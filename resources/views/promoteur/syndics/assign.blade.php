@extends('layouts.app')

@section('title', 'Assigner un Syndic - Promoteur')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header moderne -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-card fade-in-up">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="ms-3">
                                <h1 class="page-title">Assigner un Syndic</h1>
                                <p class="page-subtitle">
                                    Pour l'immeuble: <strong>{{ $immeuble->nom }} - {{ $immeuble->adresse }}</strong>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages avec animations -->
        @if($errors->any())
            <div class="alert alert-danger-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2">Erreurs détectées:</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

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

        <div class="row g-4">
            <!-- Syndic Actuel -->
            <div class="col-lg-6">
                <div class="modern-card fade-in-left">
                    <div class="card-header-modern secondary">
                        <div class="header-content">
                            <div class="header-icon-small">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h5 class="card-title-modern">Syndic Actuel</h5>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        @if($immeuble->syndic_id)
                            <div class="syndic-profile">
                                <div class="profile-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="profile-info">
                                    <h6 class="profile-name">{{ $immeuble->syndic->name ?? 'Nom non disponible' }}</h6>
                                    <p class="profile-email">{{ $immeuble->syndic->email ?? 'Email non disponible' }}</p>
                                    <div class="status-indicator active">
                                        <i class="fas fa-circle"></i>
                                        <span>Assigné et actif</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="action-section">
                                <form action="{{ route('promoteur.syndics.unassign') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger-modern" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir retirer ce syndic ?')">
                                        <i class="fas fa-user-times me-2"></i>Retirer le Syndic
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="empty-state-mini">
                                <div class="empty-icon-mini">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h6 class="empty-title-mini">Aucun syndic assigné</h6>
                                <p class="empty-description-mini">Votre immeuble n'a pas encore de syndic responsable.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigner Nouveau Syndic -->
            <div class="col-lg-6">
                <div class="modern-card fade-in-right">
                    <div class="card-header-modern primary">
                        <div class="header-content">
                            <div class="header-icon-small">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h5 class="card-title-modern">Assigner un Syndic</h5>
                        </div>
                    </div>
                    <div class="card-body-modern">
                        @if($syndicatsDisponibles->count() > 0)
                            <form action="{{ route('promoteur.syndics.assign') }}" method="POST">
                                @csrf
                                <div class="form-group-modern">
                                    <label for="syndic_id" class="form-label-modern">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Sélectionner un Syndic <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select-modern @error('syndic_id') is-invalid @enderror"
                                            id="syndic_id" name="syndic_id" required>
                                        <option value="">-- Choisir un syndic disponible --</option>
                                        @foreach($syndicatsDisponibles as $syndic)
                                            <option value="{{ $syndic->id }}" {{ old('syndic_id') == $syndic->id ? 'selected' : '' }}>
                                                {{ $syndic->nom }} {{ $syndic->prenom }} - {{ $syndic->email }}
                                                @if($syndic->licence_numero)
                                                    (Licence: {{ $syndic->licence_numero }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('syndic_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Information importante avec le design du formulaire créer bloc -->
                                <div class="info-card">
                                    <div class="info-header">
                                        <div class="info-icon">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <h6>À savoir</h6>
                                    </div>
                                    <ul class="info-list">
                                        <li>Le syndic recevra une notification automatique</li>
                                        <li>Il peut accepter ou refuser l'assignation</li>
                                        <li>Vous pouvez modifier l'assignation à tout moment</li>
                                        <li>Un seul syndic peut être assigné par immeuble</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-primary-modern w-100 pulse-animation">
                                    <i class="fas fa-user-plus me-2"></i>Assigner ce Syndic
                                </button>
                            </form>
                        @else
                            <div class="empty-state-mini">
                                <div class="empty-icon-mini">
                                    <i class="fas fa-users-slash"></i>
                                </div>
                                <h6 class="empty-title-mini">Aucun syndic disponible</h6>
                                <p class="empty-description-mini">
                                    Tous les syndics sont déjà assignés ou aucun compte syndic n'existe dans le système.
                                </p>
                                <button class="btn btn-outline-modern">
                                    <i class="fas fa-phone me-2"></i>Contacter Support
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Variables couleurs - même palette que liste immeubles */
        :root {
            --primary: #173B61;
            --primary-dark: #17616E;
            --primary-light: #7697A0;
            --accent: #FD8916;
            --accent-light: #FFEBD0;
            --success: #10b981;
            --danger: #F0050F;
            --info: #87ABF1;
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
            50% { transform: scale(1.02); }
        }

        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-left { animation: fadeInLeft 0.6s ease-out; }
        .fade-in-right { animation: fadeInRight 0.6s ease-out; }
        .slide-down { animation: slideDown 0.4s ease-out; }
        .pulse-animation { animation: pulse 2s infinite; }

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

        .btn-danger-modern {
            background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-danger-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(240, 5, 15, 0.3);
            color: white;
        }

        /* Alertes */
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
            background: rgba(240, 5, 15, 0.1);
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
            background: rgba(240, 5, 15, 0.2);
        }

        /* Cards modernes */
        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            height: fit-content;
        }

        .card-header-modern {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .card-header-modern.primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .card-header-modern.secondary {
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
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

        .card-title-modern {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .card-body-modern {
            padding: 2rem;
        }

        /* Profil syndic */
        .syndic-profile {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 15px;
            border: 1px solid rgba(23, 59, 97, 0.1);
        }

        .profile-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .profile-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .profile-email {
            color: var(--primary-light);
            font-size: 0.9rem;
            margin: 0 0 0.5rem 0;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-indicator.active {
            color: var(--success);
        }

        .status-indicator i {
            margin-right: 0.5rem;
            font-size: 0.6rem;
            animation: pulse 2s infinite;
        }

        /* Formulaire moderne */
        .form-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .form-select-modern {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-select-modern:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
            outline: none;
        }

        .form-select-modern.is-invalid {
            border-color: var(--danger);
        }

        /* Card d'information - même style que le formulaire créer bloc */
        .info-card {
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.05), rgba(23, 97, 110, 0.05));
            border: 1px solid rgba(23, 59, 97, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(5px);
        }

        .info-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .info-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 0.9rem;
            color: white;
        }

        .info-header h6 {
            color: var(--primary);
            font-weight: 700;
            margin: 0;
            font-size: 1rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
            font-size: 0.9rem;
        }

        .info-list li::before {
            content: '\f058';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: var(--accent);
            position: absolute;
            left: 0;
            top: 0;
        }

        .info-list li:last-child {
            margin-bottom: 0;
        }

        /* État vide mini */
        .empty-state-mini {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-icon-mini {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .empty-icon-mini i {
            font-size: 1.5rem;
            color: var(--primary-light);
        }

        .empty-title-mini {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .empty-description-mini {
            color: var(--primary-light);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Section actions */
        .action-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .header-card {
                padding: 1.5rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .syndic-profile {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-icon {
                margin: 0 auto 1rem auto;
            }
        }

        @media (max-width: 576px) {
            .header-card .d-flex {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .header-icon {
                margin: 0 auto;
            }
            
            .info-card {
                padding: 1rem;
            }
            
            .info-icon {
                margin: 0 auto 1rem auto;
                display: flex;
            }

            .info-header {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Amélioration accessibilité */
        .btn:focus {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Animation de chargement */
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

        /* Container principal */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
    </style>

    <!-- JavaScript pour interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation du bouton de soumission
            const submitBtn = document.querySelector('button[type="submit"]');
            const form = document.querySelector('form');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    submitBtn.classList.add('btn-loading');
                });
            }

            // Validation en temps réel du select
            const selectElement = document.querySelector('.form-select-modern');
            if (selectElement) {
                selectElement.addEventListener('change', function() {
                    this.classList.remove('is-invalid');
                });
            }
        });
    </script>
@endsection