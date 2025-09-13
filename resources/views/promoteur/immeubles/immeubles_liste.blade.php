@extends('layouts.app')

@section('title', 'Mes Immeubles - Promoteur')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section moderne -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h1 class="header-title">Mes Immeubles</h1>
                                <p class="header-subtitle">Promoteur: <strong>{{ $promoteur->nom ?? '' }} {{ $promoteur->prenom ?? '' }}</strong></p>
                            </div>
                        </div>
                        @if($immeuble)
                            <a href="{{ route('promoteur.blocs.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>Ajouter Bloc
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success-modern" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-3"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger-modern" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-3"></i>
                    <div class="flex-grow-1">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if($immeuble)
            <!-- Immeuble Card principale -->
            <div class="card-modern">
                <!-- En-tête de l'immeuble -->
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="building-icon-large">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h2 class="building-title">{{ $immeuble->nom ?? 'Immeuble' }}</h2>
                                <p class="building-address">{{ $immeuble->adresse }}</p>
                            </div>
                        </div>
                        <div>
                            @php
                                $statusClass = $immeuble->statut == 'actif' ? 'success' : 'warning';
                            @endphp
                            <span class="status-badge status-{{ $statusClass }}">
                                <i class="fas fa-{{ $immeuble->statut == 'actif' ? 'check-circle' : 'clock' }} me-2"></i>
                                {{ ucfirst($immeuble->statut ?? 'Inactif') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Section Statistiques -->
                <div class="stats-container">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card stat-primary">
                                <div class="stat-icon-container">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['total_blocs'] ?? 0 }}</h3>
                                    <p class="stat-label">Blocs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card stat-success">
                                <div class="stat-icon-container">
                                    <i class="fas fa-ruler-combined"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ number_format($stats['surface_totale'] ?? 0) }}</h3>
                                    <p class="stat-label">m² Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card stat-info">
                                <div class="stat-icon-container">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['total_appartements'] ?? 0 }}</h3>
                                    <p class="stat-label">Appartements</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card stat-warning">
                                <div class="stat-icon-container">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['annee_construction'] ?? 'N/A' }}</h3>
                                    <p class="stat-label">Année</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Blocs -->
                <div class="blocs-container">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-th-large me-2"></i>
                            Liste des Blocs
                        </h3>
                        <span class="blocs-count">{{ $immeuble->blocs->count() }} blocs</span>
                    </div>

                    @if($immeuble->blocs->count() > 0)
                        <div class="blocs-grid">
                            @foreach($immeuble->blocs as $index => $bloc)
                                @php
                                    $colors = ['primary', 'success', 'info', 'warning', 'danger', 'purple'];
                                    $color = $colors[$index % count($colors)];
                                @endphp
                                <div class="bloc-card bloc-{{ $color }}">
                                    <div class="bloc-header">
                                        <h5 class="bloc-name">{{ $bloc->nom }}</h5>
                                        <div class="bloc-badge bloc-badge-{{ $color }}">
                                            {{ substr($bloc->nom, -1) }}
                                        </div>
                                    </div>

                                    <div class="bloc-stats">
                                        <div class="bloc-stat-item">
                                            <span class="stat-value">{{ $bloc->nombre_appartement ?? 0 }}</span>
                                            <span class="stat-text">Appartements</span>
                                        </div>
                                        <div class="bloc-stat-item">
                                            <span class="stat-value">{{ $bloc->nombre_etages ?? 0 }}</span>
                                            <span class="stat-text">Étages</span>
                                        </div>
                                        <div class="bloc-stat-item">
                                            <span class="stat-value">{{ number_format($bloc->surface_totale ?? 0) }}</span>
                                            <span class="stat-text">m²</span>
                                        </div>
                                    </div>

                                    <div class="generation-status {{ $bloc->appartements->count() > 0 ? 'generated' : 'pending' }}">
                                        <i class="fas fa-{{ $bloc->appartements->count() > 0 ? 'check' : 'clock' }} me-1"></i>
                                        {{ $bloc->appartements->count() }} appartements générés
                                    </div>
                                    
                                    <div class="bloc-actions">
                                        <a href="{{ route('promoteur.appartements.index', ['bloc_id' => $bloc->id]) }}" 
                                           class="btn btn-outline-modern">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>

                                        @if($bloc->appartements->count() == 0)
                                            <button onclick="generateApartments({{ $bloc->id }})" 
                                                    class="btn btn-success-modern">
                                                <i class="fas fa-magic me-1"></i>Générer
                                            </button>
                                        @else
                                            <button onclick="regenerateApartments({{ $bloc->id }})" 
                                                    class="btn btn-warning-modern">
                                                <i class="fas fa-sync me-1"></i>Regénérer
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <h4 class="empty-title">Aucun bloc trouvé</h4>
                            <p class="empty-description">Commencez par ajouter des blocs à votre immeuble.</p>
                            <a href="{{ route('promoteur.blocs.create') }}" class="btn btn-primary-modern">
                                <i class="fas fa-plus me-2"></i>Ajouter un Bloc
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Footer avec actions -->
                <div class="card-footer-modern">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('promoteur.immeubles.export-pdf') }}" class="btn btn-outline-modern">
                                <i class="fas fa-download me-2"></i>Exporter PDF
                            </a>
                            <button class="btn btn-outline-modern">
                                <i class="fas fa-chart-line me-2"></i>Statistiques
                            </button>
                            <a href="{{ route('promoteur.syndics.assign') }}" class="btn btn-outline-modern">
                                <i class="fas fa-user-tie me-2"></i>
                                {{ $immeuble->syndic_id ? 'Gérer Syndic' : 'Assigner Syndic' }}
                            </a>
                        </div>
                        <button class="btn btn-primary-modern">
                            <i class="fas fa-edit me-2"></i>Modifier Immeuble
                        </button>
                    </div>
                </div>
            </div>

        @else
            <!-- État vide (pas d'immeuble) -->
            <div class="card-modern">
                <div class="empty-state-large">
                    <div class="empty-icon-large">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="empty-title-large">Aucun immeuble trouvé</h3>
                    <p class="empty-description-large">Vous n'avez pas encore d'immeuble assigné. Contactez l'administrateur pour plus d'informations.</p>
                    <button class="btn btn-primary-modern btn-lg">
                        <i class="fas fa-phone me-2"></i>Contacter Support
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript (inchangé) -->
    <script>
        function generateApartments(blocId) {
            if (confirm('Générer automatiquement les appartements pour ce bloc ?')) {
                fetch(`/promoteur/blocs/${blocId}/generate-apartments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = `/promoteur/appartements`;
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                });
            }
        }

        function regenerateApartments(blocId) {
            if (confirm('Regénérer tous les appartements ? Cela supprimera les existants.')) {
                fetch(`/promoteur/blocs/${blocId}/regenerate-apartments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                });
            }
        }
    </script>

    <!-- CSS Simplifié et Clair -->
    <style>
        /* Configuration de base */
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header moderne */
        .header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
        }

        .header-icon i {
            font-size: 24px;
            color: white;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .header-subtitle {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Boutons modernes */
        .btn-primary-modern {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.6);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-success-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-warning-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
        }

        .btn-outline-modern {
            border: 2px solid #e2e8f0;
            color: #4a5568;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-outline-modern:hover {
            border-color: #4facfe;
            color: #4facfe;
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Alertes modernes */
        .alert-success-modern {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #b8dacc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #155724;
        }

        .alert-danger-modern {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f1b0b7;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #721c24;
        }

        /* Card principale */
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header-modern {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            color: white;
            padding: 2rem;
        }

        .building-icon-large {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
        }

        .building-icon-large i {
            font-size: 28px;
            color: white;
        }

        .building-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .building-address {
            margin: 0;
            opacity: 0.8;
            font-size: 1.1rem;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-success {
            background: rgba(72, 187, 120, 0.1);
            color: #2f855a;
            border: 2px solid rgba(72, 187, 120, 0.2);
        }

        .status-warning {
            background: rgba(237, 137, 54, 0.1);
            color: #c05621;
            border: 2px solid rgba(237, 137, 54, 0.2);
        }

        /* Section statistiques */
        .stats-container {
            padding: 2rem;
            background: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-icon-container {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 24px;
        }

        .stat-primary .stat-icon-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-success .stat-icon-container {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .stat-info .stat-icon-container {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-warning .stat-icon-container {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #718096;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        /* Section blocs */
        .blocs-container {
            padding: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .blocs-count {
            background: #edf2f7;
            color: #4a5568;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Grid des blocs */
        .blocs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .bloc-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .bloc-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .bloc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .bloc-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .bloc-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 0.875rem;
        }

        .bloc-badge-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bloc-badge-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .bloc-badge-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bloc-badge-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bloc-badge-danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bloc-badge-purple { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

        .bloc-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .bloc-stat-item {
            text-align: center;
            padding: 1rem 0.5rem;
            background: #f7fafc;
            border-radius: 10px;
        }

        .stat-value {
            display: block;
            font-size: 1.25rem;
            font-weight: 800;
            color: #2d3748;
        }

        .stat-text {
            display: block;
            font-size: 0.75rem;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 0.25rem;
        }

        .generation-status {
            text-align: center;
            padding: 0.75rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .generation-status.generated {
            background: rgba(72, 187, 120, 0.1);
            color: #2f855a;
        }

        .generation-status.pending {
            background: rgba(237, 137, 54, 0.1);
            color: #c05621;
        }

        .bloc-actions {
            display: flex;
            gap: 0.5rem;
        }

        .bloc-actions .btn {
            flex: 1;
        }

        /* États vides */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #edf2f7;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .empty-icon i {
            font-size: 2rem;
            color: #a0aec0;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .empty-description {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .empty-state-large {
            text-align: center;
            padding: 4rem 1rem;
        }

        .empty-icon-large {
            width: 120px;
            height: 120px;
            background: #edf2f7;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .empty-icon-large i {
            font-size: 3rem;
            color: #a0aec0;
        }

        .empty-title-large {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .empty-description-large {
            color: #718096;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        .card-footer-modern {
            padding: 1.5rem 2rem;
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-modern {
                padding: 1.5rem;
            }
            
            .header-title {
                font-size: 1.5rem;
            }

            .stats-container {
                padding: 1.5rem;
            }

            .blocs-container {
                padding: 1.5rem;
            }

            .card-footer-modern {
                padding: 1.5rem;
            }

            .card-footer-modern .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .blocs-grid {
                grid-template-columns: 1fr;
            }

            .bloc-stats {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .bloc-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection