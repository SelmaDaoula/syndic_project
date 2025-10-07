@extends('layouts.app')

@section('title', 'Mes Immeubles - Promoteur')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header avec animation d'entrée -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center fade-in-up">
                    <div>
                        <h1 class="page-title">Mes Immeubles</h1>
                        <p class="page-subtitle">{{ $promoteur->nom ?? '' }} {{ $promoteur->prenom ?? '' }}</p>
                    </div>
                    @if($immeuble)
                        <a href="{{ route('promoteur.blocs.create') }}" class="btn btn-primary-modern pulse-animation">
                            <i class="fas fa-plus me-2"></i>Ajouter Bloc
                        </a>
                    @endif
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

        @if($errors->any())
            <div class="alert alert-danger-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if($immeuble)
            <!-- Vue en deux colonnes avec animations -->
            <div class="row g-4">
                <!-- Colonne gauche : Informations immeuble -->
                <div class="col-lg-4">
                    <div class="building-summary-card fade-in-left">
                        <div class="building-info">
                            <div class="building-icon">
                                <i class="fas fa-building icon-bounce"></i>
                            </div>
                            <div>
                                <h3 class="building-name">{{ $immeuble->nom ?? 'Immeuble' }}</h3>
                                <p class="building-address">{{ $immeuble->adresse }}</p>
                                <span class="status-badge status-{{ $immeuble->statut == 'actif' ? 'success' : 'warning' }} pulse-slow">
                                    {{ ucfirst($immeuble->statut ?? 'Inactif') }}
                                </span>
                            </div>
                        </div>

                        <!-- Statistiques compactes avec hover effects -->
                        <div class="stats-compact">
                            <div class="stat-item hover-lift" data-tooltip="Nombre total de blocs">
                                <span class="stat-number counter-animation">{{ $stats['total_blocs'] ?? 0 }}</span>
                                <span class="stat-label">Blocs</span>
                            </div>
                            <div class="stat-item hover-lift" data-tooltip="Nombre total d'appartements">
                                <span class="stat-number counter-animation">{{ $stats['total_appartements'] ?? 0 }}</span>
                                <span class="stat-label">Appartements</span>
                            </div>
                            <div class="stat-item hover-lift" data-tooltip="Surface totale construite">
                                <span class="stat-number counter-animation">{{ number_format($stats['surface_totale'] ?? 0) }}</span>
                                <span class="stat-label">m²</span>
                            </div>
                            <div class="stat-item hover-lift" data-tooltip="Année de construction">
                                <span class="stat-number">{{ $stats['annee_construction'] ?? 'N/A' }}</span>
                                <span class="stat-label">Année</span>
                            </div>
                        </div>

                        <!-- Actions rapides avec effets améliorés -->
                        <div class="quick-actions">
                            <a href="{{ route('promoteur.immeubles.export-pdf') }}" class="action-btn">
                                <div class="action-icon-wrapper">
                                    <i class="fas fa-download"></i>
                                </div>
                                <span>Exporter</span>
                            </a>
                            <button class="action-btn">
                                <div class="action-icon-wrapper">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span>Stats</span>
                            </button>
                            <a href="{{ route('promoteur.syndics.assign') }}" class="action-btn">
                                <div class="action-icon-wrapper">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <span>Syndic</span>
                            </a>
                            <button class="action-btn">
                                <div class="action-icon-wrapper">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <span>Modifier</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite : Liste des blocs -->
                <div class="col-lg-8">
                    <div class="blocs-section fade-in-right">
                        <div class="section-header">
                            <h3 class="section-title">
                                <i class="fas fa-th-large me-2 text-primary"></i>
                                Liste des Blocs
                            </h3>
                            <span class="blocs-count">{{ $immeuble->blocs->count() }} blocs</span>
                        </div>

                        @if($immeuble->blocs->count() > 0)
                            <div class="blocs-list">
                                @foreach($immeuble->blocs as $index => $bloc)
                                    <div class="bloc-item fade-in-item" style="animation-delay: {{ $index * 100 }}ms">
                                        <div class="bloc-main-info">
                                            <div class="bloc-badge">
                                                {{ substr($bloc->nom, -1) }}
                                            </div>
                                            <div class="bloc-details">
                                                <h5 class="bloc-name">{{ $bloc->nom }}</h5>
                                                <div class="bloc-stats">
                                                    <span class="stat-chip">
                                                        <i class="fas fa-door-open me-1"></i>
                                                        {{ $bloc->nombre_appartement ?? 0 }} appts
                                                    </span>
                                                    <span class="stat-chip">
                                                        <i class="fas fa-layer-group me-1"></i>
                                                        {{ $bloc->nombre_etages ?? 0 }} étages
                                                    </span>
                                                    <span class="stat-chip">
                                                        <i class="fas fa-expand me-1"></i>
                                                        {{ number_format($bloc->surface_totale ?? 0) }} m²
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bloc-status">
                                            <div class="generation-status {{ $bloc->appartements->count() > 0 ? 'generated' : 'pending' }}">
                                                <div class="status-icon">
                                                    <i class="fas fa-{{ $bloc->appartements->count() > 0 ? 'check' : 'clock' }}"></i>
                                                </div>
                                                <span>{{ $bloc->appartements->count() }} générés</span>
                                            </div>
                                        </div>

                                        <div class="bloc-actions">
                                            <a href="{{ route('promoteur.appartements.index', ['bloc_id' => $bloc->id]) }}" 
                                               class="btn btn-outline-sm" data-tooltip="Voir les appartements">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($bloc->appartements->count() == 0)
                                                <button onclick="generateApartments({{ $bloc->id }})" 
                                                        class="btn btn-success-sm" data-tooltip="Générer les appartements">
                                                    <i class="fas fa-magic"></i>
                                                </button>
                                            @else
                                                <button onclick="regenerateApartments({{ $bloc->id }})" 
                                                        class="btn btn-warning-sm" data-tooltip="Regénérer les appartements">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-th-large floating-icon"></i>
                                </div>
                                <h4 class="empty-title">Aucun bloc trouvé</h4>
                                <p class="empty-description">Commencez par ajouter des blocs à votre immeuble.</p>
                                <a href="{{ route('promoteur.blocs.create') }}" class="btn btn-primary-modern">
                                    <i class="fas fa-plus me-2"></i>Ajouter un Bloc
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @else
            <!-- État vide (pas d'immeuble) avec animation -->
            <div class="empty-state-large fade-in-up">
                <div class="empty-icon-large">
                    <i class="fas fa-building floating-icon"></i>
                </div>
                <h3 class="empty-title-large">Aucun immeuble trouvé</h3>
                <p class="empty-description-large">Vous n'avez pas encore d'immeuble assigné. Contactez l'administrateur pour plus d'informations.</p>
                <button class="btn btn-primary-modern btn-lg pulse-animation">
                    <i class="fas fa-phone me-2"></i>Contacter Support
                </button>
            </div>
        @endif
    </div>

    <!-- JavaScript inchangé -->
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
        });
    </script>

    <!-- CSS amélioré avec nouvelles animations -->
    <style>
        /* Base inchangée */
        body {
            background-color: #FFEBD0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animations et transitions */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in-left {
            animation: fadeInLeft 0.6s ease-out;
        }

        .fade-in-right {
            animation: fadeInRight 0.6s ease-out;
        }

        .fade-in-item {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        .pulse-slow {
            animation: pulseSlow 3s infinite;
        }

        .icon-bounce:hover {
            animation: bounce 1s ease infinite;
        }

        .floating-icon {
            animation: floating 3s ease-in-out infinite;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Header */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #173B61;
            margin: 0;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            color: #7697A0;
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Boutons améliorés */
        .btn-primary-modern {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 137, 22, 0.4);
            color: white;
        }

        /* Alertes améliorées */
        .alert-success-modern, .alert-danger-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .alert-success-modern {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .alert-danger-modern {
            background: rgba(240, 5, 15, 0.1);
            color: #7f1d1d;
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

        .alert-icon-wrapper.error {
            background: rgba(240, 5, 15, 0.2);
        }

        /* Card résumé immeuble améliorée */
        .building-summary-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            height: fit-content;
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
            background: linear-gradient(135deg, #173B61 0%, #17616E 50%, #FD8916 100%);
        }

        .building-info {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .building-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
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
            font-size: 1.3rem;
            font-weight: 700;
            color: #173B61;
            margin: 0 0 0.5rem 0;
        }

        .building-address {
            color: #7697A0;
            margin: 0 0 0.75rem 0;
            font-size: 0.9rem;
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

        /* Statistiques compactes améliorées */
        .stats-compact {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.25rem;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            border-radius: 15px;
            cursor: pointer;
            position: relative;
        }

        .stat-item[data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            background: #173B61;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1000;
        }

        .stat-number {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: #173B61;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #7697A0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Actions rapides améliorées */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #173B61;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.05), rgba(23, 97, 110, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .action-btn:hover::before {
            opacity: 1;
        }

        .action-btn:hover {
            background: white;
            border-color: #173B61;
            color: #173B61;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .action-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
        }

        .action-btn:hover .action-icon-wrapper {
            transform: scale(1.1);
        }

        .action-btn i {
            font-size: 1.2rem;
        }

        .action-btn span {
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Section blocs améliorée */
        .blocs-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .blocs-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 50%, #FD8916 100%);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #173B61;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .text-primary {
            color: #FD8916 !important;
        }

        .blocs-count {
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            color: #173B61;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid rgba(253, 137, 22, 0.2);
        }

        /* Liste des blocs améliorée */
        .blocs-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .bloc-item {
            display: flex;
            align-items: center;
            padding: 1.75rem;
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
            border-radius: 15px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .bloc-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #173B61, #17616E);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .bloc-item:hover::before {
            transform: scaleY(1);
        }

        .bloc-item:hover {
            background: white;
            border-color: #173B61;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .bloc-main-info {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .bloc-badge {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
            font-size: 1.1rem;
        }

        .bloc-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #173B61;
            margin: 0 0 0.5rem 0;
        }

        .bloc-stats {
            display: flex;
            gap: 0.75rem;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }

        .stat-chip {
            display: inline-flex;
            align-items: center;
            background: rgba(23, 59, 97, 0.1);
            color: #173B61;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .bloc-status {
            margin-right: 1rem;
        }

        .generation-status {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .generation-status.generated {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.1));
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .generation-status.pending {
            background: linear-gradient(135deg, rgba(253, 137, 22, 0.15), rgba(253, 137, 22, 0.1));
            color: #92400e;
            border: 1px solid rgba(253, 137, 22, 0.3);
        }

        .status-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .generated .status-icon {
            background: rgba(16, 185, 129, 0.2);
        }

        .pending .status-icon {
            background: rgba(253, 137, 22, 0.2);
        }

        .bloc-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Petits boutons améliorés */
        .btn-outline-sm, .btn-success-sm, .btn-warning-sm {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-outline-sm::before, .btn-success-sm::before, .btn-warning-sm::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .btn-outline-sm:hover::before, .btn-success-sm:hover::before, .btn-warning-sm:hover::before {
            width: 100px;
            height: 100px;
        }

        .btn-outline-sm {
            background: white;
            color: #173B61;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn-outline-sm:hover {
            background: #173B61;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
        }

        .btn-success-sm {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-warning-sm {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
        }

        .btn-warning-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(253, 137, 22, 0.4);
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
            background: #173B61;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        [data-tooltip]:hover::before {
            content: '';
            position: absolute;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #173B61;
            z-index: 1000;
        }

        /* États vides améliorés */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-icon i {
            font-size: 1.8rem;
            color: #7697A0;
        }

        .empty-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #173B61;
            margin-bottom: 0.75rem;
        }

        .empty-description {
            color: #7697A0;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .empty-state-large {
            text-align: center;
            padding: 4rem 1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .empty-icon-large {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .empty-icon-large i {
            font-size: 2.5rem;
            color: #7697A0;
        }

        .empty-title-large {
            font-size: 1.75rem;
            font-weight: 700;
            color: #173B61;
            margin-bottom: 1rem;
        }

        .empty-description-large {
            color: #7697A0;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            font-weight: 500;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsive amélioré */
        @media (max-width: 991px) {
            .stats-compact {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .bloc-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .bloc-main-info {
                justify-content: flex-start;
            }
            
            .bloc-status {
                margin-right: 0;
                text-align: center;
            }
            
            .bloc-actions {
                justify-content: center;
            }

            .page-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .building-info {
                flex-direction: column;
                text-align: center;
            }
            
            .building-icon {
                margin: 0 auto 1rem auto;
            }
            
            .bloc-stats {
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .stats-compact {
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .stat-item {
                padding: 1rem;
            }

            .stat-number {
                font-size: 1.4rem;
            }

            .building-summary-card, .blocs-section {
                padding: 1.5rem;
            }

            .empty-state-large {
                padding: 3rem 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Améliorations supplémentaires */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Effet de shimmer pour les éléments en chargement */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Amélioration de l'accessibilité */
        .btn:focus, .action-btn:focus {
            outline: 2px solid #FD8916;
            outline-offset: 2px;
        }

        /* Effet de gradient sur les bordures */
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 15px;
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 15px;
            padding: 2px;
            background: linear-gradient(135deg, #173B61, #17616E, #FD8916);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
        }

        /* Animation de loading pour les boutons */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Amélioration des transitions de page */
        .page-transition {
            transition: all 0.3s ease-in-out;
        }

        /* Effet de parallaxe subtile */
        .parallax-element {
            transform: translateZ(0);
            will-change: transform;
        }
    </style>
@endsection