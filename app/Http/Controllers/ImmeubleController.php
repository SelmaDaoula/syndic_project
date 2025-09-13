<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Immeuble;
use App\Models\Promoteur;

class ImmeubleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.suspension');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Logique différente selon le rôle
            switch ($user->role_id) {
                case 6: // Promoteur
                    return $this->indexForPromoteur($user);
                case 7: // Syndic
                    return $this->indexForSyndic($user);
                case 1: // Admin
                    return $this->indexForAdmin();
                default:
                    abort(403, 'Accès non autorisé');
            }
        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@index: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $user = Auth::user();

            // Seuls les promoteurs peuvent créer des immeubles
            if ($user->role_id !== 6) {
                abort(403, 'Seuls les promoteurs peuvent créer des immeubles');
            }

            $promoteur = Promoteur::where('user_id', $user->id)->first();
            if (!$promoteur) {
                return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            // CORRIGÉ: Utiliser $promoteur->id au lieu de $user->id
            $existingImmeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();
            if ($existingImmeuble) {
                return redirect()->route('promoteur.immeubles.index')
                    ->withErrors(['error' => 'Vous avez déjà un immeuble.']);
            }

            return view('promoteur.immeubles.immeubles_create', compact('promoteur'));

        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@create: ' . $e->getMessage());
            return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Erreur lors du chargement du formulaire.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->role_id !== 6) {
                abort(403, 'Accès non autorisé');
            }

            $promoteur = Promoteur::where('user_id', $user->id)->first();
            if (!$promoteur) {
                return redirect()->back()->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            // CORRIGÉ: Utiliser $promoteur->id au lieu de $user->id
            $existingImmeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();
            if ($existingImmeuble) {
                return redirect()->route('promoteur.immeubles.index')
                    ->withErrors(['error' => 'Vous avez déjà un immeuble.']);
            }

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:500',
                'surfaceTotal' => 'nullable|numeric|min:0|max:999999',
                'anneeConstruction' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'statut' => 'required|in:actif,construction,maintenance',
            ], [
                'nom.required' => 'Le nom de l\'immeuble est requis.',
                'adresse.required' => 'L\'adresse est requise.',
                'statut.required' => 'Le statut est requis.',
                'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            ]);

            DB::beginTransaction();

            // CORRIGÉ: Utiliser $promoteur->id au lieu de $user->id
            $immeuble = Immeuble::create([
                'nom' => $validated['nom'],
                'adresse' => $validated['adresse'],
                'surface_total' => $validated['surfaceTotal'] ?? 0,
                'nombre_blocs' => 0,
                'annee_construction' => $validated['anneeConstruction'],
                'statut' => $validated['statut'],
                'promoteur_id' => $promoteur->id, // FIXÉ: utiliser $promoteur->id (12) pas $user->id (61)
                'syndic_id' => null,
                'abonnement_id' => null,
            ]);

            DB::commit();

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Immeuble "' . $validated['nom'] . '" créé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur ImmeubleController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = Auth::user();
            $immeuble = $this->getImmeubleForUser($id, $user);

            if (!$immeuble) {
                return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            $immeuble->load([
                'blocs' => function ($query) {
                    $query->orderBy('nom');
                }
            ]);

            return view('promoteur.immeubles.immeubles_show', compact('immeuble'));

        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@show: ' . $e->getMessage());
            return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = Auth::user();
            $immeuble = $this->getImmeubleForUser($id, $user);

            if (!$immeuble) {
                return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            return view('promoteur.immeubles.immeubles_edit', compact('immeuble'));

        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@edit: ' . $e->getMessage());
            return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();
            $immeuble = $this->getImmeubleForUser($id, $user);

            if (!$immeuble) {
                return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'adresse' => 'required|string|max:500',
                'surfaceTotal' => 'nullable|numeric|min:0|max:999999',
                'anneeConstruction' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'statut' => 'required|in:actif,construction,maintenance',
            ]);

            $immeuble->update([
                'nom' => $validated['nom'],
                'adresse' => $validated['adresse'],
                'surface_total' => $validated['surfaceTotal'] ?? 0,
                'annee_construction' => $validated['anneeConstruction'],
                'statut' => $validated['statut'],
            ]);

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Immeuble mis à jour avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la mise à jour.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();

            if (!in_array($user->role_id, [1, 6])) {
                abort(403, 'Accès non autorisé');
            }

            $immeuble = $this->getImmeubleForUser($id, $user);
            if (!$immeuble) {
                return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            $nom = $immeuble->nom;
            $immeuble->delete();

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Immeuble "' . $nom . '" supprimé avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur ImmeubleController@destroy: ' . $e->getMessage());
            return redirect()->route('promoteur.dashboard')->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    // Méthodes privées corrigées

    private function indexForPromoteur($user)
    {
        $promoteur = Promoteur::where('user_id', $user->id)->first();
        if (!$promoteur) {
            abort(404, 'Profil promoteur non trouvé.');
        }

        // CORRIGÉ: Utiliser $promoteur->id au lieu de $user->id
        $immeuble = Immeuble::where('promoteur_id', $promoteur->id)
            ->with([
                'blocs' => function ($query) {
                    $query->orderBy('nom');
                }
            ])
            ->first();

        $stats = $this->calculateStats($immeuble);

        return view('promoteur.immeubles.immeubles_liste', compact('immeuble', 'promoteur', 'stats'));
    }

    private function indexForSyndic($user)
    {
        $immeubles = Immeuble::where('syndic_id', $user->id)->with('blocs')->get();
        return view('syndic.immeubles.index', compact('immeubles'));
    }

    private function indexForAdmin()
    {
        $immeubles = Immeuble::with(['blocs', 'promoteur'])->paginate(10);
        return view('admin.immeubles.index', compact('immeubles'));
    }

    private function getImmeubleForUser($id, $user)
    {
        $query = Immeuble::where('id', $id);

        switch ($user->role_id) {
            case 6: // Promoteur
                $promoteur = Promoteur::where('user_id', $user->id)->first();
                if ($promoteur) {
                    // CORRIGÉ: Utiliser $promoteur->id au lieu de $user->id
                    $query->where('promoteur_id', $promoteur->id);
                } else {
                    return null;
                }
                break;
            case 7: // Syndic
                $query->where('syndic_id', $user->id);
                break;
            case 1: // Admin
                // Pas de filtre
                break;
            default:
                return null;
        }

        return $query->first();
    }

    private function calculateStats($immeuble)
    {
        if (!$immeuble) {
            return [
                'total_blocs' => 0,
                'total_appartements' => 0,
                'surface_totale' => 0,
                'annee_construction' => 'N/A',
            ];
        }

        return [
            'total_blocs' => $immeuble->blocs->count(),
            'total_appartements' => $immeuble->blocs->sum('nombreAppartement'),
            'surface_totale' => $immeuble->surface_total ?? 0,
            'annee_construction' => $immeuble->annee_construction ?? 'N/A',
        ];
    }

    // BONUS: Méthode pour assigner un abonnement plus tard
    public function assignAbonnement(Request $request, $immeubleId)
    {
        $request->validate([
            'abonnement_id' => 'required|exists:abonnements,id'
        ]);

        $immeuble = Immeuble::findOrFail($immeubleId);
        $immeuble->update(['abonnement_id' => $request->abonnement_id]);

        return back()->with('success', 'Abonnement assigné avec succès!');
    }

    // Ajoutez cette méthode dans ImmeubleController :

/**
 * Exporter l'immeuble en PDF
 */

public function exportPdf()
{
    try {
        $user = Auth::user();
        $promoteur = Promoteur::where('user_id', $user->id)->first();
        
        if (!$promoteur) {
            return redirect()->back()->withErrors(['error' => 'Profil promoteur non trouvé.']);
        }
        
        // CORRECTION : Charger aussi les appartements
        $immeuble = Immeuble::where('promoteur_id', $promoteur->id)
            ->with(['blocs' => function ($query) {
                $query->orderBy('nom')->with('appartements'); // Ajouter ->with('appartements')
            }])
            ->first();
        
        if (!$immeuble) {
            return redirect()->back()->withErrors(['error' => 'Aucun immeuble trouvé.']);
        }
        
        // CORRECTION : Calculer les stats directement
        $stats = [
            'total_blocs' => $immeuble->blocs->count(),
            'total_appartements' => $immeuble->blocs->sum(function($bloc) { 
                return $bloc->appartements->count(); 
            }),
            'surface_totale' => $immeuble->blocs->sum('surface_totale'),
            'annee_construction' => $immeuble->annee_construction
        ];
        
        return view('promoteur.immeubles.export-pdf', compact('immeuble', 'promoteur', 'stats'));
        
    } catch (\Exception $e) {
        Log::error('Erreur ImmeubleController@exportPdf: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'export PDF.']);
    }
}
}


?>