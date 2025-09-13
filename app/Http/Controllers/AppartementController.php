<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Bloc;
use App\Models\TypeAppartement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AppartementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Récupérer le promoteur
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();
        if (!$promoteur) {
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Promoteur non trouvé.']);
        }

        // Récupérer les appartements
        $appartements = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
            $q->where('promoteur_id', $promoteur->id);
        })->with(['bloc.immeuble'])
            ->when($request->filled('bloc_id'), function ($query) use ($request) {
                $query->where('bloc_id', $request->bloc_id);
            })
            ->orderBy('bloc_id')
            ->orderBy('numero')
            ->paginate(20);

        return view('promoteur.appartements.index', compact('appartements'));
    }

    /**
     * Afficher un appartement spécifique
     */
    public function show($id)
    {
        $user = Auth::user();
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();

        if (!$promoteur) {
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Promoteur non trouvé.']);
        }

        $appartement = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
            $q->where('promoteur_id', $promoteur->id);
        })->with(['bloc.immeuble', 'proprietaire'])->findOrFail($id);

        return view('promoteur.appartements.show', compact('appartement'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $user = Auth::user();
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();

        if (!$promoteur) {
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Promoteur non trouvé.']);
        }

        $appartement = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
            $q->where('promoteur_id', $promoteur->id);
        })->with(['bloc.immeuble'])->findOrFail($id);

        // CORRECTION : Types selon ta DB
        $typesAppartement = ['studio', 'F2', 'F3', 'F4', 'F5+'];
        $statutsAppartement = ['libre', 'occupe', 'travaux', 'reserve', 'maintenance'];

        return view('promoteur.appartements.edit', compact('appartement', 'typesAppartement', 'statutsAppartement'));
    }


    /**
     * Mettre à jour un appartement
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();

        if (!$promoteur) {
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Promoteur non trouvé.']);
        }

        $appartement = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
            $q->where('promoteur_id', $promoteur->id);
        })->findOrFail($id);

        // CORRECTION : Validation selon ta DB
        $validated = $request->validate([
            'type_appartement' => 'required|string|in:studio,F2,F3,F4,F5+',
            'surface' => 'required|numeric|min:10|max:1000',
            'nombre_pieces' => 'required|integer|min:1|max:20',
            'numero' => [
                'required',
                'string',
                'max:10',
                Rule::unique('appartements')->where(function ($query) use ($appartement) {
                    return $query->where('bloc_id', $appartement->bloc_id);
                })->ignore($appartement->id)
            ],
            'statut' => 'required|in:libre,occupe,travaux,reserve,maintenance'
        ], [
            'type_appartement.required' => 'Le type d\'appartement est requis.',
            'surface.required' => 'La surface est requise.',
            'surface.min' => 'La surface doit être d\'au moins 10 m².',
            'nombre_pieces.required' => 'Le nombre de pièces est requis.',
            'numero.required' => 'Le numéro d\'appartement est requis.',
            'numero.unique' => 'Ce numéro d\'appartement existe déjà dans ce bloc.',
            'statut.required' => 'Le statut est requis.'
        ]);

        try {
            $appartement->update($validated);

            return redirect()
                ->route('promoteur.appartements.show', $appartement->id)
                ->with('success', 'Appartement mis à jour avec succès');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }



    /**
     * Supprimer un appartement
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();

        if (!$promoteur) {
            return redirect()->route('promoteur.immeubles.index')
                ->withErrors(['error' => 'Promoteur non trouvé.']);
        }

        $appartement = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
            $q->where('promoteur_id', $promoteur->id);
        })->findOrFail($id);

        // Vérifier que l'appartement peut être supprimé
        if ($appartement->statut === 'occupe') {
            return back()->withErrors(['error' => 'Impossible de supprimer un appartement occupé']);
        }

        try {
            $blocId = $appartement->bloc_id;
            $appartement->delete();

            return redirect()
                ->route('promoteur.appartements.index', ['bloc_id' => $blocId])
                ->with('success', 'Appartement supprimé avec succès');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Génération automatique des appartements pour un bloc
     */
    public function generateApartmentsForBloc($bloc, $nombreAppartements = null, $nombreEtages = null)
    {
        try {
            DB::beginTransaction();

            // Supprimer les appartements existants
            $bloc->appartements()->delete();

            $nombreAppartements = $nombreAppartements ?? $bloc->nombre_appartement;
            $nombreEtages = $nombreEtages ?? $bloc->nombre_etages;

            $appartementsParEtage = ceil($nombreAppartements / $nombreEtages);
            $appartements = [];
            $compteur = 1;

            for ($etage = 1; $etage <= $nombreEtages; $etage++) {
                for ($appt = 1; $appt <= $appartementsParEtage && $compteur <= $nombreAppartements; $appt++) {
                    $numero = $etage . str_pad($appt, 2, '0', STR_PAD_LEFT);

                    $appartements[] = [
                        'bloc_id' => $bloc->id,
                        'type_appartement' => 'F3', // Type par défaut selon ta DB
                        'surface' => null, // AUCUNE SURFACE PAR DÉFAUT
                        'nombre_pieces' => 3,
                        'numero' => $numero,
                        'statut' => 'libre',
                        'proprietaire_id' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $compteur++;
                }
            }

            Appartement::insert($appartements);
            DB::commit();

            return [
                'success' => true,
                'message' => count($appartements) . ' appartements générés avec succès (surface à définir manuellement)'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Modification en masse
     */
    public function bulkUpdate(Request $request)
    {
        $user = Auth::user();
        $promoteur = \App\Models\Promoteur::where('user_id', $user->id)->first();

        if (!$promoteur) {
            return response()->json(['error' => 'Promoteur non trouvé'], 403);
        }

        $validated = $request->validate([
            'appartement_ids' => 'required|array',
            'appartement_ids.*' => 'integer',
            'bulk_action' => 'required|in:update_status,update_type',
            'statut' => 'nullable|in:libre,occupe,travaux,reserve',
            'type_appartement' => 'nullable|in:F1,F2,F3,F4,F5,Studio,Duplex'
        ]);

        try {
            DB::beginTransaction();

            $appartements = Appartement::whereHas('bloc.immeuble', function ($q) use ($promoteur) {
                $q->where('promoteur_id', $promoteur->id);
            })->whereIn('id', $validated['appartement_ids']);

            $updateData = [];

            if ($validated['bulk_action'] === 'update_status' && isset($validated['statut'])) {
                $updateData['statut'] = $validated['statut'];
                $message = 'Statut mis à jour pour ' . count($validated['appartement_ids']) . ' appartements';
            }

            if ($validated['bulk_action'] === 'update_type' && isset($validated['type_appartement'])) {
                $updateData['type_appartement'] = $validated['type_appartement'];
                $message = 'Type mis à jour pour ' . count($validated['appartement_ids']) . ' appartements';
            }

            if (!empty($updateData)) {
                $appartements->update($updateData);
            }

            DB::commit();

            return back()->with('success', $message ?? 'Opération effectuée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}