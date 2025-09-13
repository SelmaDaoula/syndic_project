<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Bloc;
use App\Models\Immeuble;

class BlocController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.suspension');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $immeubleId = $request->input('immeuble_id');
            $user = Auth::user();

            if ($immeubleId) {
                // Vérifier l'accès à l'immeuble
                $immeuble = $this->getImmeubleForUser($immeubleId, $user);
                if (!$immeuble) {
                    return redirect()->back()->withErrors(['error' => 'Immeuble non trouvé.']);
                }

                $blocs = $immeuble->blocs()->orderBy('nom')->get();
                return view('blocs.index', compact('blocs', 'immeuble'));
            }

            // Liste générale selon le rôle
            return $this->indexByRole($user);

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@index: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $immeubleId = $request->input('immeuble_id');
            $user = Auth::user();

            if (!$immeubleId) {
                return redirect()->back()->withErrors(['error' => 'ID immeuble requis.']);
            }

            $immeuble = $this->getImmeubleForUser($immeubleId, $user);
            if (!$immeuble) {
                return redirect()->back()->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            // Vérifier les permissions de création
            if (!$this->canManageBlocs($user, $immeuble)) {
                abort(403, 'Vous n\'avez pas l\'autorisation de créer des blocs pour cet immeuble.');
            }

            return view('blocs.create', compact('immeuble'));

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@create: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement du formulaire.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    
  /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $immeubleId = $request->input('immeuble_id');

            $immeuble = $this->getImmeubleForUser($immeubleId, $user);
            if (!$immeuble) {
                return redirect()->back()->withErrors(['error' => 'Immeuble non trouvé.']);
            }

            if (!$this->canManageBlocs($user, $immeuble)) {
                abort(403, 'Vous n\'avez pas l\'autorisation de créer des blocs.');
            }

            // VALIDATION CORRIGÉE avec les noms des champs du formulaire
            $validated = $request->validate([
                'nom' => 'required|string|max:255|unique:blocs,nom,NULL,id,Immeuble_id,' . $immeuble->id,
                'nombreAppartement' => 'required|integer|min:1|max:200',
                'nombreEtages' => 'required|integer|min:1|max:50',
                'surfaceTotale' => 'nullable|numeric|min:0|max:99999',
            ], [
                'nom.required' => 'Le nom du bloc est requis.',
                'nom.unique' => 'Ce nom de bloc existe déjà pour cet immeuble.',
                'nombreAppartement.required' => 'Le nombre d\'appartements est requis.',
                'nombreEtages.required' => 'Le nombre d\'étages est requis.',
            ]);

            DB::beginTransaction();

            // CRÉATION du bloc SANS génération d'appartements
            $bloc = Bloc::create([
                'nom' => $validated['nom'],
                'nombre_appartement' => $validated['nombreAppartement'],
                'nombre_etages' => $validated['nombreEtages'],
                'surface_totale' => $validated['surfaceTotale'] ?? 0,
                'Immeuble_id' => $immeuble->id,
            ]);

            // ❌ SUPPRIMER CETTE LIGNE - PAS DE GÉNÉRATION AUTOMATIQUE ❌
            // $appartementController = new AppartementController();
            // $appartementController->generateApartmentsForBloc($bloc, $validated['nombreAppartement'], $validated['nombreEtages']);

            // Mettre à jour le nombre de blocs dans l'immeuble
            $immeuble->nombre_blocs = $immeuble->blocs()->count();
            $immeuble->save();

            DB::commit();

            return redirect()->route('promoteur.immeubles.index')
                ->with('success', 'Bloc "' . $validated['nom'] . '" créé avec succès! Cliquez sur "Générer" pour ajouter les appartements.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur BlocController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création du bloc: ' . $e->getMessage()])
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
            $bloc = $this->getBlocForUser($id, $user);

            if (!$bloc) {
                return redirect()->back()->withErrors(['error' => 'Bloc non trouvé.']);
            }

            $bloc->load(['immeuble', 'appartements']);

            return view('blocs.show', compact('bloc'));

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@show: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = Auth::user();
            $bloc = $this->getBlocForUser($id, $user);

            if (!$bloc) {
                return redirect()->back()->withErrors(['error' => 'Bloc non trouvé.']);
            }

            if (!$this->canManageBlocs($user, $bloc->immeuble)) {
                abort(403, 'Vous n\'avez pas l\'autorisation de modifier ce bloc.');
            }

            return view('blocs.edit', compact('bloc'));

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@edit: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors du chargement.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();
            $bloc = $this->getBlocForUser($id, $user);

            if (!$bloc) {
                return redirect()->back()->withErrors(['error' => 'Bloc non trouvé.']);
            }

            if (!$this->canManageBlocs($user, $bloc->immeuble)) {
                abort(403, 'Vous n\'avez pas l\'autorisation de modifier ce bloc.');
            }

            $validated = $request->validate([
                'nom' => 'required|string|max:255|unique:blocs,nom,' . $id . ',id,Immeuble_id,' . $bloc->Immeuble_id,
                'nombreAppartement' => 'required|integer|min:1|max:200',
                'nombreEtages' => 'required|integer|min:1|max:50',
                'surfaceTotale' => 'nullable|numeric|min:0|max:99999',
            ]);

            $bloc->update($validated);

            // Redirection selon le rôle
            if ($user->role_id === 6) { // Promoteur
                return redirect()->route('promoteur.immeubles.index')
                    ->with('success', 'Bloc "' . $validated['nom'] . '" mis à jour avec succès!');
            } else {
                return redirect()->route('blocs.index', ['immeuble_id' => $bloc->Immeuble_id])
                    ->with('success', 'Bloc "' . $validated['nom'] . '" mis à jour avec succès!');
            }

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@update: ' . $e->getMessage());
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
            $bloc = $this->getBlocForUser($id, $user);

            if (!$bloc) {
                return redirect()->back()->withErrors(['error' => 'Bloc non trouvé.']);
            }

            if (!$this->canManageBlocs($user, $bloc->immeuble)) {
                abort(403, 'Vous n\'avez pas l\'autorisation de supprimer ce bloc.');
            }

            DB::beginTransaction();

            $nom = $bloc->nom;
            $immeubleId = $bloc->Immeuble_id;
            $bloc->delete();

            // Mettre à jour le nombre de blocs
            $immeuble = Immeuble::find($immeubleId);
            if ($immeuble) {
                $immeuble->nombre_blocs = $immeuble->blocs()->count();
                $immeuble->save();
            }

            DB::commit();

            // Redirection selon le rôle
            if ($user->role_id === 6) { // Promoteur
                return redirect()->route('promoteur.immeubles.index')
                    ->with('success', 'Bloc "' . $nom . '" supprimé avec succès!');
            } else {
                return redirect()->route('blocs.index', ['immeuble_id' => $immeubleId])
                    ->with('success', 'Bloc "' . $nom . '" supprimé avec succès!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur BlocController@destroy: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    // Méthodes privées pour la logique métier

    private function indexByRole($user)
    {
        switch ($user->role_id) {
            case 6: // Promoteur
                $immeuble = Immeuble::where('promoteur_id', $user->id)->first();
                if ($immeuble) {
                    return redirect()->route('blocs.index', ['immeuble_id' => $immeuble->id]);
                }
                return redirect()->route('immeubles.index')->withErrors(['error' => 'Aucun immeuble trouvé.']);

            case 7: // Syndic
                $immeubles = Immeuble::where('syndic_id', $user->id)->with('blocs')->get();
                return view('syndic.blocs.index', compact('immeubles'));

            case 1: // Admin
                $blocs = Bloc::with('immeuble')->paginate(20);
                return view('admin.blocs.index', compact('blocs'));

            default:
                abort(403, 'Accès non autorisé');
        }
    }

    private function getImmeubleForUser($id, $user)
    {
        $query = Immeuble::where('id', $id);

        switch ($user->role_id) {
            case 6: // Promoteur
                // CORRECTION pour les promoteurs
                $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
                if ($promoteur) {
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
    private function getBlocForUser($id, $user)
    {
        $query = Bloc::with('immeuble')->where('id', $id);

        switch ($user->role_id) {
            case 6: // Promoteur
                $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
                if ($promoteur) {
                    $query->whereHas('immeuble', function ($q) use ($promoteur) {
                        $q->where('promoteur_id', $promoteur->id);
                    });
                } else {
                    return null;
                }
                break;
            case 7: // Syndic
                $query->whereHas('immeuble', function ($q) use ($user) {
                    $q->where('syndic_id', $user->id);
                });
                break;
            case 1: // Admin
                // Pas de filtre
                break;
            default:
                return null;
        }

        return $query->first();
    }
    private function canManageBlocs($user, $immeuble)
    {
        switch ($user->role_id) {
            case 1: // Admin
                return true;
            case 6: // Promoteur
                // CORRECTION pour les promoteurs
                $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
                return $promoteur && $immeuble->promoteur_id == $promoteur->id;
            case 7: // Syndic
                return $immeuble->syndic_id == $user->id;
            default:
                return false;
        }
    }

    // Ajoutez cette méthode dans BlocController :

    /**
     * Formulaire de création de bloc spécifique aux promoteurs
     */
    public function createForPromoteur()
    {
        try {
            $user = Auth::user();

            // Récupérer le promoteur
            $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
            if (!$promoteur) {
                return redirect()->route('promoteur.dashboard')
                    ->withErrors(['error' => 'Profil promoteur non trouvé.']);
            }

            // Récupérer automatiquement l'immeuble du promoteur
            $immeuble = Immeuble::where('promoteur_id', $promoteur->id)->first();

            if (!$immeuble) {
                return redirect()->route('promoteur.immeubles.create')
                    ->withErrors(['error' => 'Créez d\'abord un immeuble avant d\'ajouter des blocs.']);
            }

            return view('promoteur.blocs.create', compact('immeuble'));

        } catch (\Exception $e) {
            Log::error('Erreur BlocController@createForPromoteur: ' . $e->getMessage());
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Erreur lors du chargement du formulaire.']);
        }
    }

    // Ajoutez cette méthode privée dans BlocController :

    /**
     * Générer automatiquement les appartements pour un bloc
     */
 
public function generateApartments($blocId)
{
    $user = Auth::user();
    
    // Récupérer le promoteur
    $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
    if (!$promoteur) {
        return response()->json([
            'success' => false,
            'message' => 'Promoteur non trouvé.'
        ]);
    }
    
    $bloc = Bloc::whereHas('immeuble', function($q) use ($promoteur) {
        $q->where('promoteur_id', $promoteur->id);
    })->findOrFail($blocId);
    
    if ($bloc->appartements->count() > 0) {
        return response()->json([
            'success' => false,
            'message' => 'Ce bloc a déjà des appartements.'
        ]);
    }
    
    $appartementController = new AppartementController();
    $result = $appartementController->generateApartmentsForBloc($bloc);
    
    return response()->json($result);
}

public function regenerateApartments($blocId)
{
    $user = Auth::user();
    
    // Récupérer le promoteur
    $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
    if (!$promoteur) {
        return response()->json([
            'success' => false,
            'message' => 'Promoteur non trouvé.'
        ]);
    }
    
    $bloc = Bloc::whereHas('immeuble', function($q) use ($promoteur) {
        $q->where('promoteur_id', $promoteur->id);
    })->findOrFail($blocId);
    
    $appartementController = new AppartementController();
    $result = $appartementController->generateApartmentsForBloc($bloc);
    
    return response()->json($result);
}

    
}