@extends('layouts.app')

@section('title', 'Dashboard Promoteur')

@section('content')
    @php
        // Calculer les statistiques directement dans la vue
        $immeubles = $promoteur->immeubles ?? collect();
        $totalAppartements = 0;
        $syndicAssignes = 0;
        
        foreach ($immeubles as $immeuble) {
            if ($immeuble->blocs) {
                foreach ($immeuble->blocs as $bloc) {
                    $totalAppartements += $bloc->nombre_appartement ?? 0;
                }
            }
            if ($immeuble->syndic_id) {
                $syndicAssignes++;
            }
        }
        
        $joursRestants = 0;
        if ($promoteur->hasValidSubscription()) {
            $abonnement = $promoteur->getAbonnementActif();
            if ($abonnement && $abonnement->date_fin && !$abonnement->date_fin->isPast()) {
                $joursRestants = now()->diffInDays($abonnement->date_fin);
            }
        }
        
        $stats = [
            'total_immeubles' => $immeubles->count(),
            'total_appartements' => $totalAppartements,
            'syndics_actifs' => $syndicAssignes,
            'jours_restants' => $joursRestants,
        ];
    @endphp

    <div class="dashboard-container">
        <!-- En-tête -->
        <div class="dashboard-header">
            <div class="header-left">
                <h1>Bonjour {{ $promoteur->prenom ?? 'Promoteur' }}</h1>
                <p>Voici un résumé de votre activité</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('promoteur.immeubles.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Nouvel immeuble
                </a>
            </div>
        </div>

        <!-- Alerte abonnement -->
        @if(!$promoteur->hasValidSubscription())
        <div class="alert-warning">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Abonnement expiré</strong>
                <p>Renouvelez votre abonnement pour continuer à utiliser tous les services</p>
            </div>
            <a href="{{ route('promoteur.abonnements.index') }}" class="btn-warning">Renouveler</a>
        </div>
        @endif

        <!-- Statistiques principales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_immeubles'] }}</div>
                <div class="stat-label">Immeubles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_appartements'] }}</div>
                <div class="stat-label">Appartements</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['syndics_actifs'] }}</div>
                <div class="stat-label">Syndics assignés</div>
            </div>
            <div class="stat-card {{ !$promoteur->hasValidSubscription() ? 'stat-danger' : '' }}">
                <div class="stat-status">
                    {{ $promoteur->hasValidSubscription() ? 'Actif' : 'Expiré' }}
                </div>
                <div class="stat-label">
                    Abonnement
                    @if($promoteur->hasValidSubscription() && $stats['jours_restants'] > 0)
                        <span class="stat-sublabel">{{ $stats['jours_restants'] }} jours restants</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <!-- Mes immeubles -->
            <div class="section">
                <div class="section-header">
                    <h2>Mes immeubles</h2>
                    <a href="{{ route('promoteur.immeubles.index') }}" class="btn-link">Voir tous</a>
                </div>
                
                @if($immeubles->count() > 0)
                    <div class="immeubles-list">
                        @foreach($immeubles->take(4) as $immeuble)
                        <div class="immeuble-item">
                            <div class="immeuble-main">
                                <h3>{{ $immeuble->nom }}</h3>
                                <p>{{ $immeuble->adresse }}</p>
                                
                                <div class="immeuble-stats">
                                    <span class="stat-pill">{{ $immeuble->blocs->count() }} blocs</span>
                                    <span class="stat-pill">{{ $immeuble->blocs->sum('nombre_appartement') }} appts</span>
                                    @if($immeuble->syndic)
                                        <span class="stat-pill success">Syndic assigné</span>
                                    @else
                                        <span class="stat-pill warning">Sans syndic</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="immeuble-actions">
                                @if(!$immeuble->syndic)
                                <a href="{{ route('promoteur.syndics.assign') }}" class="btn-sm btn-warning">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                @endif
                                <a href="{{ route('promoteur.immeubles.index') }}" class="btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-building"></i>
                        <h3>Aucun immeuble</h3>
                        <p>Créez votre premier immeuble pour commencer</p>
                        <a href="{{ route('promoteur.immeubles.create') }}" class="btn-primary">Créer un immeuble</a>
                    </div>
                @endif
            </div>

            <!-- Actions rapides -->
            <div class="section">
                <div class="section-header">
                    <h2>Actions rapides</h2>
                </div>
                
                <div class="actions-grid">
                    <a href="{{ route('promoteur.immeubles.create') }}" class="action-card">
                        <div class="action-icon primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-content">
                            <h4>Nouvel immeuble</h4>
                            <p>Créer un nouvel immeuble</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('promoteur.syndics.assign') }}" class="action-card">
                        <div class="action-icon orange">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="action-content">
                            <h4>Assigner syndic</h4>
                            <p>Gérer les syndics</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('promoteur.rapports.index') }}" class="action-card">
                        <div class="action-icon blue">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="action-content">
                            <h4>Rapports</h4>
                            <p>Voir les statistiques</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('promoteur.abonnements.index') }}" class="action-card">
                        <div class="action-icon green">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="action-content">
                            <h4>Abonnement</h4>
                            <p>Gérer l'abonnement</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1a202c;
            line-height: 1.6;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* En-tête */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .header-left h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .header-left p {
            color: #718096;
            font-size: 1rem;
        }

        .btn-primary {
            background: #3182ce;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #2c5aa0;
            transform: translateY(-1px);
            color: white;
        }

        /* Alerte */
        .alert-warning {
            background: #fef5e7;
            border: 1px solid #f6e05e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-icon {
            color: #d69e2e;
            font-size: 1.25rem;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            color: #744210;
            display: block;
            margin-bottom: 0.25rem;
        }

        .alert-content p {
            color: #975a16;
            font-size: 0.9rem;
        }

        .btn-warning {
            background: #ed8936;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-warning:hover {
            background: #dd6b20;
            color: white;
        }

        /* Statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-card.stat-danger {
            border-color: #fc8181;
            background: #fef5f5;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .stat-status {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .stat-card.stat-danger .stat-status {
            color: #e53e3e;
        }

        .stat-label {
            color: #718096;
            font-weight: 500;
        }

        .stat-sublabel {
            display: block;
            font-size: 0.8rem;
            color: #a0aec0;
            margin-top: 0.25rem;
        }

        /* Contenu principal */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 3rem;
        }

        .section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
        }

        .btn-link {
            color: #3182ce;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-link:hover {
            color: #2c5aa0;
        }

        /* Liste des immeubles */
        .immeubles-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .immeuble-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .immeuble-item:hover {
            background: #edf2f7;
            transform: translateY(-1px);
        }

        .immeuble-main h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .immeuble-main p {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .immeuble-stats {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stat-pill {
            background: #edf2f7;
            color: #4a5568;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stat-pill.success {
            background: #c6f6d5;
            color: #276749;
        }

        .stat-pill.warning {
            background: #fef5e7;
            color: #975a16;
        }

        .immeuble-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: white;
            color: #718096;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            background: #f7fafc;
            color: #4a5568;
        }

        .btn-sm.btn-warning {
            background: #fed7aa;
            border-color: #fdba74;
            color: #9a3412;
        }

        .btn-sm.btn-warning:hover {
            background: #fed7aa;
            color: #7c2d12;
        }

        /* État vide */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #718096;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #cbd5e0;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #4a5568;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        /* Actions rapides */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        .action-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .action-card:hover {
            background: #edf2f7;
            transform: translateY(-1px);
            color: inherit;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .action-icon.primary { background: #3182ce; }
        .action-icon.orange { background: #ed8936; }
        .action-icon.blue { background: #3182ce; }
        .action-icon.green { background: #38a169; }

        .action-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .action-content p {
            font-size: 0.9rem;
            color: #718096;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .immeuble-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .alert-warning {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection