<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Immeuble;
use App\Models\Bloc;
use App\Models\Promoteur;
use App\Models\User;

class PromoteurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.suspension');
        $this->middleware('promoteur');
    }

    /**
     * Dashboard du promoteur avec vue d'ensemble
     */

    /**
     * Dashboard du promoteur avec vue d'ensemble
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            if (!$promoteur) {
                return redirect()->route('dashboard')
                    ->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            $immeuble = Immeuble::where('promoteur_id', $user->id)->with('blocs')->first();

            // Statistiques pour le dashboard
            $stats = [
                'has_immeuble' => $immeuble ? true : false,
                'total_blocs' => $immeuble ? $immeuble->blocs->count() : 0,
                'total_appartements' => $immeuble ? $immeuble->blocs->sum('nombreAppartement') : 0,
                'syndic_assigned' => $immeuble && $immeuble->syndic_id ? true : false,
                'immeuble_status' => $immeuble ? $immeuble->statut : null,
            ];

            // FIXÉ: Utiliser la vue qui existe
            return view('dashboards.promoteur', compact('promoteur', 'immeuble', 'stats'));

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@dashboard: ' . $e->getMessage());
            return redirect()->route('dashboard')->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    // SUPPRIMÉ : index(), create(), store() - maintenant gérés par ImmeubleController

    /**
     * Afficher le formulaire d'assignation de syndic
     */
    /**
     * Afficher le formulaire d'assignation de syndic
     */

    public function showAssignSyndic()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            if (!$promoteur) {
                return redirect()->route('promoteur.dashboard')
                    ->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            $immeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();

            if (!$immeuble) {
                return redirect()->route('promoteur.immeubles.create')
                    ->withErrors(['error' => 'Créez d\'abord un immeuble avant d\'assigner un syndic.']);
            }

            // CORRIGÉ : Utiliser la table syndicats
            $syndicatsDisponibles = \App\Models\Syndicat::where('is_suspended', 0)
                ->whereNotIn('id', function ($query) {
                    $query->select('syndic_id')
                        ->from('immeubles')
                        ->whereNotNull('syndic_id');
                })
                ->get();

            return view('promoteur.syndics.assign', compact('promoteur', 'immeuble', 'syndicatsDisponibles'));

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@showAssignSyndic: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }
    /**
     * Assigner un syndic à l'immeuble du promoteur
     */
    /**
     * Assigner un syndic à l'immeuble du promoteur
     */
    public function assignSyndic(Request $request)
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            if (!$promoteur) {
                return redirect()->back()->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            $immeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();

            if (!$immeuble) {
                return redirect()->back()->withErrors(['error' => 'Aucun immeuble trouvé.']);
            }

            $request->validate([
                'syndic_id' => 'required|exists:syndicats,id', // CORRIGÉ : syndicats au lieu de users
            ], [
                'syndic_id.required' => 'Veuillez sélectionner un syndic.',
                'syndic_id.exists' => 'Le syndic sélectionné n\'existe pas.',
            ]);

            // CORRIGÉ : Vérifier dans la table syndicats
            $syndic = \App\Models\Syndicat::where('id', $request->syndic_id)
                ->where('is_suspended', 0)
                ->first();

            if (!$syndic) {
                return redirect()->back()->withErrors(['error' => 'Syndic non valide ou suspendu.']);
            }

            // Vérifier que le syndic n'est pas déjà assigné
            $existingAssignment = Immeuble::where('syndic_id', $syndic->id)->first();
            if ($existingAssignment) {
                return redirect()->back()->withErrors(['error' => 'Ce syndic est déjà assigné à un autre immeuble.']);
            }

            DB::beginTransaction();

            // Assigner le syndic
            $immeuble->syndic_id = $syndic->id;
            $immeuble->save();

            Log::info('Syndic assigné', [
                'syndic_id' => $syndic->id,
                'syndic_name' => $syndic->nom . ' ' . $syndic->prenom,
                'immeuble_id' => $immeuble->id,
                'promoteur_id' => $promoteur->id
            ]);

            DB::commit();

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Syndic "' . $syndic->nom . ' ' . $syndic->prenom . '" assigné avec succès à votre immeuble!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur PromoteurController@assignSyndic: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de l\'assignation du syndic.'])
                ->withInput();
        }
    }
    /**
     * Retirer l'assignation du syndic
     */
    public function unassignSyndic()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            if (!$promoteur) {
                return redirect()->back()->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            $immeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();

            if (!$immeuble || !$immeuble->syndic_id) {
                return redirect()->back()->withErrors(['error' => 'Aucun syndic assigné.']);
            }

            DB::beginTransaction();

            // CORRIGÉ : Récupérer depuis la table syndicats
            $syndic = \App\Models\Syndicat::find($immeuble->syndic_id);
            $syndicName = $syndic ? ($syndic->nom . ' ' . $syndic->prenom) : 'Syndic';

            $immeuble->syndic_id = null;
            $immeuble->save();

            DB::commit();

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Syndic "' . $syndicName . '" retiré avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur PromoteurController@unassignSyndic: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du retrait du syndic.']);
        }
    }
    private function createSyndicNotification($syndic, $immeuble, $action)
    {
        try {
            $message = $action === 'assigned'
                ? "Vous avez été assigné comme syndic de l'immeuble {$immeuble->nom}"
                : "Votre assignation pour l'immeuble {$immeuble->nom} a été retirée";

            // Si vous avez une table notifications, ajoutez ici
            // Notification::create([...]);

            Log::info("Notification syndic: {$message}", [
                'syndic_id' => $syndic->id,
                'immeuble_id' => $immeuble->id,
                'action' => $action
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur notification syndic: ' . $e->getMessage());
        }
    }


    /**
     * Gestion de l'abonnement du promoteur
     */
    public function subscription()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            // Récupérer l'abonnement actuel
            $abonnement = DB::table('abonnements')
                ->where('promoteur_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $subscriptionStatus = [
                'has_subscription' => $abonnement ? true : false,
                'is_active' => $abonnement && $abonnement->dateFin >= date('Y-m-d'),
                'expires_soon' => false,
                'days_remaining' => 0,
            ];

            if ($abonnement) {
                $expireDate = new \DateTime($abonnement->dateFin);
                $today = new \DateTime();
                $interval = $today->diff($expireDate);

                $subscriptionStatus['days_remaining'] = $expireDate > $today ? $interval->days : 0;
                $subscriptionStatus['expires_soon'] = $subscriptionStatus['days_remaining'] <= 7 && $subscriptionStatus['days_remaining'] > 0;
            }

            return view('promoteur.abonnements', compact('promoteur', 'abonnement', 'subscriptionStatus'));

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@subscription: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement de l\'abonnement.']);
        }
    }

    /**
     * Afficher les rapports et statistiques du promoteur
     */
    public function reports()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();
            $immeuble = Immeuble::where('promoteur_id', $user->id)->with('blocs')->first();

            if (!$immeuble) {
                return redirect()->route('promoteur.immeubles.create')
                    ->withErrors(['error' => 'Créez d\'abord un immeuble pour voir les rapports.']);
            }

            // Calculer les statistiques détaillées
            $stats = [
                'immeubles' => [
                    'total' => 1,
                    'surface_totale' => $immeuble->surface_total,
                    'annee_construction' => $immeuble->annee_construction,
                    'statut' => $immeuble->statut,
                ],
                'blocs' => [
                    'total' => $immeuble->blocs->count(),
                    'details' => $immeuble->blocs->map(function ($bloc) {
                        return [
                            'nom' => $bloc->nom,
                            'appartements' => $bloc->nombreAppartement,
                            'etages' => $bloc->nombreEtages,
                            'surface' => $bloc->surfaceTotale,
                        ];
                    }),
                ],
                'appartements' => [
                    'total' => $immeuble->blocs->sum('nombreAppartement'),
                    'par_bloc' => $immeuble->blocs->pluck('nombreAppartement', 'nom'),
                ],
            ];

            return view('promoteur.rapports.index', compact('promoteur', 'immeuble', 'stats'));

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@reports: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement des rapports.']);
        }
    }

    /**
     * Profil et paramètres du promoteur
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            return view('promoteur.profile.show', compact('user', 'promoteur'));

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@profile: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement du profil.']);
        }
    }

    /**
     * Mettre à jour le profil du promoteur
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();

            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            // Mettre à jour l'utilisateur
            $user->update([
                'name' => $request->prenom . ' ' . $request->nom,
                'email' => $request->email,
            ]);

            // Mettre à jour le promoteur
            $promoteur->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'telephone' => $request->telephone,
                'email' => $request->email,
            ]);

            return redirect()->route('promoteur.profile')
                ->with('success', 'Profil mis à jour avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur PromoteurController@updateProfile: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour du profil.'])
                ->withInput();
        }
    }

    /**
     * API pour récupérer les données du dashboard
     */
    public function getDashboardData()
    {
        try {
            $user = Auth::user();
            $promoteur = Promoteur::where('user_id', $user->id)->first();
            $immeuble = Immeuble::where('promoteur_id', $user->id)->with('blocs')->first();

            $data = [
                'promoteur' => [
                    'nom' => $promoteur->nom,
                    'prenom' => $promoteur->prenom,
                    'email' => $promoteur->email,
                ],
                'immeuble' => $immeuble ? [
                    'id' => $immeuble->id,
                    'nom' => $immeuble->nom, // CORRIGÉ : utiliser 'nom' au lieu de 'ImmeubleNom'
                    'statut' => $immeuble->statut,
                    'has_syndic' => $immeuble->syndic_id ? true : false,
                ] : null,
                'stats' => [
                    'total_blocs' => $immeuble ? $immeuble->blocs->count() : 0,
                    'total_appartements' => $immeuble ? $immeuble->blocs->sum('nombreAppartement') : 0,
                ],
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }
}
?>