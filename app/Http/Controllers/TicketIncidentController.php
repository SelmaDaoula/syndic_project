<?php

namespace App\Http\Controllers;

use App\Models\TicketIncident;
use App\Models\Appartement;
use App\Models\Technicien;
use App\Models\Immeuble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TicketIncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketIncident::with(['appartement.bloc', 'immeuble', 'assignedTo', 'createdBy']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('type_incident')) {
            $query->where('type_incident', $request->type_incident);
        }

        if ($request->filled('assignation')) {
            if ($request->assignation === 'assigned') {
                $query->whereNotNull('assignee_id');
            } elseif ($request->assignation === 'unassigned') {
                $query->whereNull('assignee_id');
            }
        }

        $tickets = $query->latest()->paginate(15);

        return view('syndic.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $appartements = Appartement::with('bloc')->get();
        $techniciens = Technicien::with('user')->get();

        $syndic = Auth::user()->syndic;
        $immeuble = $syndic ? $syndic->immeuble : Immeuble::first();

        if (!$immeuble) {
            return redirect()->route('syndic.dashboard')
                ->with('error', 'Aucun immeuble associé');
        }

        return view('syndic.tickets.ticket-create', compact('appartements', 'techniciens', 'immeuble'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appartement_id' => 'required|exists:appartements,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'type_incident' => 'required|in:plomberie,electricite,chauffage,climatisation,nettoyage,securite,ascenseur,ventilation,autre',
            'priorite' => 'required|in:faible,normale,haute,urgente',
            'cout_estime' => 'nullable|numeric|min:0',
            'assignee_id' => 'nullable|exists:users,id',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $appartement = Appartement::with('bloc')->findOrFail($request->appartement_id);

        $validated['statut'] = 'ouvert';
        $validated['created_by'] = Auth::id();
        $validated['date_incident'] = now();
        $validated['bloc_id'] = $appartement->bloc_id;

        $syndic = Auth::user()->syndic;

        if ($syndic && $syndic->immeuble_id) {
            $validated['immeuble_id'] = $syndic->immeuble_id;
        } else {
            if ($appartement->bloc && $appartement->bloc->immeuble_id) {
                $validated['immeuble_id'] = $appartement->bloc->immeuble_id;
            } else {
                $immeuble = Immeuble::first();
                if ($immeuble) {
                    $validated['immeuble_id'] = $immeuble->id;
                } else {
                    return back()->withErrors(['error' => 'Aucun immeuble trouvé dans le système'])->withInput();
                }
            }
        }

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('tickets', 'public');
                $photos[] = $path;
            }
            $validated['photos'] = $photos;
        }

        TicketIncident::create($validated);

        return redirect()->route('syndic.tickets.index')
            ->with('success', 'Ticket créé avec succès');
    }

    public function show(TicketIncident $ticket)
    {
        $ticket->load(['appartement.bloc', 'immeuble', 'assignedTo', 'createdBy']);
        $techniciens = Technicien::with('user')->get();

        return view('syndic.tickets.ticket-details', compact('ticket', 'techniciens'));
    }

    public function updateStatus(Request $request, TicketIncident $ticket)
    {
        $validated = $request->validate([
            'statut' => 'required|in:ouvert,en_cours,resolu,ferme',
            'cout_reel' => 'nullable|numeric|min:0',
            'notes_resolution' => 'nullable|string',
            'satisfaction_client' => 'nullable|integer|min:1|max:5',
        ]);

        if ($validated['statut'] === 'resolu' && $ticket->statut !== 'resolu') {
            $validated['date_resolution'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('syndic.tickets.show', $ticket)
            ->with('success', 'Statut mis à jour avec succès');
    }

    public function assign(Request $request, TicketIncident $ticket)
    {
        $validated = $request->validate([
            'assignee_id' => 'required|exists:users,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('syndic.tickets.show', $ticket)
            ->with('success', 'Technicien assigné avec succès');
    }
}