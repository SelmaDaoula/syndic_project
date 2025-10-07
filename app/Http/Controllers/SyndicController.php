<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Immeuble;
use App\Models\Appartement;
use App\Models\Proprietaire;
use App\Models\Locataire;
use App\Models\TicketIncident;
use App\Models\Depense;
use App\Models\Paiement;
use App\Models\Technicien;

class SyndicController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'syndic']);
    }

    /**
     * Dashboard syndic
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Récupérer le profil syndic de l'utilisateur
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil syndic non trouvé.');
        }
        
        // Trouver l'immeuble géré par ce syndic
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        // Statistiques par défaut
        $stats = [
            'total_appartements' => 0,
            'appartements_occupes' => 0,
            'total_proprietaires' => 0,
            'total_locataires' => 0,
            'tickets_ouverts' => 0,
            'tickets_en_cours' => 0,
            'paiements_en_retard' => 0,
            'depenses_mois' => 0
        ];
        
        // Collections vides par défaut
        $derniers_tickets = collect();
        $paiements_recents = collect();

        // Si un immeuble est assigné, calculer les vraies statistiques
        if ($immeuble) {
            try {
                // Statistiques basiques
                $stats['total_appartements'] = Appartement::whereHas('bloc', function($query) use ($immeuble) {
                    $query->where('immeuble_id', $immeuble->id);
                })->count();
                
                $stats['appartements_occupes'] = Appartement::whereHas('bloc', function($query) use ($immeuble) {
                    $query->where('immeuble_id', $immeuble->id);
                })->whereNotNull('proprietaire_id')->count();
                
                $stats['tickets_ouverts'] = TicketIncident::where('immeuble_id', $immeuble->id)
                    ->where('statut', 'ouvert')->count();
                    
                $stats['tickets_en_cours'] = TicketIncident::where('immeuble_id', $immeuble->id)
                    ->where('statut', 'en_cours')->count();
                
                // Derniers tickets
                $derniers_tickets = TicketIncident::where('immeuble_id', $immeuble->id)
                    ->with(['createdBy', 'appartement'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                // Paiements récents
                $paiements_recents = Paiement::where('immeuble_id', $immeuble->id)
                    ->with(['user', 'appartement'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                    
            } catch (\Exception $e) {
                \Log::error('Erreur dashboard syndic: ' . $e->getMessage());
            }
        }

        return view('dashboards.syndic', compact('immeuble', 'stats', 'derniers_tickets', 'paiements_recents'));
    }

    /**
     * Afficher l'immeuble du syndic
     */
    public function showImmeuble()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->with(['blocs.appartements'])->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        return view('syndic.immeuble', compact('immeuble'));
    }

    /**
     * Liste des appartements
     */
    public function appartements()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $appartements = Appartement::whereHas('bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with(['bloc', 'proprietaire.user', 'locataire.user'])
        ->orderBy('numero')
        ->paginate(15);
        
        return view('syndic.appartements', compact('immeuble', 'appartements'));
    }

    /**
     * Liste des résidents
     */
    public function residents()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        // Récupérer tous les propriétaires et locataires de l'immeuble
        $proprietaires = Proprietaire::whereHas('appartements.bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with(['user', 'appartements'])->get();
        
        $locataires = Locataire::whereHas('appartement.bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with(['user', 'appartement'])->get();
        
        return view('syndic.residents', compact('immeuble', 'proprietaires', 'locataires'));
    }

    /**
     * Liste des tickets
     */
    public function tickets()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }

        // Récupérer tous les tickets de l'immeuble
        $tickets = TicketIncident::where('immeuble_id', $immeuble->id)
            ->with(['appartement.bloc', 'createdBy', 'assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistiques
        $stats = [
            'total' => TicketIncident::where('immeuble_id', $immeuble->id)->count(),
            'ouverts' => TicketIncident::where('immeuble_id', $immeuble->id)->where('statut', 'ouvert')->count(),
            'en_cours' => TicketIncident::where('immeuble_id', $immeuble->id)->where('statut', 'en_cours')->count(),
            'resolus' => TicketIncident::where('immeuble_id', $immeuble->id)->where('statut', 'resolu')->count(),
        ];

        return view('syndic.tickets.index', compact('immeuble', 'tickets', 'stats'));
    }

    /**
     * Afficher un ticket spécifique
     */
    public function showTicket(TicketIncident $ticket)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        // Vérifier que le ticket appartient à l'immeuble du syndic
        if (!$immeuble || $ticket->immeuble_id !== $immeuble->id) {
            abort(403, 'Accès refusé à ce ticket.');
        }

        $ticket->load(['appartement.bloc', 'createdBy', 'assignedTo']);

        // Récupérer les techniciens disponibles pour assignation
        $techniciens = Technicien::where('immeuble_id', $immeuble->id)
            ->where('is_suspended', 0)
            ->with('user')
            ->get();

        return view('syndic.tickets.ticket-details', compact('ticket', 'techniciens', 'immeuble'));
    }

    /**
     * Formulaire de création de ticket
     */
    public function createTicket()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }

        // Récupérer les appartements et techniciens
        $appartements = Appartement::whereHas('bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with('bloc')->orderBy('numero')->get();

        $techniciens = Technicien::where('immeuble_id', $immeuble->id)
            ->where('is_suspended', 0)
            ->with('user')
            ->get();

        return view('syndic.tickets.ticket-create', compact('immeuble', 'appartements', 'techniciens'));
    }

    /**
     * Enregistrer un nouveau ticket
     */
    public function storeTicket(Request $request)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'appartement_id' => 'required|exists:appartements,id',
            'type_incident' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute,urgente',
            'assignee_id' => 'nullable|exists:users,id',
            'cout_estime' => 'nullable|numeric|min:0',
            'photos.*' => 'nullable|image|max:2048'
        ]);

        // Récupérer le bloc de l'appartement
        $appartement = Appartement::with('bloc')->find($validated['appartement_id']);
        
        $ticketData = array_merge($validated, [
            'immeuble_id' => $immeuble->id,
            'bloc_id' => $appartement->bloc_id,
            'created_by' => $user->id,
            'statut' => $validated['assignee_id'] ? 'en_cours' : 'ouvert',
            'date_incident' => now()
        ]);

        // Gérer l'upload des photos
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('tickets/photos', 'public');
                $photos[] = $path;
            }
            $ticketData['photos'] = $photos;
        }

        $ticket = TicketIncident::create($ticketData);

        return redirect()->route('syndic.tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès. Numéro: ' . $ticket->numero_ticket);
    }

    /**
     * Mettre à jour le statut d'un ticket
     */
    public function updateTicketStatus(Request $request, TicketIncident $ticket)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        // Vérifier l'accès
        if (!$immeuble || $ticket->immeuble_id !== $immeuble->id) {
            abort(403);
        }

        $validated = $request->validate([
            'statut' => 'required|in:ouvert,en_cours,resolu,ferme',
            'assignee_id' => 'nullable|exists:users,id',
            'notes_resolution' => 'nullable|string',
            'cout_reel' => 'nullable|numeric|min:0'
        ]);

        $updateData = $validated;
        
        // Si le ticket est marqué comme résolu, enregistrer la date
        if ($validated['statut'] === 'resolu' && $ticket->statut !== 'resolu') {
            $updateData['date_resolution'] = now();
        }

        $ticket->update($updateData);

        return back()->with('success', 'Ticket mis à jour avec succès.');
    }

    /**
     * Assigner un ticket à un technicien
     */
    public function assignTicket(Request $request, TicketIncident $ticket)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble || $ticket->immeuble_id !== $immeuble->id) {
            abort(403);
        }

        $validated = $request->validate([
            'assignee_id' => 'required|exists:users,id'
        ]);

        // Vérifier que le technicien appartient à l'immeuble
        $technicien = Technicien::where('user_id', $validated['assignee_id'])
            ->where('immeuble_id', $immeuble->id)
            ->first();

        if (!$technicien) {
            return back()->with('error', 'Technicien non valide pour cet immeuble.');
        }

        $ticket->update([
            'assignee_id' => $validated['assignee_id'],
            'statut' => 'en_cours'
        ]);

        return back()->with('success', 'Ticket assigné avec succès.');
    }

    /**
     * Liste des paiements
     */
    public function paiements()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $paiements = Paiement::where('immeuble_id', $immeuble->id)
            ->with(['user', 'appartement.bloc'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('syndic.paiements', compact('immeuble', 'paiements'));
    }

    /**
     * Liste des dépenses
     */
    public function depenses()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $depenses = Depense::where('immeuble_id', $immeuble->id)
            ->orderBy('date_depense', 'desc')
            ->paginate(15);
            
        return view('syndic.depenses', compact('immeuble', 'depenses'));
    }

    /**
     * Formulaire de création de dépense
     */
    public function createDepense()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        return view('syndic.depense-create', compact('immeuble'));
    }

    /**
     * Enregistrer une nouvelle dépense
     */
    public function storeDepense(Request $request)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }

        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'type_depense' => 'required|string',
            'date_depense' => 'required|date',
            'fournisseur' => 'nullable|string|max:255',
            'facture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $depenseData = array_merge($validated, [
            'immeuble_id' => $immeuble->id,
            'created_by' => $user->id
        ]);

        // Gérer l'upload de la facture
        if ($request->hasFile('facture')) {
            $facturePath = $request->file('facture')->store('depenses/factures', 'public');
            $depenseData['facture_path'] = $facturePath;
        }

        $depense = Depense::create($depenseData);

        return redirect()->route('syndic.depenses.index')
            ->with('success', 'Dépense enregistrée avec succès.');
    }

    /**
     * Liste des techniciens
     */
    public function techniciens()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $techniciens = Technicien::where('immeuble_id', $immeuble->id)
            ->with(['user', 'specialites'])
            ->paginate(15);
            
        return view('syndic.techniciens', compact('immeuble', 'techniciens'));
    }

    /**
     * Activer/désactiver un technicien
     */
    public function toggleTechnicien(Technicien $technicien)
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        // Vérifier que le technicien appartient à l'immeuble du syndic
        if (!$immeuble || $technicien->immeuble_id !== $immeuble->id) {
            abort(403);
        }

        $technicien->update([
            'is_suspended' => !$technicien->is_suspended
        ]);

        $status = $technicien->is_suspended ? 'suspendu' : 'activé';
        
        return back()->with('success', "Technicien {$status} avec succès.");
    }

    /**
     * Rapports et statistiques
     */
    public function rapports()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        // Statistiques pour les rapports
        $stats = [
            'tickets_par_mois' => $this->getTicketsParMois($immeuble->id),
            'depenses_par_mois' => $this->getDepensesParMois($immeuble->id),
            'paiements_par_mois' => $this->getPaiementsParMois($immeuble->id),
            'tickets_par_type' => $this->getTicketsParType($immeuble->id),
            'tickets_par_statut' => $this->getTicketsParStatut($immeuble->id)
        ];
        
        return view('syndic.rapports', compact('immeuble', 'stats'));
    }

    /**
     * Méthodes privées pour les statistiques
     */
    private function getTicketsParMois($immeubleId)
    {
        return TicketIncident::where('immeuble_id', $immeubleId)
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, COUNT(*) as total')
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->limit(12)
            ->get();
    }

    private function getDepensesParMois($immeubleId)
    {
        return Depense::where('immeuble_id', $immeubleId)
            ->selectRaw('MONTH(date_depense) as mois, YEAR(date_depense) as annee, SUM(montant) as total')
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->limit(12)
            ->get();
    }

    private function getPaiementsParMois($immeubleId)
    {
        return Paiement::where('immeuble_id', $immeubleId)
            ->selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, SUM(montant) as total')
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'desc')
            ->orderBy('mois', 'desc')
            ->limit(12)
            ->get();
    }

    private function getTicketsParType($immeubleId)
    {
        return TicketIncident::where('immeuble_id', $immeubleId)
            ->selectRaw('type_incident, COUNT(*) as total')
            ->groupBy('type_incident')
            ->get();
    }

    private function getTicketsParStatut($immeubleId)
    {
        return TicketIncident::where('immeuble_id', $immeubleId)
            ->selectRaw('statut, COUNT(*) as total')
            ->groupBy('statut')
            ->get();
    }

    /**
     * Gestion des propriétaires (sous-menu résidents)
     */
    public function proprietaires()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $proprietaires = Proprietaire::whereHas('appartements.bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with(['user', 'appartements.bloc'])
        ->paginate(15);
        
        return view('syndic.proprietaires', compact('immeuble', 'proprietaires'));
    }

    /**
     * Gestion des locataires (sous-menu résidents)
     */
    public function locataires()
    {
        $user = Auth::user();
        $syndicat = $user->syndicat;
        
        if (!$syndicat) {
            return redirect()->route('syndic.dashboard')->with('error', 'Profil syndic non trouvé.');
        }
        
        $immeuble = Immeuble::where('syndic_id', $syndicat->id)->first();
        
        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')->with('info', 'Aucun immeuble assigné.');
        }
        
        $locataires = Locataire::whereHas('appartement.bloc', function($query) use ($immeuble) {
            $query->where('immeuble_id', $immeuble->id);
        })->with(['user', 'appartement.bloc', 'proprietaire.user'])
        ->paginate(15);
        
        return view('syndic.locataires', compact('immeuble', 'locataires'));
    }
}