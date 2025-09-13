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
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        
        .pdf-container {
            background: white;
            margin: 20px auto;
            max-width: 1200px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .stats-card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .appartement-card {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
        }
        
        .status-badge {
            font-size: 0.8rem;
            padding: 4px 8px;
        }
        
        .section-title {
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        
        <!-- Header Section -->
        <div class="header-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-0">
                        <i class="fas fa-building me-3"></i>
                        {{ $immeuble->nom }}
                    </h1>
                    <p class="lead mb-0">Rapport Complet d'Immeuble</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white bg-opacity-20 rounded p-3">
                        <p class="mb-1"><strong>Promoteur:</strong></p>
                        <p class="mb-1">{{ $promoteur->nom ?? 'N/A' }} {{ $promoteur->prenom ?? '' }}</p>
                        <p class="mb-0 small">{{ date('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4">
            
            <!-- Informations Générales -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="col-lg-6">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>Localisation
                            </h5>
                            <p class="mb-2"><strong>Adresse:</strong> {{ $immeuble->adresse }}</p>
                            <p class="mb-2"><strong>Statut:</strong> 
                                <span class="badge bg-{{ $immeuble->statut == 'actif' ? 'success' : 'warning' }}">
                                    {{ ucfirst($immeuble->statut) }}
                                </span>
                            </p>
                            <p class="mb-0"><strong>Année:</strong> {{ $immeuble->annee_construction ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card stats-card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i class="fas fa-ruler-combined me-2"></i>Dimensions
                            </h5>
                            <p class="mb-2"><strong>Surface totale:</strong> 
                                <span class="h5 text-success">{{ number_format($stats['surface_totale'] ?? 0) }} m²</span>
                            </p>
                            <p class="mb-0"><strong>Efficacité:</strong> 
                                {{ $stats['total_appartements'] > 0 ? round(($stats['surface_totale'] ?? 0) / $stats['total_appartements']) : 0 }} m²/appartement
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques Principales -->
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="section-title">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="text-primary mb-3">
                                <i class="fas fa-layer-group fa-2x"></i>
                            </div>
                            <h3 class="h2 text-primary">{{ $stats['total_blocs'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">Blocs</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="text-success mb-3">
                                <i class="fas fa-door-open fa-2x"></i>
                            </div>
                            <h3 class="h2 text-success">{{ $stats['total_appartements'] ?? 0 }}</h3>
                            <p class="mb-0 text-muted">Appartements</p>
                        </div>
                    </div>
                </div>

                @php
                    $totalAppartements = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->count(); });
                    $libres = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'libre')->count(); });
                    $occupes = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'occupe')->count(); });
                    $tauxOccupation = $totalAppartements > 0 ? round(($occupes / $totalAppartements) * 100) : 0;
                @endphp

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h3 class="h2 text-warning">{{ $occupes }}</h3>
                            <p class="mb-0 text-muted">Occupés</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="text-info mb-3">
                                <i class="fas fa-percentage fa-2x"></i>
                            </div>
                            <h3 class="h2 text-info">{{ $tauxOccupation }}%</h3>
                            <p class="mb-0 text-muted">Taux d'occupation</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($immeuble->blocs->count() > 0)
                
                <!-- Vue d'ensemble des Blocs -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-th-large text-primary me-2"></i>
                            Vue d'ensemble des Blocs
                        </h2>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-tag me-1"></i>Nom du Bloc</th>
                                        <th><i class="fas fa-home me-1"></i>Appartements Prévus</th>
                                        <th><i class="fas fa-check me-1"></i>Appartements Générés</th>
                                        <th><i class="fas fa-building me-1"></i>Étages</th>
                                        <th><i class="fas fa-ruler me-1"></i>Surface (m²)</th>
                                        <th><i class="fas fa-chart-pie me-1"></i>Progression</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($immeuble->blocs as $bloc)
                                        @php
                                            $progression = $bloc->nombre_appartement > 0 ? 
                                                round(($bloc->appartements->count() / $bloc->nombre_appartement) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $bloc->nom }}</strong></td>
                                            <td>{{ $bloc->nombre_appartement ?? 0 }}</td>
                                            <td>
                                                <span class="badge bg-{{ $bloc->appartements->count() > 0 ? 'success' : 'warning' }}">
                                                    {{ $bloc->appartements->count() }}
                                                </span>
                                            </td>
                                            <td>{{ $bloc->nombre_etages ?? 0 }}</td>
                                            <td>{{ number_format($bloc->surface_totale ?? 0) }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ $progression == 100 ? 'success' : ($progression > 0 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $progression }}%">
                                                        {{ $progression }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Détail des Appartements par Bloc -->
                <div class="page-break"></div>
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title">
                            <i class="fas fa-list-alt text-primary me-2"></i>
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
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h4 class="mb-0">
                                                <i class="fas fa-building me-2"></i>
                                                {{ $bloc->nom }}
                                            </h4>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-light text-dark">
                                                {{ $bloc->appartements->count() }} appartements générés
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($bloc->appartements->count() > 0)
                                        <div class="row g-3">
                                            @foreach($bloc->appartements->sortBy('numero') as $appartement)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="appartement-card p-3 rounded">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="mb-0 fw-bold">N° {{ $appartement->numero }}</h6>
                                                            <span class="status-badge badge bg-{{ 
                                                                $appartement->statut == 'libre' ? 'success' : 
                                                                ($appartement->statut == 'occupe' ? 'warning' : 
                                                                ($appartement->statut == 'travaux' ? 'info' : 
                                                                ($appartement->statut == 'reserve' ? 'secondary' : 'danger'))) 
                                                            }}">
                                                                {{ ucfirst($appartement->statut ?? 'N/A') }}
                                                            </span>
                                                        </div>
                                                        <div class="small">
                                                            <div class="mb-1">
                                                                <i class="fas fa-home me-1 text-muted"></i>
                                                                {{ $appartement->type_appartement ?? 'Type N/A' }}
                                                            </div>
                                                            <div class="mb-1">
                                                                <i class="fas fa-ruler-combined me-1 text-muted"></i>
                                                                {{ $appartement->surface ? $appartement->surface . ' m²' : 'Surface N/A' }}
                                                            </div>
                                                            <div>
                                                                <i class="fas fa-door-closed me-1 text-muted"></i>
                                                                {{ $appartement->nombre_pieces ?? 0 }} pièces
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun appartement généré pour ce bloc</p>
                                            <small class="text-muted">Cliquez sur "Générer" dans l'interface pour créer les appartements</small>
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
                    <div class="row">
                        <div class="col-12">
                            <h2 class="section-title">
                                <i class="fas fa-chart-pie text-primary me-2"></i>
                                Analyse des Statuts
                            </h2>
                        </div>
                        
                        @php
                            $travaux = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'travaux')->count(); });
                            $reserves = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'reserve')->count(); });
                            $maintenance = $immeuble->blocs->sum(function($bloc) { return $bloc->appartements->where('statut', 'maintenance')->count(); });
                        @endphp

                        <div class="col-lg-6">
                            <div class="card stats-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Répartition par Statut</h5>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="bg-success bg-opacity-10 p-2 rounded text-center">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <div><strong>{{ $libres }}</strong></div>
                                                <small>Libres ({{ $totalAppartements > 0 ? round($libres/$totalAppartements*100) : 0 }}%)</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-warning bg-opacity-10 p-2 rounded text-center">
                                                <i class="fas fa-user text-warning"></i>
                                                <div><strong>{{ $occupes }}</strong></div>
                                                <small>Occupés ({{ $totalAppartements > 0 ? round($occupes/$totalAppartements*100) : 0 }}%)</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-info bg-opacity-10 p-2 rounded text-center">
                                                <i class="fas fa-tools text-info"></i>
                                                <div><strong>{{ $travaux }}</strong></div>
                                                <small>En travaux</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-secondary bg-opacity-10 p-2 rounded text-center">
                                                <i class="fas fa-clock text-secondary"></i>
                                                <div><strong>{{ $reserves }}</strong></div>
                                                <small>Réservés</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="card stats-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Informations Techniques</h5>
                                    @php
                                        $surfaceTotaleAppartements = $immeuble->blocs->sum(function($bloc) { 
                                            return $bloc->appartements->where('surface', '!=', null)->sum('surface'); 
                                        });
                                        $nombreAvecSurface = $immeuble->blocs->sum(function($bloc) { 
                                            return $bloc->appartements->where('surface', '!=', null)->count(); 
                                        });
                                        $surfaceMoyenne = $nombreAvecSurface > 0 ? round($surfaceTotaleAppartements/$nombreAvecSurface, 1) : 0;
                                    @endphp
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Total étages:</span>
                                            <strong>{{ $immeuble->blocs->sum('nombre_etages') }}</strong>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Surface moyenne:</span>
                                            <strong>{{ $surfaceMoyenne }} m²</strong>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Appartements avec surface définie:</span>
                                            <strong>{{ $nombreAvecSurface }}/{{ $totalAppartements }}</strong>
                                        </div>
                                    </div>
                                    @if($maintenance > 0)
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <span>En maintenance:</span>
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
                <div class="text-center py-5">
                    <i class="fas fa-building fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted">Aucun bloc créé</h3>
                    <p class="text-muted">Commencez par ajouter des blocs à votre immeuble depuis l'interface de gestion.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-light p-4 text-center border-top">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-robot me-1"></i>
                        Rapport généré automatiquement par le système de gestion
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-calendar me-1"></i>
                        {{ date('d/m/Y à H:i:s') }} - 
                        <strong>{{ $totalAppartements ?? 0 }}</strong> appartements au total
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton d'impression (masqué à l'impression) -->
    <div class="text-center py-3 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
            <i class="fas fa-print me-2"></i>Imprimer / Sauvegarder PDF
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-times me-2"></i>Fermer
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>