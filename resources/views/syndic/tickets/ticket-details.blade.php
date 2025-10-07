@extends('layouts.app')

@section('title', 'Détails du ticket #' . $ticket->numero_ticket)

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
                            <h1 class="page-title">Ticket #{{ $ticket->numero_ticket }}</h1>
                            <p class="page-subtitle">{{ $ticket->titre }}</p>
                        </div>
                    </div>
                    <a href="{{ route('syndic.tickets.index') }}" class="btn btn-outline-simple">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Badges de statut élégants -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="elegant-status-bar">
                <div class="status-chip">
                    <div class="chip-header">Statut</div>
                    @switch($ticket->statut)
                        @case('ouvert')
                            <div class="chip-badge status-open">
                                <span class="status-dot"></span>
                                <span>Ouvert</span>
                            </div>
                            @break
                        @case('en_cours')
                            <div class="chip-badge status-progress">
                                <span class="status-dot"></span>
                                <span>En cours</span>
                            </div>
                            @break
                        @case('resolu')
                            <div class="chip-badge status-resolved">
                                <span class="status-dot"></span>
                                <span>Résolu</span>
                            </div>
                            @break
                        @case('ferme')
                            <div class="chip-badge status-closed">
                                <span class="status-dot"></span>
                                <span>Fermé</span>
                            </div>
                            @break
                    @endswitch
                </div>
                
                <div class="status-chip">
                    <div class="chip-header">Priorité</div>
                    @switch($ticket->priorite)
                        @case('faible')
                            <div class="chip-badge priority-low">
                                <span class="priority-icon">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                                <span>Faible</span>
                            </div>
                            @break
                        @case('normale')
                            <div class="chip-badge priority-medium">
                                <span class="priority-icon">
                                    <i class="fas fa-minus"></i>
                                </span>
                                <span>Normale</span>
                            </div>
                            @break
                        @case('haute')
                            <div class="chip-badge priority-high">
                                <span class="priority-icon">
                                    <i class="fas fa-arrow-up"></i>
                                </span>
                                <span>Haute</span>
                            </div>
                            @break
                        @case('urgente')
                            <div class="chip-badge priority-urgent">
                                <span class="priority-icon">
                                    <i class="fas fa-exclamation"></i>
                                </span>
                                <span>Urgente</span>
                            </div>
                            @break
                    @endswitch
                </div>

                <div class="status-chip">
                    <div class="chip-header">Assignation</div>
                    @if($ticket->assignedTo)
                        <div class="chip-badge assignment-assigned">
                            <span class="user-avatar">
                                {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                            </span>
                            <span>{{ $ticket->assignedTo->name }}</span>
                        </div>
                    @else
                        <div class="chip-badge assignment-unassigned">
                            <span class="assignment-icon">
                                <i class="fas fa-user-slash"></i>
                            </span>
                            <span>Non assigné</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h3 class="info-title">Informations du ticket</h3>
                        <p class="info-subtitle">Détails et caractéristiques</p>
                    </div>
                </div>
                
                <div class="info-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <span>Appartement</span>
                                </label>
                                <div class="detail-value">Bloc {{ $ticket->appartement->bloc->nom }} - Appt {{ $ticket->appartement->numero }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <span>Type d'incident</span>
                                </label>
                                <div class="detail-value">{{ ucfirst($ticket->type_incident) }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <span>Date de création</span>
                                </label>
                                <div class="detail-value">{{ $ticket->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span>Créé par</span>
                                </label>
                                <div class="detail-value">{{ $ticket->createdBy->name }}</div>
                            </div>
                        </div>

                        @if($ticket->cout_estime)
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <span>Coût estimé</span>
                                </label>
                                <div class="detail-value-with-unit">
                                    <span class="value">{{ number_format($ticket->cout_estime, 2) }}</span>
                                    <span class="unit">DT</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($ticket->cout_reel)
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">
                                    <div class="label-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <span>Coût réel</span>
                                </label>
                                <div class="detail-value-with-unit">
                                    <span class="value">{{ number_format($ticket->cout_reel, 2) }}</span>
                                    <span class="unit">DT</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mt-4">
                        <label class="detail-label">
                            <div class="label-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <span>Description</span>
                        </label>
                        <div class="detail-value">{{ $ticket->description }}</div>
                    </div>

                    <!-- Photos -->
                    @if($ticket->photos && count($ticket->photos) > 0)
                    <div class="mt-4">
                        <label class="detail-label">
                            <div class="label-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <span>Photos</span>
                        </label>
                        <div class="photos-grid">
                            @foreach($ticket->photos as $photo)
                            <div class="photo-item">
                                <img src="{{ Storage::url($photo) }}" alt="Photo du ticket">
                                <div class="photo-overlay">
                                    <a href="{{ Storage::url($photo) }}" target="_blank" class="photo-btn">
                                        <i class="fas fa-expand"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Notes de résolution -->
                    @if($ticket->notes_resolution)
                    <div class="mt-4">
                        <label class="detail-label">
                            <div class="label-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span>Notes de résolution</span>
                        </label>
                        <div class="detail-value notes-resolution">
                            {{ $ticket->notes_resolution }}
                            @if($ticket->date_resolution)
                            <div class="resolution-date">
                                Résolu le {{ \Carbon\Carbon::parse($ticket->date_resolution)->format('d/m/Y à H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Satisfaction client - BIEN PLACÉ ICI DANS LA COLONNE PRINCIPALE -->
                    @if($ticket->statut === 'resolu' || $ticket->statut === 'ferme')
                    <div class="mt-4">
                        <label class="detail-label">
                            <div class="label-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <span>Satisfaction client</span>
                        </label>
                        @if($ticket->satisfaction_client)
                            <div class="detail-value satisfaction-rating">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $ticket->satisfaction_client ? 'star-filled' : 'star-empty' }}"></i>
                                    @endfor
                                </div>
                                <div class="rating-text">{{ $ticket->satisfaction_client }}/5</div>
                            </div>
                        @else
                            <div class="detail-value no-rating">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune évaluation pour le moment
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="actions-card">
                <div class="actions-header">
                    <h6 class="actions-title">
                        <i class="fas fa-cogs"></i>
                        Actions
                    </h6>
                </div>
                <div class="actions-body">
                    <form method="POST" action="{{ route('syndic.tickets.status', $ticket) }}" class="modern-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-field">
                            <label class="field-label">
                                <i class="fas fa-flag"></i>
                                Changer le statut
                            </label>
                            <div class="select-wrapper">
                                <select name="statut" class="modern-select" id="statutSelect">
                                    <option value="ouvert" {{ $ticket->statut == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                                    <option value="en_cours" {{ $ticket->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="resolu" {{ $ticket->statut == 'resolu' ? 'selected' : '' }}>Résolu</option>
                                    <option value="ferme" {{ $ticket->statut == 'ferme' ? 'selected' : '' }}>Fermé</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="field-label">
                                <i class="fas fa-euro-sign"></i>
                                Coût réel (DT)
                            </label>
                            <div class="input-wrapper">
                                <input type="number" step="0.01" name="cout_reel" class="modern-input" 
                                       value="{{ $ticket->cout_reel }}" placeholder="0.00">
                                <span class="input-suffix">DT</span>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="field-label" id="notesLabel">
                                <i class="fas fa-sticky-note"></i>
                                Notes de résolution
                            </label>
                            <textarea name="notes_resolution" class="modern-textarea" rows="4" 
                                      placeholder="Ajouter des détails sur la résolution...">{{ $ticket->notes_resolution }}</textarea>
                        </div>

                        <!-- Champ Satisfaction Client -->
                        <div class="form-field" id="satisfactionField" style="{{ ($ticket->statut === 'resolu' || $ticket->statut === 'ferme') ? '' : 'display: none;' }}">
                            <label class="field-label">
                                <i class="fas fa-star"></i>
                                Satisfaction client
                            </label>
                            <div class="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="satisfaction_client" value="{{ $i }}" 
                                           id="rating{{ $i }}" 
                                           {{ $ticket->satisfaction_client == $i ? 'checked' : '' }}
                                           class="rating-radio">
                                    <label for="rating{{ $i }}" class="rating-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <small class="form-text text-muted">Évaluation de 1 à 5 étoiles</small>
                        </div>

                        <button type="submit" class="modern-btn primary">
                            <i class="fas fa-save"></i>
                            <span>Mettre à jour</span>
                        </button>
                    </form>

                    @if($techniciens->count() > 0)
                    <div class="divider"></div>
                    <form method="POST" action="{{ route('syndic.tickets.assign', $ticket) }}" class="modern-form">
                        @csrf
                        <div class="form-field">
                            <label class="field-label">
                                <i class="fas fa-user-cog"></i>
                                Assigner un technicien
                            </label>
                            <div class="select-wrapper">
                                <select name="assignee_id" class="modern-select" required>
                                    <option value="">Choisir un technicien</option>
                                    @foreach($techniciens as $technicien)
                                    <option value="{{ $technicien->user->id }}" 
                                            {{ $ticket->assignee_id == $technicien->user->id ? 'selected' : '' }}>
                                        {{ $technicien->user->name }}
                                        @if($technicien->specialites)
                                            - {{ $technicien->specialites }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                        <button type="submit" class="modern-btn secondary">
                            <i class="fas fa-user-plus"></i>
                            <span>Assigner le ticket</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="clean-section">
                <h6 class="clean-title">Informations complémentaires</h6>
                <div class="clean-content">
                    <div class="info-row"><span class="label">Numéro</span> {{ $ticket->numero_ticket }}</div>
                    <div class="info-row"><span class="label">Créé le</span> {{ $ticket->created_at->format('d/m/Y à H:i') }}</div>
                    <div class="info-row"><span class="label">Modifié le</span> {{ $ticket->updated_at->format('d/m/Y à H:i') }}</div>
                    @if($ticket->date_resolution)
                    <div class="info-row"><span class="label">Résolu le</span> {{ \Carbon\Carbon::parse($ticket->date_resolution)->format('d/m/Y à H:i') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

body {
    background-color: var(--accent-light);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

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

.btn-outline-simple {
    background: white;
    border: 2px solid var(--primary-light);
    color: var(--primary);
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-outline-simple:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(23, 59, 97, 0.3);
}

.elegant-status-bar {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid var(--gray-200);
}

.status-chip {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    min-width: 160px;
}

.chip-header {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

.chip-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1.5px solid;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.priority-icon {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    color: white;
}

.user-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    color: white;
}

.assignment-icon {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    color: white;
}

.status-open { background: #eff6ff; color: #1d4ed8; border-color: #3b82f6; }
.status-open .status-dot { background: #3b82f6; }

.status-progress { background: #fefce8; color: #a16207; border-color: #eab308; }
.status-progress .status-dot { background: #eab308; }

.status-resolved { background: #ecfdf5; color: #065f46; border-color: #10b981; }
.status-resolved .status-dot { background: #10b981; }

.status-closed { background: #f8fafc; color: #475569; border-color: #64748b; }
.status-closed .status-dot { background: #64748b; }

.priority-low { background: #ecfdf5; color: #065f46; border-color: #10b981; }
.priority-low .priority-icon { background: #10b981; }

.priority-medium { background: #fefce8; color: #a16207; border-color: #eab308; }
.priority-medium .priority-icon { background: #eab308; }

.priority-high { background: #fff7ed; color: #c2410c; border-color: #ea580c; }
.priority-high .priority-icon { background: #ea580c; }

.priority-urgent { background: #fef2f2; color: #991b1b; border-color: #dc2626; }
.priority-urgent .priority-icon { background: #dc2626; }

.assignment-assigned { background: var(--accent-light); color: var(--primary); border-color: var(--accent); }
.assignment-assigned .user-avatar { background: var(--accent); }

.assignment-unassigned { background: #f8fafc; color: #64748b; border-color: #94a3b8; }
.assignment-unassigned .assignment-icon { background: #94a3b8; }

.actions-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 1px solid var(--gray-200);
    overflow: hidden;
    margin-bottom: 2rem;
}

.actions-header {
    background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--gray-200);
}

.actions-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.actions-body {
    padding: 2rem;
}

.modern-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.field-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.field-label i {
    color: var(--accent);
    width: 14px;
}

.select-wrapper {
    position: relative;
}

.modern-select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    border: 2px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    color: var(--primary);
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
}

.modern-select:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
}

.select-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.8rem;
    pointer-events: none;
}

.input-wrapper {
    position: relative;
}

.modern-input {
    width: 100%;
    padding: 12px 50px 12px 16px;
    border: 2px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    color: var(--primary);
    transition: all 0.2s ease;
}

.modern-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
}

.input-suffix {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
    font-size: 0.85rem;
    font-weight: 600;
}

.modern-textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    color: var(--primary);
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
    transition: all 0.2s ease;
}

.modern-textarea:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
}

.modern-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 14px 24px;
    border: none;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-btn.primary {
    background: linear-gradient(135deg, var(--accent), #e07706);
    color: white;
    box-shadow: 0 4px 12px rgba(253, 137, 22, 0.3);
}

.modern-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(253, 137, 22, 0.4);
}

.modern-btn.secondary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: 0 4px 12px rgba(23, 59, 97, 0.3);
}

.modern-btn.secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(23, 59, 97, 0.4);
}

.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gray-300), transparent);
    margin: 2rem 0;
}

.info-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    overflow: hidden;
    position: relative;
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
}

.info-header {
    display: flex;
    align-items: center;
    padding: 2rem 2rem 1.5rem 2rem;
    border-bottom: 2px solid #f1f5f9;
}

.info-icon {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
}

.info-icon i {
    font-size: 22px;
    color: white;
}

.info-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 0.25rem 0;
}

.info-subtitle {
    color: var(--primary-light);
    margin: 0;
    font-size: 0.9rem;
    font-weight: 500;
}

.info-body {
    padding: 2rem;
}

.detail-group {
    margin-bottom: 1.5rem;
}

.detail-label {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: var(--primary);
    font-size: 0.95rem;
}

.label-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    border: 1px solid rgba(253, 137, 22, 0.2);
}

.label-icon i {
    font-size: 0.9rem;
    color: var(--accent);
}

.detail-value {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.detail-value-with-unit {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.detail-value-with-unit .value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary);
}

.detail-value-with-unit .unit {
    color: var(--primary-light);
    font-weight: 600;
    font-size: 0.9rem;
    background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid rgba(253, 137, 22, 0.2);
}

.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.photo-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.photo-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.photo-item:hover .photo-overlay {
    opacity: 1;
}

.photo-btn {
    color: white;
    font-size: 1.2rem;
    text-decoration: none;
}

.notes-resolution {
    background: #f0f9ff !important;
    border-color: #0ea5e9 !important;
}

.resolution-date {
    font-size: 0.85rem;
    color: var(--primary-light);
    margin-top: 0.5rem;
    font-weight: 500;
}

/* Satisfaction client */
.satisfaction-rating {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
}

.star-filled {
    color: #fbbf24;
    font-size: 1.2rem;
}

.star-empty {
    color: #d1d5db;
    font-size: 1.2rem;
}

.rating-text {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary);
}

.no-rating {
    background: #fef3c7 !important;
    border-color: #fbbf24 !important;
    color: #92400e;
    font-style: italic;
}

/* Input rating */
.rating-input {
    display: flex;
    gap: 0.5rem;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-radio {
    display: none;
}

.rating-label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #d1d5db;
    transition: all 0.2s ease;
}

.rating-label:hover,
.rating-label:hover ~ .rating-label,
.rating-radio:checked ~ .rating-label {
    color: #fbbf24;
}

.rating-label:hover {
    transform: scale(1.2);
}

.clean-section {
    background: #fafbfc;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    border-left: 3px solid var(--accent);
}

.clean-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--primary);
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-row {
    display: flex;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.info-row:last-child {
    margin-bottom: 0;
}

.info-row .label {
    min-width: 80px;
    font-weight: 600;
    color: var(--primary-light);
    margin-right: 1rem;
}

.d-flex { display: flex !important; }
.align-items-center { align-items: center !important; }
.justify-content-between { justify-content: space-between !important; }
.ms-3 { margin-left: 1rem !important; }
.me-2 { margin-right: 0.5rem !important; }
.mb-4 { margin-bottom: 1.5rem !important; }
.mt-4 { margin-top: 1.5rem !important; }
.g-4 > * { padding: 1rem !important; }
.text-muted { color: #6b7280 !important; }

@media (max-width: 768px) {
    .header-card {
        padding: 1.5rem;
    }
    .header-card .d-flex {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    .info-header {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    .info-icon {
        margin: 0 auto 1rem auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statutSelect = document.getElementById('statutSelect');
    const notesTextarea = document.querySelector('textarea[name="notes_resolution"]');
    const satisfactionField = document.getElementById('satisfactionField');
    const notesLabel = document.getElementById('notesLabel');
    
    if (statutSelect) {
        statutSelect.addEventListener('change', function() {
            // Afficher/masquer le champ satisfaction
            if (this.value === 'resolu' || this.value === 'ferme') {
                satisfactionField.style.display = 'flex';
            } else {
                satisfactionField.style.display = 'none';
            }
            
            // Rendre les notes obligatoires si résolu
            if (this.value === 'resolu') {
                notesTextarea.required = true;
                notesLabel.innerHTML = '<i class="fas fa-sticky-note"></i> Notes de résolution <span style="color: #ef4444;">*</span>';
            } else {
                notesTextarea.required = false;
                notesLabel.innerHTML = '<i class="fas fa-sticky-note"></i> Notes de résolution';
            }
        });
    }
});
</script>
@endsection