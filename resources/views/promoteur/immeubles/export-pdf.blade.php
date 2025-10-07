{{-- resources/views/promoteur/immeubles/export-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export PDF - {{ $immeuble->nom }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Variables couleurs synchronisées avec la plateforme */
        :root {
            --primary: #173B61;
            --primary-dark: #17616E;
            --primary-light: #7697A0;
            --accent: #FD8916;
            --accent-light: #f8fafc; /* Changé de #FFEBD0 vers un gris très clair */
            --info: #87ABF1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #F0050F;
            --secondary: #6b7280;
        }

        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }

        /* Base identique à la plateforme */
        body {
            background-color: var(--accent-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animations synchronisées */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-item { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }

        .pdf-container {
            background: white;
            margin: 20px auto;
            max-width: 1400px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Header moderne comme la plateforme */
        .header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
            margin-bottom: 1rem;
        }

        .page-title {
            font-size: 2.5rem;
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
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .header-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Cards statistiques comme la plateforme */
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            transition: height 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .stats-card:hover::before {
            height: 5px;
        }

        .stats-card.primary::before { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
        .stats-card.success::before { background: linear-gradient(135deg, var(--success), #34d399); }
        .stats-card.warning::before { background: linear-gradient(135deg, var(--accent), #FF9933); }
        .stats-card.info::before { background: linear-gradient(135deg, var(--info), #60a5fa); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .primary .stat-icon { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
        .success .stat-icon { background: linear-gradient(135deg, var(--success), #34d399); }
        .warning .stat-icon { background: linear-gradient(135deg, var(--accent), #FF9933); }
        .info .stat-icon { background: linear-gradient(135deg, var(--info), #60a5fa); }

        /* Section titles comme la plateforme */
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 32px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--accent);
        }

        .section-title i {
            color: var(--accent);
        }

        /* Table modernisée comme la plateforme */
        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
            margin-bottom: 2rem;
        }

        .table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border: none;
            font-size: 0.9rem;
        }

        .table-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.02), rgba(23, 97, 110, 0.02));
            transform: translateX(5px);
        }

        .table-row td {
            padding: 1.25rem 1.5rem;
            border: none;
            vertical-align: middle;
        }

        /* Appartement cards comme la plateforme */
        .appartement-card {
            background: var(--accent-light);
            border: 1px solid rgba(23, 59, 97, 0.1); /* Changé pour être plus subtil */
            border-radius: 12px;
            transition: all 0.2s ease;
            border-left: 4px solid var(--primary);
        }

        .appartement-card:hover {
            border-color: var(--accent);
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        /* Status badges comme la plateforme */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .status-badge.bg-success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #065f46 !important;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .status-badge.bg-warning {
            background: rgba(253, 137, 22, 0.1) !important;
            color: #92400e !important;
            border-color: rgba(253, 137, 22, 0.3);
        }

        .status-badge.bg-info {
            background: rgba(135, 171, 241, 0.1) !important;
            color: #1e40af !important;
            border-color: rgba(135, 171, 241, 0.3);
        }

        .status-badge.bg-secondary {
            background: rgba(107, 114, 128, 0.1) !important;
            color: #374151 !important;
            border-color: rgba(107, 114, 128, 0.3);
        }

        .status-badge.bg-danger {
            background: rgba(240, 5, 15, 0.1) !important;
            color: #7f1d1d !important;
            border-color: rgba(240, 5, 15, 0.3);
        }

        /* Cartes de statut modernisées */
        .status-overview {
            background: var(--accent-light);
            border-radius: 12px;
            padding: 20px;
        }

        .status-item {
            background: white;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .status-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .status-item i {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .status-item strong {
            display: block;
            font-size: 1.25rem;
            margin-bottom: 4px;
        }

        .status-item small {
            color: var(--primary-light);
            font-size: 0.875rem;
        }

        /* Couleurs des icônes */
        .text-primary { color: var(--primary) !important; }
        .text-success { color: var(--success) !important; }
        .text-warning { color: var(--accent) !important; }
        .text-info { color: var(--info) !important; }
        .text-muted { color: var(--primary-light) !important; }
        .text-danger { color: var(--danger) !important; }

        /* Progress bars modernisées */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #f1f5f9;
        }

        .progress-bar {
            border-radius: 4px;
        }

        .progress-bar.bg-success { background: linear-gradient(135deg, var(--success), #34d399) !important; }
        .progress-bar.bg-warning { background: linear-gradient(135deg, var(--accent), #FF9933) !important; }
        .progress-bar.bg-danger { background: linear-gradient(135deg, var(--danger), #ef4444) !important; }

        /* Boutons comme la plateforme */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #FF9933 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 137, 22, 0.4);
            color: white;
        }

        .btn-outline-secondary {
            border-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 59, 97, 0.3);
        }

        /* Footer modernisé */
        .footer-section {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
        }

        .footer-section p {
            margin: 0;
            color: var(--primary-light);
            font-size: 0.875rem;
        }

        /* Content spacing */
        .content-section {
            padding: 40px;
        }

        .mb-section {
            margin-bottom: 48px;
        }

        /* État vide */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-icon i {
            font-size: 2rem;
            color: var(--primary-light);
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .empty-description {
            color: var(--primary-light);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .page-title {
                font-size: 2rem;
            }
            
            .content-section {
                padding: 24px;
            }
        }

        @media (max-width: 768px) {
            .header-section {
                padding: 1.5rem;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
            
            .content-section {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .pdf-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .header-section {
                padding: 1rem;
            }
            
            .content-section {
                padding: 15px;
            }
        }

        /* Logo professionnel personnalisé */
        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
            background: white;
            border: 3px solid rgba(23, 59, 97, 0.1);
            overflow: hidden;
            position: relative;
        }

        .company-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 16px;
        }

        /* Fallback au cas où l'image ne charge pas */
        .company-logo::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
            text-align: center;
            z-index: -1;
        }

        /* Style pour le conteneur du logo dans le header */
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="pdf-container fade-in-up">
        
        <!-- Header Section modernisé -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="logo-container">
                        <div class="company-logo">
                            <img src="{{ asset('images/logo2.png') }}" 
                                 alt="Logo Entreprise" 
                                 onerror="this.style.display='none'; this.parentElement.classList.add('fallback-logo');">
                        </div>
                        <div class="ms-3">
                            <h1 class="page-title mb-2">{{ $immeuble->nom }}</h1>
                            <p class="page-subtitle mb-0">Rapport Complet d'Immeuble</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="header-info">
                        <div class="mb-2"><strong>Promoteur</strong></div>
                        <div class="mb-3">{{ $promoteur->nom ?? 'N/A' }} {{ $promoteur->prenom ?? '' }}</div>
                        <div class="small opacity-75">{{ date('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section">
            
            <!-- Informations Générales -->
            <div class="row mb-section">
                <div class="col-12">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card stats-card primary h-100 fade-in-item" style="animation-delay: 0ms">
                        <div class="card-body p-4">
                            <div class="stat-icon mb-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5 class="card-title mb-3 text-primary">Localisation</h5>
                            <div class="mb-3">
                                <strong class="text-muted d-block mb-1">Adresse</strong>
                                <span>{{ $immeuble->adresse }}</span>
                            </div>
                            <div class="mb-3">
                                <strong class="text-muted d-block mb-1">Statut</strong>
                                <span class="status-badge bg-{{ $immeuble->statut == 'actif' ? 'success' : 'warning' }}">
                                    {{ ucfirst($immeuble->statut) }}
                                </span>
                            </div>
                            <div>
                                <strong class="text-muted d-block mb-1">Année de construction</strong>
                                <span>{{ $immeuble->annee_construction ?? 'Non spécifiée' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card stats-card success h-100 fade-in-item" style="animation-delay: 100ms">
                        <div class="card-body p-4">
                            <div class="stat-icon mb-3">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                            <h5 class="card-title mb-3 text-success">Dimensions</h5>
                            <div class="mb-3">
                                <strong class="text-muted d-block mb-1">Surface totale</strong>
                                <span class="h4 text-success mb-0">{{ number_format($stats['surface_totale'] ?? 0) }} m²</span>
                            </div>
                            <div>
                                <strong class="text-muted d-block mb-1">Efficacité</strong>
                                <span>{{ $stats['total_appartements'] > 0 ? round(($stats['surface_totale'] ?? 0) / $stats['total_appartements']) : 0 }} m²/appartement</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques Principales -->
            <div class="row mb-section">
                <div class="col-12">
                    <h2 class="section-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card primary text-center fade-in-item" style="animation-delay: 0ms">
                        <div class="card-body p-3">
                            <div class="stat-icon mx-auto mb-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h3 class="h1 text-primary mb-2">{{ $stats['total_blocs'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Blocs</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card success text-center fade-in-item" style="animation-delay: 100ms">
                        <div class="card-body p-3">
                            <div class="stat-icon mx-auto mb-3">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <h3 class="h1 text-success mb-2">{{ $stats['total_appartements'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Appartements</p>
                        </div>
                    </div>
                </div>

                @php
                    $totalAppartements = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->count(); });
                    $libres = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'libre')->count(); });
                    $occupes = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'occupe')->count(); });
                    $tauxOccupation = $totalAppartements > 0 ? round(($occupes / $totalAppartements) * 100) : 0;
                @endphp

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card warning text-center fade-in-item" style="animation-delay: 200ms">
                        <div class="card-body p-3">
                            <div class="stat-icon mx-auto mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3 class="h1 text-warning mb-2">{{ $occupes }}</h3>
                            <p class="text-muted mb-0">Occupés</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card stats-card info text-center fade-in-item" style="animation-delay: 300ms">
                        <div class="card-body p-3">
                            <div class="stat-icon mx-auto mb-3">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <h3 class="h1 text-info mb-2">{{ $tauxOccupation }}%</h3>
                            <p class="text-muted mb-0">Taux d'occupation</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($immeuble->blocs->count() > 0)
                
                <!-- Vue d'ensemble des Blocs -->
                <div class="row mb-section">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-th-large me-2"></i>
                            Vue d'ensemble des Blocs
                        </h2>
                        <div class="table-card">
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th class="fw-medium">Nom du Bloc</th>
                                            <th class="fw-medium">Appartements Prévus</th>
                                            <th class="fw-medium">Appartements Générés</th>
                                            <th class="fw-medium">Étages</th>
                                            <th class="fw-medium">Surface (m²)</th>
                                            <th class="fw-medium">Progression</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($immeuble->blocs as $index => $bloc)
                                            @php
                                                $progression = $bloc->nombre_appartement > 0 ? 
                                                    round(($bloc->appartements->count() / $bloc->nombre_appartement) * 100) : 0;
                                            @endphp
                                            <tr class="table-row fade-in-item" style="animation-delay: {{ $index * 20 }}ms">
                                                <td><strong>{{ $bloc->nom }}</strong></td>
                                                <td>{{ $bloc->nombre_appartement ?? 0 }}</td>
                                                <td>
                                                    <span class="status-badge bg-{{ $bloc->appartements->count() > 0 ? 'success' : 'warning' }}">
                                                        {{ $bloc->appartements->count() }}
                                                    </span>
                                                </td>
                                                <td>{{ $bloc->nombre_etages ?? 0 }}</td>
                                                <td>{{ number_format($bloc->surface_totale ?? 0) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2">
                                                            <div class="progress-bar bg-{{ $progression == 100 ? 'success' : ($progression > 0 ? 'warning' : 'danger') }}" 
                                                                 style="width: {{ $progression }}%">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ $progression }}%</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détail des Appartements par Bloc -->
                <div class="page-break"></div>
                <div class="row mb-section">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-list-alt me-2"></i>
                            Détail des Appartements par Bloc
                        </h2>
                    </div>
                </div>

                @foreach($immeuble->blocs as $index => $bloc)
                    @if($index > 0 && $index % 2 == 0)
                        <div class="page-break"></div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-card fade-in-item" style="animation-delay: {{ $index * 100 }}ms">
                                <div style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 20px 24px;">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h4 class="mb-0 fw-bold">
                                                <i class="fas fa-building me-2"></i>
                                                {{ $bloc->nom }}
                                            </h4>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="status-badge bg-light text-dark fs-6">
                                                {{ $bloc->appartements->count() }} appartements générés
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    @if($bloc->appartements->count() > 0)
                                        <div class="row g-3">
                                            @foreach($bloc->appartements->sortBy('numero') as $appartement)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="appartement-card p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <h6 class="mb-0 fw-bold">N° {{ $appartement->numero }}</h6>
                                                            <span class="status-badge bg-{{ 
                                                                $appartement->statut == 'libre' ? 'success' : 
                                                                ($appartement->statut == 'occupe' ? 'warning' : 
                                                                ($appartement->statut == 'travaux' ? 'info' : 
                                                                ($appartement->statut == 'reserve' ? 'secondary' : 'danger'))) 
                                                            }}">
                                                                {{ ucfirst($appartement->statut ?? 'N/A') }}
                                                            </span>
                                                        </div>
                                                        <div class="small">
                                                            <div class="mb-2 d-flex align-items-center">
                                                                <i class="fas fa-home text-muted me-2"></i>
                                                                <span>{{ $appartement->type_appartement ?? 'Type N/A' }}</span>
                                                            </div>
                                                            <div class="mb-2 d-flex align-items-center">
                                                                <i class="fas fa-ruler-combined text-muted me-2"></i>
                                                                <span>{{ $appartement->surface ? $appartement->surface . ' m²' : 'Surface N/A' }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-door-closed text-muted me-2"></i>
                                                                <span>{{ $appartement->nombre_pieces ?? 0 }} pièces</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <h5 class="empty-title">Aucun appartement généré</h5>
                                            <p class="empty-description mb-0">Utilisez l'interface de gestion pour générer les appartements de ce bloc</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Analyse des Statuts -->
                @if($totalAppartements > 0)
                    <div class="page-break"></div>
                    <div class="row mb-section">
                        <div class="col-12">
                            <h2 class="section-title">
                                <i class="fas fa-chart-pie me-2"></i>
                                Analyse des Statuts
                            </h2>
                        </div>
                        
                        @php
                            $travaux = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'travaux')->count(); });
                            $reserves = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'reserve')->count(); });
                            $maintenance = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'maintenance')->count(); });
                        @endphp

                        <div class="col-lg-6 mb-4">
                            <div class="card stats-card h-100 fade-in-item" style="animation-delay: 0ms">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Répartition par Statut</h5>
                                    <div class="status-overview">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="status-item">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    <strong>{{ $libres }}</strong>
                                                    <small>Libres ({{ $totalAppartements > 0 ? round($libres/$totalAppartements*100) : 0 }}%)</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="status-item">
                                                    <i class="fas fa-user text-warning"></i>
                                                    <strong>{{ $occupes }}</strong>
                                                    <small>Occupés ({{ $totalAppartements > 0 ? round($occupes/$totalAppartements*100) : 0 }}%)</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="status-item">
                                                    <i class="fas fa-tools text-info"></i>
                                                    <strong>{{ $travaux }}</strong>
                                                    <small>En travaux</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="status-item">
                                                    <i class="fas fa-clock text-muted"></i>
                                                    <strong>{{ $reserves }}</strong>
                                                    <small>Réservés</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="card stats-card h-100 fade-in-item" style="animation-delay: 100ms">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-4">Informations Techniques</h5>
                                    @php
                                        $surfaceTotaleAppartements = $immeuble->blocs->sum(function($bloc) { 
                                            return $bloc->appartements->where('surface', '!=', null)->sum('surface'); 
                                        });
                                        $nombreAvecSurface = $immeuble->blocs->sum(function($bloc) { 
                                            return $bloc->appartements->where('surface', '!=', null)->count(); 
                                        });
                                        $surfaceMoyenne = $nombreAvecSurface > 0 ? round($surfaceTotaleAppartements/$nombreAvecSurface, 1) : 0;
                                    @endphp
                                    
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Total étages</span>
                                            <strong>{{ $immeuble->blocs->sum('nombre_etages') }}</strong>
                                        </div>
                                    </div>
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Surface moyenne</span>
                                            <strong>{{ $surfaceMoyenne }} m²</strong>
                                        </div>
                                    </div>
                                    <div class="mb-3 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Avec surface définie</span>
                                            <strong>{{ $nombreAvecSurface }}/{{ $totalAppartements }}</strong>
                                        </div>
                                    </div>
                                    @if($maintenance > 0)
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted">En maintenance</span>
                                                <strong class="text-danger">{{ $maintenance }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="empty-title">Aucun bloc créé</h3>
                    <p class="empty-description">Commencez par ajouter des blocs à votre immeuble depuis l'interface de gestion.</p>
                </div>
            @endif
        </div>

        <!-- Footer modernisé -->
        <div class="footer-section">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>
                        <i class="fas fa-robot me-2"></i>
                        Rapport généré automatiquement par le système de gestion
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p>
                        <i class="fas fa-calendar me-2"></i>
                        {{ date('d/m/Y à H:i:s') }} - 
                        <strong>{{ $totalAppartements ?? 0 }}</strong> appartements au total
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action modernisés -->
    <div class="text-center py-4 no-print">
        <button onclick="window.print()" class="btn btn-primary me-3">
            <i class="fas fa-print me-2"></i>Imprimer / Sauvegarder PDF
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary">
            <i class="fas fa-times me-2"></i>Fermer
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Style de fallback pour le logo si l'image ne se charge pas */
        .company-logo.fallback-logo::after {
            content: "LOGO";
            font-family: 'Segoe UI', sans-serif;
            font-weight: 800;
            letter-spacing: 1px;
            z-index: 1;
            color: white;
        }
    </style>
</body>
</html>