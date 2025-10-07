@extends('layouts.app')

@section('title', 'Gestion des Tickets')

@section('content')
<div class="container-fluid px-4">
    <!-- Header sobre -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="header-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="ms-3">
                            <h1 class="page-title">Gestion des Tickets</h1>
                            <p class="page-subtitle">Suivi et gestion des demandes de maintenance</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('syndic.tickets.create') }}" class="btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="filters-card">
                <div class="filters-header">
                    <i class="fas fa-filter"></i>
                    Filtres
                </div>
                <form method="GET" action="{{ route('syndic.tickets.index') }}" class="filters-form" id="filtersForm">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label class="filter-label">Statut</label>
                            <select name="statut" class="filter-select" onchange="document.getElementById('filtersForm').submit();">
                                <option value="">Tous les statuts</option>
                                <option value="ouvert" {{ request('statut') == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="resolu" {{ request('statut') == 'resolu' ? 'selected' : '' }}>Résolu</option>
                                <option value="ferme" {{ request('statut') == 'ferme' ? 'selected' : '' }}>Fermé</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Priorité</label>
                            <select name="priorite" class="filter-select" onchange="document.getElementById('filtersForm').submit();">
    <option value="">Toutes les priorités</option>
    <option value="faible" {{ request('priorite') == 'faible' ? 'selected' : '' }}>Faible</option>
    <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
    <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
    <option value="urgente" {{ request('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
</select>
                            
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Type d'incident</label>
                            <select name="type_incident" class="filter-select" onchange="document.getElementById('filtersForm').submit();">
                                <option value="">Tous les types</option>
                                <option value="plomberie" {{ request('type_incident') == 'plomberie' ? 'selected' : '' }}>Plomberie</option>
                                <option value="electricite" {{ request('type_incident') == 'electricite' ? 'selected' : '' }}>Électricité</option>
                                <option value="chauffage" {{ request('type_incident') == 'chauffage' ? 'selected' : '' }}>Chauffage</option>
                                <option value="climatisation" {{ request('type_incident') == 'climatisation' ? 'selected' : '' }}>Climatisation</option>
                                <option value="autre" {{ request('type_incident') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Assignation</label>
                            <select name="assignation" class="filter-select" onchange="document.getElementById('filtersForm').submit();">
                                <option value="">Tous</option>
                                <option value="assigned" {{ request('assignation') == 'assigned' ? 'selected' : '' }}>Assignés</option>
                                <option value="unassigned" {{ request('assignation') == 'unassigned' ? 'selected' : '' }}>Non assignés</option>
                            </select>
                        </div>
                    </div>
                    <div class="filters-actions">
                        <button type="submit" class="filter-btn primary">
                            <i class="fas fa-search"></i>
                            Filtrer
                        </button>
                        <a href="{{ route('syndic.tickets.index') }}" class="filter-btn secondary">
                            <i class="fas fa-undo"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stats-grid">
                <div class="stat-card stat-open">
                    <div class="stat-number" data-target="{{ \App\Models\TicketIncident::where('statut', 'ouvert')->count() }}">0</div>
                    <div class="stat-label">Ouverts</div>
                </div>
                <div class="stat-card stat-progress">
                    <div class="stat-number" data-target="{{ \App\Models\TicketIncident::where('statut', 'en_cours')->count() }}">0</div>
                    <div class="stat-label">En cours</div>
                </div>
                <div class="stat-card stat-resolved">
                    <div class="stat-number" data-target="{{ \App\Models\TicketIncident::where('statut', 'resolu')->count() }}">0</div>
                    <div class="stat-label">Résolus</div>
                </div>
                <div class="stat-card stat-urgent">
                    <div class="stat-number" data-target="{{ \App\Models\TicketIncident::where('priorite', 'urgente')->count() }}">0</div>
                    <div class="stat-label">Urgents</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des tickets -->
    <div class="row">
        <div class="col-12">
            <div class="tickets-table-container">
                <div class="table-header">
                    <div class="table-title">
                        <i class="fas fa-list"></i>
                        Liste des Tickets
                    </div>
                    <div class="results-count">
                        {{ $tickets->total() }} ticket{{ $tickets->total() > 1 ? 's' : '' }} trouvé{{ $tickets->total() > 1 ? 's' : '' }}
                    </div>
                </div>

                @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="tickets-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Appartement</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Assigné à</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr>
                                <td>
                                    <div class="ticket-info">
                                        <div class="ticket-number">#{{ $ticket->numero_ticket }}</div>
                                        <div class="ticket-title">{{ $ticket->titre }}</div>
                                        <div class="ticket-description">{{ Str::limit($ticket->description, 80) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="apartment-info">
                                        Bloc {{ $ticket->appartement->bloc->nom }} - Appt {{ $ticket->appartement->numero }}
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge">{{ ucfirst($ticket->type_incident) }}</span>
                                </td>
                                <td>
                                    @switch($ticket->statut)
                                        @case('ouvert')
                                            <div class="status-badge status-open">
                                                <span class="status-dot"></span>
                                                <span>Ouvert</span>
                                            </div>
                                            @break
                                        @case('en_cours')
                                            <div class="status-badge status-progress">
                                                <span class="status-dot"></span>
                                                <span>En cours</span>
                                            </div>
                                            @break
                                        @case('resolu')
                                            <div class="status-badge status-resolved">
                                                <span class="status-dot"></span>
                                                <span>Résolu</span>
                                            </div>
                                            @break
                                        @case('ferme')
                                            <div class="status-badge status-closed">
                                                <span class="status-dot"></span>
                                                <span>Fermé</span>
                                            </div>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @switch($ticket->priorite)
    @case('faible')
        <div class="priority-badge priority-low">
            <span class="priority-icon">
                <i class="fas fa-arrow-down"></i>
            </span>
            <span>Faible</span>
        </div>
        @break
    @case('normale')
        <div class="priority-badge priority-medium">
            <span class="priority-icon">
                <i class="fas fa-minus"></i>
            </span>
            <span>Normale</span>
        </div>
        @break
    @case('haute')
        <div class="priority-badge priority-high">
            <span class="priority-icon">
                <i class="fas fa-arrow-up"></i>
            </span>
            <span>Haute</span>
        </div>
        @break
    @case('urgente')
        <div class="priority-badge priority-urgent">
            <span class="priority-icon">
                <i class="fas fa-exclamation"></i>
            </span>
            <span>Urgente</span>
        </div>
        @break
@endswitch
                                </td>
                                <td>
                                    @if($ticket->assignedTo)
                                        <div class="assignee-info">
                                            <div class="assignee-avatar">
                                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                                            </div>
                                            <div class="assignee-name">{{ $ticket->assignedTo->name }}</div>
                                        </div>
                                    @else
                                        <div class="unassigned">Non assigné</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">{{ $ticket->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="ticket-actions">
                                        <a href="{{ route('syndic.tickets.show', $ticket) }}" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('syndic.tickets.show', $ticket) }}" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="empty-title">Aucun ticket trouvé</div>
                    <div class="empty-text">
                        @if(request()->hasAny(['statut', 'priorite', 'type_incident', 'assignation']))
                            Essayez de modifier vos critères de recherche
                        @else
                            Aucun ticket n'a encore été créé
                        @endif
                    </div>
                    @if(!request()->hasAny(['statut', 'priorite', 'type_incident', 'assignation']))
                    <a href="{{ route('syndic.tickets.create') }}" class="btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer le premier ticket
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="pagination-container">
                {{ $tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* Variables de couleurs exactes de votre interface */
:root {
    --primary: #173B61;
    --primary-dark: #17616E;
    --primary-light: #7697A0;
    --accent: #FD8916;
    --accent-light: #FFEBD0;
    --white: #ffffff;
    --gray-50: #f8f9fa;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

/* Base - même arrière-plan que votre interface */
body {
    background-color: var(--accent-light);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header identique à vos interfaces */
.header-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    position: relative;
    overflow: hidden;
}

.header-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
}

.header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
}

.page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--primary-light);
    margin: 0;
    font-size: 1rem;
    font-weight: 500;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent), #e07706);
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(253, 137, 22, 0.4);
    color: white;
    text-decoration: none;
}

/* Filtres */
.filters-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-200);
}

.filters-header {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary);
}

.filter-select {
    padding: 10px 12px;
    border: 2px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    color: var(--primary);
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
}

.filters-actions {
    display: flex;
    gap: 1rem;
}

.filter-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.filter-btn.primary {
    background: var(--accent);
    color: white;
}

.filter-btn.primary:hover {
    background: #e07706;
    color: white;
}

.filter-btn.secondary {
    background: var(--gray-100);
    color: var(--primary);
    border: 1px solid var(--gray-300);
}

.filter-btn.secondary:hover {
    background: var(--gray-200);
    color: var(--primary);
    text-decoration: none;
}

/* Statistiques */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-600);
}

.stat-open .stat-number { color: #3b82f6; }
.stat-progress .stat-number { color: #eab308; }
.stat-resolved .stat-number { color: #10b981; }
.stat-urgent .stat-number { color: #dc2626; }

/* Table moderne */
.tickets-table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.table-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.results-count {
    color: var(--primary-light);
    font-size: 0.9rem;
    font-weight: 500;
}

.table-responsive {
    overflow-x: auto;
}

.tickets-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.tickets-table th {
    background: var(--gray-50);
    padding: 1rem 1.5rem;
    text-align: left;
    font-weight: 600;
    color: var(--primary);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--gray-200);
    white-space: nowrap;
}

.tickets-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: middle;
}

.tickets-table tbody tr {
    transition: all 0.3s ease;
    position: relative;
}

.tickets-table tbody tr:hover {
    background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(253, 137, 22, 0.2);
    border-left: 4px solid var(--accent);
}

.tickets-table tbody tr:hover .ticket-number {
    color: var(--accent);
    font-weight: 800;
}

.tickets-table tbody tr:hover .action-btn {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.tickets-table tbody tr:hover .status-badge,
.tickets-table tbody tr:hover .priority-badge {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.tickets-table tbody tr:hover .assignee-avatar {
    transform: scale(1.2) rotate(5deg);
    box-shadow: 0 2px 8px rgba(253, 137, 22, 0.3);
}

/* Animation d'entrée pour les lignes de la table */
.tickets-table tbody tr {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
}

.tickets-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.tickets-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
.tickets-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
.tickets-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
.tickets-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
.tickets-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
.tickets-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
.tickets-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
.tickets-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
.tickets-table tbody tr:nth-child(10) { animation-delay: 1.0s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation pour les statistiques */
@keyframes countUp {
    from {
        opacity: 0;
        transform: scale(0.5);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.stat-number {
    animation: countUp 0.8s ease-out;
}

/* Animation de pulsation pour les badges urgents */
@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.priority-urgent {
    animation: pulse 2s infinite;
}

/* Effet de brillance au survol des cartes de stats */
.stat-card {
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
    opacity: 0;
}

.stat-card:hover::before {
    animation: shine 0.6s ease-in-out;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
        opacity: 0;
    }
}

/* Animation pour les filtres actifs */
.filter-select:focus {
    animation: filterFocus 0.3s ease;
}

@keyframes filterFocus {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
    100% {
        transform: scale(1);
    }
}

/* Badges de statut dans la table */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1.5px solid;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-open { 
    background: #eff6ff; 
    color: #1d4ed8; 
    border-color: #3b82f6; 
}
.status-open .status-dot { background: #3b82f6; }

.status-progress { 
    background: #fefce8; 
    color: #a16207; 
    border-color: #eab308; 
}
.status-progress .status-dot { background: #eab308; }

.status-resolved { 
    background: #ecfdf5; 
    color: #065f46; 
    border-color: #10b981; 
}
.status-resolved .status-dot { background: #10b981; }

.status-closed { 
    background: #f8fafc; 
    color: #475569; 
    border-color: #64748b; 
}
.status-closed .status-dot { background: #64748b; }

/* Badges de priorité */
.priority-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1.5px solid;
    white-space: nowrap;
}

.priority-icon {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    color: white;
    flex-shrink: 0;
}

.priority-low { 
    background: #ecfdf5; 
    color: #065f46; 
    border-color: #10b981; 
}
.priority-low .priority-icon { background: #10b981; }

.priority-medium { 
    background: #fefce8; 
    color: #a16207; 
    border-color: #eab308; 
}
.priority-medium .priority-icon { background: #eab308; }

.priority-high { 
    background: #fff7ed; 
    color: #c2410c; 
    border-color: #ea580c; 
}
.priority-high .priority-icon { background: #ea580c; }

.priority-urgent { 
    background: #fef2f2; 
    color: #991b1b; 
    border-color: #dc2626; 
}
.priority-urgent .priority-icon { background: #dc2626; }

/* Informations du ticket */
.ticket-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    max-width: 300px;
}

.ticket-number {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.9rem;
}

.ticket-title {
    color: var(--primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}

.ticket-description {
    color: var(--gray-600);
    font-size: 0.85rem;
    line-height: 1.4;
}

.apartment-info {
    color: var(--primary-light);
    font-size: 0.85rem;
    font-weight: 500;
    white-space: nowrap;
}

.type-badge {
    background: var(--accent-light);
    color: var(--accent);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
}

.assignee-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.assignee-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--accent);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    flex-shrink: 0;
}

.assignee-name {
    color: var(--primary);
    font-weight: 500;
    font-size: 0.85rem;
}

.unassigned {
    color: var(--gray-500);
    font-style: italic;
    font-size: 0.85rem;
}

.date-info {
    color: var(--gray-600);
    font-size: 0.85rem;
    white-space: nowrap;
}

/* Actions */
.ticket-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid;
}

.action-btn.view {
    background: var(--accent-light);
    color: var(--accent);
    border-color: var(--accent);
}

.action-btn.view:hover {
    background: var(--accent);
    color: white;
    text-decoration: none;
}

.action-btn.edit {
    background: #eff6ff;
    color: #3b82f6;
    border-color: #3b82f6;
}

.action-btn.edit:hover {
    background: #3b82f6;
    color: white;
    text-decoration: none;
}

/* Pagination */
.pagination-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: center;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-500);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: var(--gray-400);
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.empty-text {
    font-size: 1rem;
    margin-bottom: 2rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .header-card {
        padding: 1.5rem;
    }
    
    .header-card .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .filters-row {
        grid-template-columns: 1fr;
    }
    
    .filters-actions {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .table-header {
        padding: 1rem;
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .tickets-table th,
    .tickets-table td {
        padding: 0.75rem 0.5rem;
    }
    
    .ticket-info {
        max-width: 200px;
    }
    
    .ticket-title {
        font-size: 0.9rem;
    }
    
    .ticket-description {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-subtitle {
        font-size: 0.9rem;
    }
    
    .header-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
}

/* Bootstrap overrides pour maintenir le style */
.d-flex {
    display: flex !important;
}

.align-items-center {
    align-items: center !important;
}

.justify-content-between {
    justify-content: space-between !important;
}

.ms-3 {
    margin-left: 1rem !important;
}

.me-2 {
    margin-right: 0.5rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mt-4 {
    margin-top: 1.5rem !important;
}

/* Pagination personnalisée */
.pagination {
    justify-content: center;
}

.page-link {
    color: var(--primary);
    background-color: white;
    border: 2px solid var(--gray-300);
    border-radius: 6px !important;
    margin: 0 2px;
    padding: 8px 12px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.page-link:hover {
    color: white;
    background-color: var(--accent);
    border-color: var(--accent);
}

.page-item.active .page-link {
    color: white;
    background-color: var(--accent);
    border-color: var(--accent);
}

.page-item.disabled .page-link {
    color: var(--gray-400);
    background-color: var(--gray-100);
    border-color: var(--gray-300);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entrée pour les cartes
    const cards = document.querySelectorAll('.stat-card, .filters-card, .tickets-table-container');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation des compteurs de statistiques
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 1500; // 1.5 secondes
            const increment = target / (duration / 16); // 60 FPS
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current);
            }, 16);
        });
    }

    // Démarrer l'animation des compteurs après un délai
    setTimeout(animateCounters, 500);

    // Animation pour les nouveaux éléments de table (après filtrage)
    function animateTableRows() {
        const rows = document.querySelectorAll('.tickets-table tbody tr');
        rows.forEach((row, index) => {
            row.style.animation = 'none';
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s forwards`;
            }, 50);
        });
    }

    // Gestion du responsive pour la table
    function handleTableResponsive() {
        const table = document.querySelector('.tickets-table');
        const container = document.querySelector('.tickets-table-container');
        
        if (table && container) {
            if (window.innerWidth < 768) {
                container.style.overflowX = 'auto';
            } else {
                container.style.overflowX = 'hidden';
            }
        }
    }

    window.addEventListener('resize', handleTableResponsive);
    handleTableResponsive();

    // Animation de chargement pour les filtres
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Ajouter une classe de chargement
            const form = document.getElementById('filtersForm');
            form.style.opacity = '0.7';
            form.style.pointerEvents = 'none';
            
            // Ajouter un spinner temporaire
            const spinner = document.createElement('div');
            spinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
            spinner.style.cssText = `
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 1rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                color: var(--primary);
                font-weight: 600;
            `;
            
            const container = document.querySelector('.filters-card');
            container.style.position = 'relative';
            container.appendChild(spinner);
        });
    });

    // Effet de survol amélioré pour les badges
    const badges = document.querySelectorAll('.status-badge, .priority-badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.05)';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Animation pour les boutons d'action
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });

    // Effet de particules pour les cartes de statistiques
    function createParticleEffect(element) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--accent);
            border-radius: 50%;
            pointer-events: none;
            animation: particle 1s ease-out forwards;
        `;
        
        const rect = element.getBoundingClientRect();
        particle.style.left = Math.random() * rect.width + 'px';
        particle.style.top = Math.random() * rect.height + 'px';
        
        element.appendChild(particle);
        
        setTimeout(() => {
            if (particle.parentNode) {
                particle.parentNode.removeChild(particle);
            }
        }, 1000);
    }

    // Ajouter l'effet de particules aux cartes de stats au survol
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            for (let i = 0; i < 5; i++) {
                setTimeout(() => createParticleEffect(this), i * 100);
            }
        });
    });

    // Animation CSS pour les particules
    const style = document.createElement('style');
    style.textContent = `
        @keyframes particle {
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateY(-50px) scale(0);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection