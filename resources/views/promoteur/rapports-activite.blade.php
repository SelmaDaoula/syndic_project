{{-- resources/views/promoteur/rapports/activite.blade.php --}}
@extends('layouts.app')

@section('title', 'Rapport d\'Activité')

@section('content')
<div class="container-fluid">
    <!-- Header avec filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h1 class="h3 mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Rapport d'Activité
                            </h1>
                            <p class="mb-0 opacity-75">Analyse détaillée de votre portefeuille immobilier</p>
                        </div>
                        <div class="col-lg-6">
                            <form method="GET" class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label text-white small">Période</label>
                                    <select name="periode" class="form-select form-select-sm">
                                        <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                                        <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                                        <option value="semestre" {{ request('periode') == 'semestre' ? 'selected' : '' }}>Ce semestre</option>
                                        <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-light btn-sm w-100">
                                        <i class="fas fa-refresh me-1"></i>Actualiser
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Principaux -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-euro-sign text-success fa-lg"></i>
                    </div>
                    <h3 class="text-success mb-1">{{ number_format($kpis['revenus_mensuels'] ?? 0) }} €</h3>
                    <p class="text-muted mb-0">Revenus Mensuels</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>+12% vs mois précédent
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-percentage text-primary fa-lg"></i>
                    </div>
                    <h3 class="text-primary mb-1">{{ number_format($kpis['taux_occupation'] ?? 0, 1) }}%</h3>
                    <p class="text-muted mb-0">Taux d'Occupation</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>+2.5% vs mois précédent
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-clock text-warning fa-lg"></i>
                    </div>
                    <h3 class="text-warning mb-1">{{ $kpis['duree_moyenne_vacance'] ?? 0 }} j</h3>
                    <p class="text-muted mb-0">Durée Moyenne Vacance</p>
                    <small class="text-danger">
                        <i class="fas fa-arrow-down me-1"></i>-5 jours vs mois précédent
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-handshake text-info fa-lg"></i>
                    </div>
                    <h3 class="text-info mb-1">{{ $kpis['nouvelles_locations'] ?? 0 }}</h3>
                    <p class="text-muted mb-0">Nouvelles Locations</p>
                    <small class="text-success">
                        <i class="fas fa-arrow-up me-1"></i>+3 vs mois précédent
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Graphique Évolution Revenus -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Évolution des Revenus
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active">Mensuel</button>
                            <button class="btn btn-outline-primary">Trimestriel</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenusChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Répartition par Statut -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Répartition des Appartements
                    </h5>
                </div>
                <div class="card-body" style="position: relative; height: 280px;">
                    <canvas id="statutChart" style="max-height: 260px;"></canvas>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-success me-1"></i>Libres</span>
                            <strong>{{ $repartition['libres'] ?? 3 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-primary me-1"></i>Occupés</span>
                            <strong>{{ $repartition['occupes'] ?? 15 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-circle text-warning me-1"></i>En travaux</span>
                            <strong>{{ $repartition['travaux'] ?? 2 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-circle text-secondary me-1"></i>Réservés</span>
                            <strong>{{ $repartition['reserves'] ?? 1 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Performance par Bloc -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0">
                        <i class="fas fa-building text-info me-2"></i>
                        Performance par Bloc
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bloc</th>
                                    <th>Taux Occupation</th>
                                    <th>Revenu/m²</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($performance_blocs ?? [] as $bloc)
                                <tr>
                                    <td><strong>{{ $bloc['nom'] }}</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $bloc['taux'] >= 80 ? 'success' : ($bloc['taux'] >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $bloc['taux'] }}%">
                                                {{ $bloc['taux'] }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($bloc['revenu_m2'], 2) }} €</td>
                                    <td>
                                        <span class="badge bg-{{ $bloc['performance'] == 'excellent' ? 'success' : ($bloc['performance'] == 'bon' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($bloc['performance']) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et Notifications -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0">
                        <i class="fas fa-bell text-warning me-2"></i>
                        Alertes & Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Loyer impayé</h6>
                                    <small class="text-muted">Appartement 201 - Bloc A</small>
                                </div>
                                <span class="badge bg-danger">Urgent</span>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-calendar text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Fin de bail proche</h6>
                                    <small class="text-muted">3 baux se terminent ce mois</small>
                                </div>
                                <span class="badge bg-warning">Attention</span>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-tools text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Travaux programmés</h6>
                                    <small class="text-muted">Rénovation Bloc B prévue</small>
                                </div>
                                <span class="badge bg-info">Info</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Recommandées -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Actions Recommandées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <div class="bg-light rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-euro-sign text-success me-2"></i>
                                    <h6 class="mb-0">Optimisation Revenus</h6>
                                </div>
                                <p class="small text-muted mb-2">Réviser les loyers de 3 appartements sous-évalués</p>
                                <button class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-plus me-1"></i>Augmentation +8%
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <div class="bg-light rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-search text-primary me-2"></i>
                                    <h6 class="mb-0">Recherche Locataires</h6>
                                </div>
                                <p class="small text-muted mb-2">Lancer la commercialisation de 2 appartements libres</p>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-play me-1"></i>Publier Annonces
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mb-3">
                            <div class="bg-light rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-wrench text-info me-2"></i>
                                    <h6 class="mb-0">Maintenance Préventive</h6>
                                </div>
                                <p class="small text-muted mb-2">Planifier l'entretien annuel des équipements</p>
                                <button class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-calendar me-1"></i>Programmer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer avec exports -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Exporter PDF
                        </button>
                        <button type="button" class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-2"></i>Exporter Excel
                        </button>
                        <button type="button" class="btn btn-outline-info">
                            <i class="fas fa-envelope me-2"></i>Envoyer par Email
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i>Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuration des graphiques Chart.js
const revenusCtx = document.getElementById('revenusChart').getContext('2d');
new Chart(revenusCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Revenus (€)',
            data: [12000, 15000, 13500, 16000, 14500, 17000],
            borderColor: '#0d6efd',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

const statutCtx = document.getElementById('statutChart').getContext('2d');
new Chart(statutCtx, {
    type: 'doughnut',
    data: {
        labels: ['Libres', 'Occupés', 'Travaux', 'Réservés'],
        datasets: [{
            data: [{{ $repartition['libres'] ?? 0 }}, {{ $repartition['occupes'] ?? 0 }}, {{ $repartition['travaux'] ?? 0 }}, {{ $repartition['reserves'] ?? 0 }}],
            backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#6c757d']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection