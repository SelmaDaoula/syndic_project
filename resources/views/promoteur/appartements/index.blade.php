@extends('layouts.app')

@section('title', 'Appartements')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header moderne avec animation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-card fade-in-up">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div class="ms-3">
                                <h1 class="page-title">Liste des Appartements</h1>
                                <p class="page-subtitle">Gestion des appartements générés</p>
                            </div>
                        </div>
                        <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux Immeubles
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages modernisés -->
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

        <!-- Statistiques modernisées -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card total fade-in-item" style="animation-delay: 0ms">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Total</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->total() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card libre fade-in-item" style="animation-delay: 100ms">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Libres</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->where('statut', 'libre')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card occupe fade-in-item" style="animation-delay: 200ms">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Occupés</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->where('statut', 'occupe')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card travaux fade-in-item" style="animation-delay: 300ms">
                    <div class="stat-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Travaux</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->where('statut', 'travaux')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card reserve fade-in-item" style="animation-delay: 400ms">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Réservés</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->where('statut', 'reserve')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stat-card maintenance fade-in-item" style="animation-delay: 500ms">
                    <div class="stat-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Maintenance</h6>
                        <h3 class="stat-number counter-animation">{{ $appartements->where('statut', 'maintenance')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des appartements modernisée -->
        <div class="table-card fade-in-up">
            <div class="table-header">
                <div class="header-content">
                    <div class="header-icon-small">
                        <i class="fas fa-list"></i>
                    </div>
                    <h5 class="table-title">Appartements générés automatiquement</h5>
                </div>
            </div>
            
            <div class="table-body">
                @if($appartements->count() > 0)
                    <div class="modern-table-container">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Immeuble</th>
                                    <th>Bloc</th>
                                    <th>N° Appartement</th>
                                    <th>Type</th>
                                    <th>Surface</th>
                                    <th>Pièces</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appartements as $index => $appartement)
                                    <tr class="table-row fade-in-item" style="animation-delay: {{ $index * 20 }}ms">
                                        <td>
                                            <div class="cell-content">
                                                <strong class="building-name">{{ $appartement->bloc->immeuble->nom }}</strong>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <span class="bloc-badge">{{ $appartement->bloc->nom }}</span>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <strong class="apartment-number">{{ $appartement->numero }}</strong>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <span class="type-badge">{{ $appartement->type_appartement ?? 'F3' }}</span>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <span class="surface-info">
                                                    <i class="fas fa-expand me-1"></i>
                                                    {{ $appartement->surface }} m²
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <span class="pieces-info">
                                                    <i class="fas fa-th-large me-1"></i>
                                                    {{ $appartement->nombre_pieces }} pièces
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                @switch($appartement->statut)
                                                    @case('libre')
                                                        <span class="status-badge libre">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Libre
                                                        </span>
                                                        @break
                                                    @case('occupe')
                                                        <span class="status-badge occupe">
                                                            <i class="fas fa-user me-1"></i>
                                                            Occupé
                                                        </span>
                                                        @break
                                                    @case('travaux')
                                                        <span class="status-badge travaux">
                                                            <i class="fas fa-tools me-1"></i>
                                                            En travaux
                                                        </span>
                                                        @break
                                                    @case('reserve')
                                                        <span class="status-badge reserve">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Réservé
                                                        </span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="status-badge maintenance">
                                                            <i class="fas fa-wrench me-1"></i>
                                                            Maintenance
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="status-badge default">
                                                            {{ ucfirst($appartement->statut) }}
                                                        </span>
                                                @endswitch
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="cell-content">
                                                <div class="action-buttons">
                                                    <button class="action-btn view" title="Voir" 
                                                            onclick="viewApartment({{ $appartement->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="action-btn edit" title="Modifier"
                                                            onclick="editApartment({{ $appartement->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
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
                            <i class="fas fa-home floating-icon"></i>
                        </div>
                        <h4 class="empty-title">Aucun appartement trouvé</h4>
                        <p class="empty-description">
                            Aucun appartement n'a été généré pour ce bloc.
                        </p>
                        <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-primary-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux Immeubles
                        </a>
                    </div>
                @endif
            </div>

            <!-- Pagination modernisée -->
            @if($appartements->hasPages())
                <div class="table-footer">
                    <div class="pagination-wrapper">
                        {{ $appartements->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- CSS modernisé -->
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

        /* Base */
        body {
            background-color: var(--accent-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-item { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
        .slide-down { animation: slideDown 0.4s ease-out; }
        .floating-icon { animation: floating 3s ease-in-out infinite; }

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

        /* Boutons */
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

        /* Alertes modernisées */
        .alert-success-modern, .alert-danger-modern {
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 1.5rem;
        }

        .alert-success-modern {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .alert-danger-modern {
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
        }

        .alert-icon-wrapper {
            width: 40px;
            height: 40px;
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
            background: rgba(239, 68, 68, 0.2);
        }

        /* Cards statistiques modernisées */
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            transition: height 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .stat-card:hover::before {
            height: 5px;
        }

        .stat-card.total::before { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
        .stat-card.libre::before { background: linear-gradient(135deg, var(--success), #34d399); }
        .stat-card.occupe::before { background: linear-gradient(135deg, var(--warning), #fbbf24); }
        .stat-card.travaux::before { background: linear-gradient(135deg, var(--info), #60a5fa); }
        .stat-card.reserve::before { background: linear-gradient(135deg, var(--secondary), #9ca3af); }
        .stat-card.maintenance::before { background: linear-gradient(135deg, var(--danger), #f87171); }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .total .stat-icon { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
        .libre .stat-icon { background: linear-gradient(135deg, var(--success), #34d399); }
        .occupe .stat-icon { background: linear-gradient(135deg, var(--warning), #fbbf24); }
        .travaux .stat-icon { background: linear-gradient(135deg, var(--info), #60a5fa); }
        .reserve .stat-icon { background: linear-gradient(135deg, var(--secondary), #9ca3af); }
        .maintenance .stat-icon { background: linear-gradient(135deg, var(--danger), #f87171); }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-light);
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            margin: 0;
        }

        /* Table modernisée */
        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
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

        .table-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .header-icon-small {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .table-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .table-body {
            padding: 0;
        }

        .modern-table-container {
            overflow-x: auto;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: var(--primary);
            font-weight: 700;
            padding: 1.25rem 1.5rem;
            border: none;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-table thead th:first-child {
            border-radius: 0;
        }

        .modern-table thead th:last-child {
            border-radius: 0;
        }

        .table-row {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-row:hover {
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.02), rgba(23, 97, 110, 0.02));
            transform: translateX(5px);
        }

        .table-row:hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .table-row td {
            padding: 1.25rem 1.5rem;
            border: none;
            vertical-align: middle;
        }

        .cell-content {
            display: flex;
            align-items: center;
        }

        .building-name {
            color: var(--primary);
            font-weight: 700;
        }

        .bloc-badge {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .apartment-number {
            color: var(--accent);
            font-size: 1.2rem;
            font-weight: 800;
        }

        .type-badge {
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid rgba(253, 137, 22, 0.2);
        }

        .surface-info, .pieces-info {
            color: var(--primary-light);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .status-badge.libre {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .status-badge.occupe {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .status-badge.travaux {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .status-badge.reserve {
            background: rgba(107, 114, 128, 0.1);
            color: #374151;
            border-color: rgba(107, 114, 128, 0.3);
        }

        .status-badge.maintenance {
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .action-btn.view {
            background: white;
            color: var(--primary);
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .action-btn.view:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
        }

        .action-btn.edit {
            background: linear-gradient(135deg, var(--accent) 0%, #FF9933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(253, 137, 22, 0.3);
        }

        .action-btn.edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.4);
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

        /* Pagination */
        .table-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .header-card {
                padding: 1.5rem;
            }
            
            .stat-card {
                padding: 1.25rem;
            }
            
            .stat-number {
                font-size: 1.5rem;
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
            
            .modern-table {
                font-size: 0.85rem;
            }
            
            .modern-table thead th,
            .table-row td {
                padding: 1rem 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .table-card {
                margin: 0 -0.5rem;
            }
            
            .table-header {
                padding: 1rem 1.5rem;
            }
            
            .modern-table-container {
                padding: 0 1rem;
            }
        }

        /* Animation des compteurs */
        .counter-animation {
            transition: color 0.3s ease;
        }

        /* Container principal */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Animation des compteurs au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter-animation');
            counters.forEach(counter => {
                const finalValue = parseInt(counter.textContent);
                if (finalValue > 0) {
                    let currentValue = 0;
                    const increment = Math.ceil(finalValue / 20);
                    const timer = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= finalValue) {
                            counter.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            counter.textContent = currentValue;
                        }
                    }, 50);
                }
            });
        });

        // Fonctions de navigation
        function viewApartment(id) {
            window.location.href = `/promoteur/appartements/${id}`;
        }

        function editApartment(id) {
            window.location.href = `/promoteur/appartements/${id}/edit`;
        }
    </script>
@endsection