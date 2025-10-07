<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\PrixAbonnement;
use App\Models\Promoteur;
use App\Services\KonnectPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbonnementController extends Controller
{
    protected $konnectService;

    public function __construct(KonnectPaymentService $konnectService)
    {
        $this->konnectService = $konnectService;
    }

    /**
     * Afficher la page de choix d'abonnements
     */
    public function index()
    {
        $prixAbonnements = PrixAbonnement::where('is_active', true)
            ->orderBy('duree_mois')
            ->get();

        $immeuble_id = request('immeuble_id');

        return view('promoteur.abonnements', compact('prixAbonnements', 'immeuble_id'));
    }

    /**
     * Traiter la sélection d'abonnement
     */
    public function process(Request $request)
    {
        Log::info('=== DÉBUT PROCESS ABONNEMENT ===', $request->all());

        try {
            // Validation
            $request->validate([
                'type_abonnement' => 'required|string',
                'montant' => 'required|numeric|min:0',
                'promoteur_id' => 'required|exists:promoteurs,id'
            ]);

            // Récupérer le promoteur et vérifier les droits
            $promoteur = Promoteur::find($request->promoteur_id);
            
            if (!$promoteur || $promoteur->user_id != Auth::id()) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }

            // Récupérer le prix d'abonnement
            $prixAbonnement = PrixAbonnement::where('type_abonnement', $request->type_abonnement)
                ->where('is_active', true)
                ->first();

            if (!$prixAbonnement) {
                return response()->json(['error' => 'Type d\'abonnement invalide'], 400);
            }

            // Calculer les dates
            $dateDebut = Carbon::now();
            $dateFin = $dateDebut->copy()->addMonths($prixAbonnement->duree_mois);

            // Créer l'abonnement
            $abonnement = Abonnement::create([
                'promoteur_id' => $promoteur->id,
                'type_abonnement' => $request->type_abonnement,
                'montant' => $prixAbonnement->prix,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'statut' => 'en_attente',
                'nombre_immeubles_max' => $prixAbonnement->max_immeubles,
                'immeuble_id' => $request->immeuble_id
            ]);

            Log::info('Abonnement créé', ['id' => $abonnement->id]);

            // Initialiser le paiement avec Konnect
            $paymentResult = $this->konnectService->initPayment($abonnement, $promoteur);

            if ($paymentResult['success']) {
                return response()->json([
                    'success' => true,
                    'redirectUrl' => $paymentResult['payUrl'],
                    'paymentRef' => $paymentResult['paymentRef']
                ]);
            } else {
                // Supprimer l'abonnement en cas d'erreur
                $abonnement->delete();
                return response()->json([
                    'error' => $paymentResult['error'] ?? 'Erreur lors de l\'initialisation du paiement'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Erreur process abonnement', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erreur technique'], 500);
        }
    }

    /**
     * Webhook pour les notifications de paiement
     */
    public function webhook(Request $request)
    {
        Log::info('=== WEBHOOK KONNECT REÇU ===', $request->all());

        $paymentRef = $request->query('payment_ref');
        
        if (!$paymentRef) {
            Log::error('Payment ref manquant');
            return response('Payment ref missing', 400);
        }

        $success = $this->konnectService->processWebhook($paymentRef);

        return response($success ? 'OK' : 'ERROR', $success ? 200 : 500);
    }

    /**
     * Vérifier manuellement le statut d'un paiement
     */
    public function checkStatus($paymentRef)
    {
        $abonnement = Abonnement::where('payment_ref', $paymentRef)->first();
        
        if (!$abonnement) {
            return response()->json(['error' => 'Abonnement non trouvé'], 404);
        }

        $paymentStatus = $this->konnectService->checkPaymentStatus($paymentRef);

        if ($paymentStatus && $paymentStatus['status'] === 'completed') {
            $abonnement->update([
                'statut' => 'actif',
                'payment_completed_at' => now()
            ]);
        }

        return response()->json([
            'abonnement' => $abonnement->fresh(),
            'payment_status' => $paymentStatus
        ]);
    }
}