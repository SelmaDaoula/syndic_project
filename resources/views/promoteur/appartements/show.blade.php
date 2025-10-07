@extends('layouts.app')

@section('title', 'Détails du ticket #' . $ticket->numero_ticket)

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
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
                        <div class="header-actions">
                            <a href="{{ route('syndic.tickets.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Informations principales -->
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
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statut -->
                <div class="clean-section">
                    <h6 class="clean-title">Statut</h6>
                    @switch($ticket->statut)
                        @case('ouvert')
                            <span class="status-badge ouvert">Ouvert</span>
                            @break
                        @case('en_cours')
                            <span class="status-badge en-cours">En cours</span>
                            @break
                        @case('resolu')
                            <span class="status-badge resolu">Résolu</span>
                            @break
                        @case('ferme')
                            <span class="status-badge ferme">Fermé</span>
                            @break
                    @endswitch
                </div>

                <!-- Priorité -->
                <div class="clean-section">
                    <h6 class="clean-title">Priorité</h6>
                    @switch($ticket->priorite)
                        @case('basse')
                            <span class="status-badge basse">Basse</span>
                            @break
                        @case('moyenne')
                            <span class="status-badge moyenne">Moyenne</span>
                            @break
                        @case('haute')
                            <span class="status-badge haute">Haute</span>
                            @break
                        @case('urgente')
                            <span class="status-badge urgente">Urgente</span>
                            @break
                    @endswitch
                </div>

                <!-- Assignation -->
                <div class="clean-section">
                    <h6 class="clean-title">Assignation</h6>
                    <div class="clean-content">
                        @if($ticket->assignedTo)
                            <div class="info-row"><span class="label">Technicien</span> {{ $ticket->assignedTo->name }}</div>
                        @else
                            <div class="info-row"><span class="label">Statut</span> Non assigné</div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="clean-section">
                    <h6 class="clean-title">Actions</h6>
                    <div class="clean-content">
                        <form method="POST" action="{{ route('syndic.tickets.status', $ticket) }}" class="action-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label class="form-label">Statut</label>
                                <select name="statut" class="form-control">
                                    <option value="ouvert" {{ $ticket->statut == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                                    <option value="en_cours" {{ $ticket->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="resolu" {{ $ticket->statut == 'resolu' ? 'selected' : '' }}>Résolu</option>
                                    <option value="ferme" {{ $ticket->statut == 'ferme' ? 'selected' : '' }}>Fermé</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Coût réel (DT)</label>
                                <input type="number" step="0.01" name="cout_reel" class="form-control" value="{{ $ticket->cout_reel }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Notes de résolution</label>
                                <textarea name="notes_resolution" class="form-control" rows="3">{{ $ticket->notes_resolution }}</textarea>
                            </div>

                            <button type="submit" class="clean-btn primary">
                                Mettre à jour
                            </button>
                        </form>

                        @if($techniciens->count() > 0)
                        <form method="POST" action="{{ route('syndic.tickets.assign', $ticket) }}" class="action-form mt-3">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Assigner à</label>
                                <select name="assignee_id" class="form-control" required>
                                    <option value="">Sélectionner un technicien</option>
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
                            </div>
                            <button type="submit" class="clean-btn warning">
                                Assigner
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
        /* Variables couleurs */
        :root {
            --primary: #173B61;
            --primary-dark: #17616E;
            --primary-light: #7697A0;
            --accent: #FD8916;
            --accent-light: #FFEBD0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --secondary: #6b7280;
        }

        body {
            background-color: var(--accent-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
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

        .btn-outline-modern {
            background: white;
            border: 2px solid var(--primary-light);
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline-modern:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 59, 97, 0.3);
        }

        /* Card principale */
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
            flex-shrink: 0;
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

        /* Détails */
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
            flex-shrink: 0;
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

        /* Photos */
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

        /* Notes de résolution */
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

        /* Sidebar */
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

        .clean-content {
            color: var(--primary);
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

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-badge.ouvert { background: #dbeafe; color: #1e40af; }
        .status-badge.en-cours { background: #fef3c7; color: #92400e; }
        .status-badge.resolu { background: #d1fae5; color: #065f46; }
        .status-badge.ferme { background: #f3f4f6; color: #374151; }
        .status-badge.basse { background: #d1fae5; color: #065f46; }
        .status-badge.moyenne { background: #fef3c7; color: #92400e; }
        .status-badge.haute { background: #fecaca; color: #991b1b; }
        .status-badge.urgente { background: #fee2e2; color: #7f1d1d; }

        /* Formulaires */
        .action-form {
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(253, 137, 22, 0.1);
        }

        /* Boutons */
        .clean-btn {
            display: block;
            width: 100%;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .clean-btn:last-child {
            margin-bottom: 0;
        }

        .clean-btn.primary {
            background: var(--accent);
            color: white;
        }

        .clean-btn.primary:hover {
            background: #e07706;
            color: white;
        }

        .clean-btn.warning {
            background: var(--warning);
            color: white;
        }

        .clean-btn.warning:hover {
            background: #d97706;
            color: white;
        }

        /* Responsive */
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
            
            .info-body {
                padding: 1.5rem;
            }
            
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statutSelect = document.querySelector('select[name="statut"]');
            const notesTextarea = document.querySelector('textarea[name="notes_resolution"]');
            
            if (statutSelect && notesTextarea) {
                statutSelect.addEventListener('change', function() {
                    const label = notesTextarea.previousElementSibling;
                    if (this.value === 'resolu') {
                        notesTextarea.required = true;
                        label.innerHTML = 'Notes de résolution <span style="color: #ef4444;">*</span>';
                    } else {
                        notesTextarea.required = false;
                        label.textContent = 'Notes de résolution';
                    }
                });
            }
        });
    </script>
@endsection