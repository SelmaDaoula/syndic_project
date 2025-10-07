@extends('layouts.app')

@section('title', 'Dashboard Syndic')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header moderne avec animation d'entrée -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-card fade-in-up">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-user-tie icon-bounce"></i>
                            </div>
                            <div class="ms-3">
                                <h1 class="page-title">Dashboard Syndic</h1>
                                <p class="page-subtitle">{{ Auth::user()->name }} - Gestion de l'immeuble</p>
                            </div>
                        </div>
                        @if($immeuble)
                            <div class="header-actions">
                                <a href="{{ route('syndic.tickets.index') }}" class="btn btn-primary-modern pulse-animation">
                                    <i class="fas fa-ticket-alt me-2"></i>Gérer Tickets
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages avec animations -->
        @if(session('success'))
            <div class="alert alert-success-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper info">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">{{ session('info') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if($immeuble)
            <!-- Informations de l'immeuble géré -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="building-summary-card fade-in-left">
                        <div class="building-info">
                            <div class="building-icon">
                                <i class="fas fa-building icon-bounce"></i>
                            </div>
                            <div>
                                <h3 class="building-name">{{ $immeuble->nom }}</h3>
                                <p class="building-address">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $immeuble->adresse }}
                                </p>
                                <span class="status-badge status-{{ $immeuble->statut == 'actif' ? 'success' : 'warning' }} pulse-slow">
                                    {{ ucfirst($immeuble->statut ?? 'Inactif') }}
                                </span>
                            </div>
                        </div>

                        <!-- Statistiques rapides -->
                        <div class="stats-quick">
                            <div class="stat-quick-item hover-lift" data-tooltip="Nombre total d'appartements">
                                <span class="stat-number counter-animation">{{ $stats['total_appartements'] }}</span>
                                <span class="stat-label">Appartements</span>
                            </div>
                            <div class="stat-quick-item hover-lift" data-tooltip="Appartements occupés">
                                <span class="stat-number counter-animation">{{ $stats['appartements_occupes'] }}</span>
                                <span class="stat-label">Occupés</span>
                            </div>
                            <div class="stat-quick-item hover-lift" data-tooltip="Tickets ouverts">
                                <span class="stat-number counter-animation">{{ $stats['tickets_ouverts'] }}</span>
                                <span class="stat-label">Tickets</span>
                            </div>
                            <div class="stat-quick-item hover-lift" data-tooltip="Dépenses du mois">
                                <span class="stat-number counter-animation">{{ number_format($stats['depenses_mois'], 0) }}</span>
                                <span class="stat-label">TND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques principales détaillées -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="metric-card fade-in-item metric-primary" style="animation-delay: 0ms">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="metric-trend up">
                                <i class="fas fa-arrow-up"></i>
                                <span>{{ $stats['total_appartements'] > 0 ? round(($stats['appartements_occupes'] / $stats['total_appartements']) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-value">{{ $stats['appartements_occupes'] }}/{{ $stats['total_appartements'] }}</h3>
                            <p class="metric-label">Appartements Occupés</p>
                            <div class="metric-progress">
                                <div class="progress-bar" style="width: {{ $stats['total_appartements'] > 0 ? ($stats['appartements_occupes'] / $stats['total_appartements']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="metric-card fade-in-item metric-warning" style="animation-delay: 100ms">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="metric-trend {{ $stats['tickets_ouverts'] > 0 ? 'down' : 'neutral' }}">
                                <i class="fas fa-{{ $stats['tickets_ouverts'] > 0 ? 'exclamation' : 'check' }}"></i>
                                <span>{{ $stats['tickets_en_cours'] }}</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-value">{{ $stats['tickets_ouverts'] }}</h3>
                            <p class="metric-label">Tickets Ouverts</p>
                            <p class="metric-subtitle">{{ $stats['tickets_en_cours'] }} en cours de traitement</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="metric-card fade-in-item metric-info" style="animation-delay: 200ms">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="metric-trend up">
                                <i class="fas fa-user-plus"></i>
                                <span>{{ $stats['total_locataires'] }}</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-value">{{ $stats['total_proprietaires'] }}</h3>
                            <p class="metric-label">Propriétaires</p>
                            <p class="metric-subtitle">{{ $stats['total_locataires'] }} locataires</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="metric-card fade-in-item metric-success" style="animation-delay: 300ms">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                            <div class="metric-trend neutral">
                                <i class="fas fa-calendar"></i>
                                <span>{{ now()->format('M') }}</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <h3 class="metric-value">{{ number_format($stats['depenses_mois'], 0, ',', ' ') }}</h3>
                            <p class="metric-label">Dépenses TND</p>
                            <p class="metric-subtitle">{{ now()->format('F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal en deux colonnes -->
            <div class="row g-4">
                <!-- Colonne gauche : Derniers tickets -->
                <div class="col-lg-8">
                    <div class="content-card fade-in-left">
                        <div class="content-header">
                            <div class="content-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <h3 class="content-title">Derniers Tickets</h3>
                                <p class="content-subtitle">Incidents et demandes récentes</p>
                            </div>
                            <a href="{{ route('syndic.tickets.index') }}" class="btn btn-outline-modern">
                                Voir tous
                            </a>
                        </div>

                        <div class="content-body">
                            @forelse($derniers_tickets as $index => $ticket)
                                <div class="ticket-item fade-in-item" style="animation-delay: {{ $index * 50 }}ms">
                                    <div class="ticket-main">
                                        <div class="ticket-priority priority-{{ $ticket->priority ?? 'medium' }}">
                                            <i class="fas fa-flag"></i>
                                        </div>
                                        <div class="ticket-details">
                                            <h5 class="ticket-title">
                                                <a href="{{ route('syndic.tickets.show', $ticket) }}">
                                                    {{ $ticket->sujet ?? 'Ticket sans sujet' }}
                                                </a>
                                            </h5>
                                            <p class="ticket-description">{{ Str::limit($ticket->description ?? 'Pas de description', 80) }}</p>
                                            <div class="ticket-meta">
                                                <span class="meta-item">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $ticket->user->name ?? 'Utilisateur inconnu' }}
                                                </span>
                                                <span class="meta-item">
                                                    <i class="fas fa-home me-1"></i>
                                                    Apt. {{ $ticket->appartement->numero ?? 'N/A' }}
                                                </span>
                                                <span class="meta-item">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $ticket->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ticket-status">
                                        @php
                                            $statusColors = [
                                                'ouvert' => 'danger',
                                                'en_cours' => 'warning',
                                                'resolu' => 'success',
                                                'ferme' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="status-badge status-{{ $statusColors[$ticket->status ?? 'ouvert'] }}">
                                            {{ ucfirst($ticket->status ?? 'ouvert') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-ticket-alt floating-icon"></i>
                                    </div>
                                    <h4 class="empty-title">Aucun ticket récent</h4>
                                    <p class="empty-description">Tous les tickets sont traités ou aucun incident n'a été signalé.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Actions rapides et informations -->
                <div class="col-lg-4">
                    <!-- Actions rapides -->
                    <div class="content-card fade-in-right mb-4">
                        <div class="content-header-simple">
                            <h3 class="content-title-simple">
                                <i class="fas fa-bolt me-2 text-primary"></i>
                                Actions Rapides
                            </h3>
                        </div>
                        <div class="content-body">
                            <div class="quick-actions-grid">
                                <a href="{{ route('syndic.tickets.index') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <span class="action-label">Tickets</span>
                                    @if($stats['tickets_ouverts'] > 0)
                                        <span class="action-badge">{{ $stats['tickets_ouverts'] }}</span>
                                    @endif
                                </a>

                                <a href="{{ route('syndic.paiements.index') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <span class="action-label">Paiements</span>
                                </a>

                                <a href="{{ route('syndic.residents.index') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <span class="action-label">Résidents</span>
                                </a>

                                <a href="{{ route('syndic.depenses.create') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <span class="action-label">Dépense</span>
                                </a>

                                <a href="{{ route('syndic.appartements.index') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <span class="action-label">Appartements</span>
                                </a>

                                <a href="{{ route('syndic.rapports.index') }}" class="quick-action-btn">
                                    <div class="action-icon-wrapper">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <span class="action-label">Rapports</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Paiements récents -->
                    <div class="content-card fade-in-right">
                        <div class="content-header-simple">
                            <h3 class="content-title-simple">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                Paiements Récents
                            </h3>
                        </div>
                        <div class="content-body">
                            @forelse($paiements_recents as $paiement)
                                <div class="payment-item">
                                    <div class="payment-user">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-info">
                                            <h6 class="user-name">{{ $paiement->user->name ?? 'Utilisateur inconnu' }}</h6>
                                            <small class="user-apt">Apt. {{ $paiement->appartement->numero ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <div class="payment-amount">
                                        <strong class="amount">{{ number_format($paiement->montant ?? 0, 2) }} TND</strong>
                                        <small class="payment-date">{{ $paiement->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state-small">
                                    <i class="fas fa-money-bill-wave fa-2x mb-2 text-muted"></i>
                                    <p class="mb-0 text-muted">Aucun paiement récent</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- État vide : Aucun immeuble assigné -->
            <div class="empty-state-large fade-in-up">
                <div class="empty-icon-large">
                    <i class="fas fa-building floating-icon"></i>
                </div>
                <h3 class="empty-title-large">Aucun immeuble assigné</h3>
                <p class="empty-description-large">
                    Votre compte syndic est créé mais aucun immeuble ne vous a encore été assigné par un promoteur.
                    Contactez l'administration ou attendez qu'un promoteur vous assigne un immeuble à gérer.
                </p>
                <div class="empty-actions">
                    <a href="{{ route('profile.show') }}" class="btn btn-primary-modern me-3">
                        <i class="fas fa-user me-2"></i>Mon Profil
                    </a>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-modern">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </a>
                </div>
                <div class="empty-help">
                    <p class="help-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Un promoteur doit vous assigner un immeuble pour activer votre interface de gestion.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript pour animations et interactions -->
    <script>
        // Animation des compteurs au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter-animation');
            counters.forEach(counter => {
                const finalValue = parseInt(counter.textContent);
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 30);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        counter.textContent = finalValue.toLocaleString();
                        clearInterval(timer);
                    } else {
                        counter.textContent = currentValue.toLocaleString();
                    }
                }, 50);
            });

            // Auto-refresh des statistiques toutes les 5 minutes (optionnel)
            // setInterval(() => location.reload(), 300000);
        });
    </script>

    <!-- CSS complet avec le style moderne -->
    <style>
        /* Variables CSS */
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

        /* Base */
        body {
            background-color: var(--accent-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Classes d'animation */
        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-left { animation: fadeInLeft 0.6s ease-out; }
        .fade-in-right { animation: fadeInRight 0.6s ease-out; }
        .fade-in-item { 
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .slide-down { animation: slideDown 0.4s ease-out; }
        .pulse-animation { animation: pulse 2s infinite; }
        .pulse-slow { animation: pulseSlow 3s infinite; }
        .icon-bounce:hover { animation: bounce 1s ease infinite; }
        .floating-icon { animation: floating 3s ease-in-out infinite; }
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Header moderne */
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
            font-size: 2rem;
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

        /* Boutons modernes */
        .btn-primary-modern {
            background: linear-gradient(135deg, var(--accent) 0%, #FF9933 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
            text-decoration: none;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 137, 22, 0.4);
            color: white;
        }

        .btn-outline-modern {
            background: white;
            border: 2px solid var(--primary-light);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-outline-modern:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Alertes modernisées */
        .alert-success-modern, .alert-info-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 1.25rem;
        }

        .alert-success-modern {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .alert-info-modern {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
        }

        .alert-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-icon-wrapper.success {
            background: rgba(16, 185, 129, 0.2);
        }

        .alert-icon-wrapper.info {
            background: rgba(59, 130, 246, 0.2);
        }

        /* Card résumé immeuble */
        .building-summary-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .building-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
        }

        .building-info {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .building-icon {
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

        .building-icon i {
            font-size: 22px;
            color: white;
        }

        .building-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
        }

        .building-address {
            color: var(--primary-light);
            margin: 0 0 0.75rem 0;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-warning {
            background: linear-gradient(135deg, rgba(253, 137, 22, 0.2), rgba(253, 137, 22, 0.1));
            color: #92400e;
            border: 1px solid rgba(253, 137, 22, 0.3);
        }

        /* Statistiques rapides */
        .stats-quick {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .stat-quick-item {
            text-align: center;
            padding: 1.25rem;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 15px;
            cursor: pointer;
            position: relative;
            border: 1px solid rgba(253, 137, 22, 0.1);
        }

        .stat-number {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--primary-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Cards métriques */
        .metric-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .metric-primary::before { background: var(--primary); }
        .metric-warning::before { background: var(--warning); }
        .metric-info::before { background: var(--info); }
        .metric-success::before { background: var(--success); }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .metric-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .metric-primary .metric-icon { background: var(--primary); }
        .metric-warning .metric-icon { background: var(--warning); }
        .metric-info .metric-icon { background: var(--info); }
        .metric-success .metric-icon { background: var(--success); }

        .metric-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .metric-trend.up {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .metric-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
        }

        .metric-trend.neutral {
            background: rgba(107, 114, 128, 0.1);
            color: #374151;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
        }

        .metric-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .metric-subtitle {
            font-size: 0.8rem;
            color: var(--primary-light);
            margin: 0;
        }

        .metric-progress {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.75rem;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 3px;
            transition: width 1s ease;
        }

        /* Cards de contenu */
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }

        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
        }

        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2rem 2rem 1.5rem 2rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .content-header > div:first-child {
            display: flex;
            align-items: center;
        }

        .content-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.1rem;
        }

        .content-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .content-subtitle {
            color: var(--primary-light);
            margin: 0;
            font-size: 0.9rem;
        }

        .content-header-simple {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .content-title-simple {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .text-primary { color: var(--accent) !important; }

        .content-body {
            padding: 2rem;
        }

        /* Items de ticket */
        .ticket-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .ticket-item:hover {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .ticket-main {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .ticket-priority {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.8rem;
        }

        .priority-low { background: rgba(16, 185, 129, 0.2); color: #065f46; }
        .priority-medium { background: rgba(245, 158, 11, 0.2); color: #92400e; }
        .priority-high { background: rgba(239, 68, 68, 0.2); color: #7f1d1d; }
        .priority-urgent { background: rgba(107, 114, 128, 0.2); color: #374151; }

        .ticket-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
        }

        .ticket-title a {
            color: inherit;
            text-decoration: none;
        }

        .ticket-title a:hover {
            color: var(--accent);
        }

        .ticket-description {
            font-size: 0.85rem;
            color: var(--primary-light);
            margin: 0 0 0.75rem 0;
        }

        .ticket-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .meta-item {
            font-size: 0.75rem;
            color: var(--primary-light);
            display: flex;
            align-items: center;
        }

        /* Actions rapides grid */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem 0.75rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: var(--primary);
            transition: all 0.3s ease;
            position: relative;
        }

        .quick-action-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 59, 97, 0.2);
        }

        .action-icon-wrapper {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
        }

        .quick-action-btn:hover .action-icon-wrapper {
            transform: scale(1.1);
            background: rgba(255,255,255,0.2);
        }

        .action-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }

        .action-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Items de paiement */
        .payment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-user {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            margin-right: 0.75rem;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .user-apt {
            font-size: 0.75rem;
            color: var(--primary-light);
        }

        .payment-amount {
            text-align: right;
        }

        .amount {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--success);
            display: block;
            margin-bottom: 0.25rem;
        }

        .payment-date {
            font-size: 0.75rem;
            color: var(--primary-light);
        }

        /* États vides */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-icon i {
            font-size: 1.8rem;
            color: var(--primary-light);
        }

        .empty-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }

        .empty-description {
            color: var(--primary-light);
            margin-bottom: 0;
            font-weight: 500;
        }

        .empty-state-small {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-state-large {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .empty-icon-large {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .empty-icon-large i {
            font-size: 2.5rem;
            color: var(--primary-light);
        }

        .empty-title-large {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .empty-description-large {
            color: var(--primary-light);
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            font-weight: 500;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .empty-actions {
            margin-bottom: 2rem;
        }

        .empty-help {
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .help-text {
            color: var(--primary-light);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Tooltips */
        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-quick {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 991px) {
            .building-info {
                flex-direction: column;
                text-align: center;
            }
            
            .building-icon {
                margin: 0 auto 1rem auto;
            }
            
            .stats-quick {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }
            
            .ticket-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .ticket-main {
                justify-content: flex-start;
            }

            .page-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .header-card .d-flex {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .header-icon {
                margin: 0 auto;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .stats-quick {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .building-summary-card,
            .content-card {
                padding: 1.5rem;
            }
            
            .content-header,
            .content-body {
                padding: 1.5rem;
            }
            
            .content-header-simple {
                padding: 1.25rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .empty-state-large {
                padding: 3rem 1.5rem;
            }
        }
    </style>
@endsection