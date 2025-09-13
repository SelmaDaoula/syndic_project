<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Abonnement;
use App\Models\Immeuble;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbonnementController extends Controller
{
    /**
     * Afficher la page de sélection d'abonnements
     */
    public function index()
    {
        $promoteur_id = Auth::id();
        
        // Récupérer l'immeuble du promoteur connecté
        $immeuble = Immeuble::where('promoteur_id', $promoteur_id)->first();
        $immeuble_id = $immeuble ? $immeuble->id : null;
        
        // Vérifier s'il a déjà un abonnement actif
        $activeAbonnement = Abonnement::where('promoteur_id', $promoteur_id)
            ->where('statut', 'actif')
            ->where('date_fin', '>', Carbon::now())
            ->first();

        if ($activeAbonnement) {
            return redirect()->back()->with('info', 'Vous avez déjà un abonnement actif.');
        }

        return view('promoteur.abonnements', compact('immeuble_id'));
    }

    /**
     * Traiter la sélection d'abonnement
     */
    public function process(Request $request)
    {
        $request->validate([
            'type_abonnement' => 'required|in:2_mois,3_mois,6_mois,12_mois',
            'montant' => 'required|numeric|min:0',
        ]);

        $promoteur_id = Auth::id();
        
        // Récupérer l'immeuble du promoteur
        $immeuble = Immeuble::where('promoteur_id', $promoteur_id)->first();
        
        $plans = $this->getPlansAbonnement();
        $selectedPlan = $plans[$request->type_abonnement];
        
        // Vérifier le montant
        if ($request->montant != $selectedPlan['prix']) {
            return redirect()->back()->with('error', 'Montant incorrect.');
        }

        // Créer l'abonnement en attente
        $abonnement = new Abonnement();
        $abonnement->type_abonnement = $request->type_abonnement;
        $abonnement->montant = $request->montant;
        $abonnement->promoteur_id = $promoteur_id;
        $abonnement->immeuble_id = $immeuble ? $immeuble->id : null;
        $abonnement->statut = 'en_attente';
        $abonnement->nombre_immeubles_max = $selectedPlan['max_immeubles'];
        $abonnement->date_debut = Carbon::now();
        $abonnement->date_fin = Carbon::now()->addMonths($selectedPlan['duree']);
        
        $abonnement->save();

        // Rediriger vers la page de paiement
        return redirect()->route('abonnement.payment.show', $abonnement->id)
            ->with('success', 'Abonnement sélectionné avec succès. Procédez au paiement.');
    }

    /**
     * Afficher la page de paiement
     */
    public function showPayment($abonnement_id)
    {
        $abonnement = Abonnement::where('id', $abonnement_id)
            ->where('promoteur_id', Auth::id())
            ->where('statut', 'en_attente')
            ->firstOrFail();

        $plans = $this->getPlansAbonnement();
        $planDetails = $plans[$abonnement->type_abonnement];

        return view('promoteur.paiement', compact('abonnement', 'planDetails'));
    }

    /**
     * Traiter le paiement
     */
    public function processPayment(Request $request, $abonnement_id)
    {
        $request->validate([
            'payment_method' => 'required|in:card,paypal,bank_transfer',
        ]);

        $abonnement = Abonnement::where('id', $abonnement_id)
            ->where('promoteur_id', Auth::id())
            ->where('statut', 'en_attente')
            ->firstOrFail();

        DB::beginTransaction();
        
        try {
            // Ici vous intégrerez votre système de paiement (Stripe, PayPal, etc.)
            // Pour l'exemple, on simule un paiement réussi
            
            $abonnement->statut = 'actif';
            $abonnement->save();
            
            // Désactiver les anciens abonnements du promoteur
            Abonnement::where('promoteur_id', Auth::id())
                ->where('id', '!=', $abonnement->id)
                ->where('statut', 'actif')
                ->update(['statut' => 'suspendu']);

            DB::commit();

            return redirect()->route('promoteur.dashboard')
                ->with('success', 'Paiement effectué avec succès! Votre abonnement est maintenant actif.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Erreur lors du traitement du paiement. Veuillez réessayer.');
        }
    }

    /**
     * Obtenir les plans d'abonnement disponibles
     */
    private function getPlansAbonnement()
    {
        return [
            '2_mois' => [
                'nom' => 'Starter',
                'duree' => 2,
                'prix' => 99.00,
                'max_immeubles' => 1,
                'fonctionnalites' => [
                    'Gestion de base',
                    'Support par email', 
                    'Rapports mensuels',
                    '1 immeuble inclus'
                ]
            ],
            '3_mois' => [
                'nom' => 'Professionnel',
                'duree' => 3,
                'prix' => 129.00,
                'max_immeubles' => 3,
                'fonctionnalites' => [
                    'Gestion avancée',
                    'Support prioritaire',
                    'Rapports détaillés',
                    '3 immeubles inclus'
                ]
            ],
            '6_mois' => [
                'nom' => 'Business',
                'duree' => 6,
                'prix' => 229.00,
                'max_immeubles' => 5,
                'fonctionnalites' => [
                    'Gestion complète',
                    'Support 24/7',
                    'Analytics avancés',
                    '5 immeubles inclus'
                ]
            ],
            '12_mois' => [
                'nom' => 'Enterprise',
                'duree' => 12,
                'prix' => 399.00,
                'max_immeubles' => 999,
                'fonctionnalites' => [
                    'Gestion illimitée',
                    'Manager dédié',
                    'Rapports personnalisés',
                    'Immeubles illimités'
                ]
            ]
        ];
    }

    /**
     * Obtenir l'abonnement actif du promoteur connecté
     */
    public function getAbonnementActif()
    {
        return Abonnement::where('promoteur_id', Auth::id())
            ->where('statut', 'actif')
            ->where('date_fin', '>', Carbon::now())
            ->first();
    }

    /**
     * Vérifier si le promoteur a un abonnement actif
     */
    public function hasAbonnementActif()
    {
        return $this->getAbonnementActif() !== null;
    }

    /**
     * Afficher l'historique des abonnements
     */
    public function historique()
    {
        $abonnements = Abonnement::where('promoteur_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('promoteur.abonnements.historique', compact('abonnements'));
    }
}

