@extends('layouts.app')

@section('title', 'Créer un Bloc - Promoteur')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header avec animation d'entrée -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center fade-in-up">
                    <div>
                        <h1 class="page-title">Créer un Bloc</h1>
                        <p class="page-subtitle">Pour l'immeuble: <strong>{{ $immeuble->nom }} - {{ $immeuble->adresse }}</strong></p>
                    </div>
                    <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-modern">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux immeubles
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages d'erreur avec animation -->
        @if($errors->any())
            <div class="alert alert-danger-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="alert-heading mb-2">Erreurs de validation :</h6>
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

        <!-- Formulaire principal -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card fade-in-up">
                    <!-- En-tête du formulaire -->
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-plus icon-bounce"></i>
                        </div>
                        <div>
                            <h3 class="form-title">Nouveau Bloc</h3>
                            <p class="form-subtitle">Ajoutez un nouveau bloc à votre immeuble</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('promoteur.blocs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="immeuble_id" value="{{ $immeuble->id }}">
                        
                        <div class="form-body">
                            <div class="row g-4">
                                <!-- Nom du Bloc -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="nom" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <span>Nom du Bloc <span class="required">*</span></span>
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern @error('nom') is-invalid @enderror" 
                                               id="nom" 
                                               name="nom" 
                                               value="{{ old('nom') }}"
                                               placeholder="Ex: Bloc A, Bloc 1..."
                                               required>
                                        @error('nom')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nombre d'Appartements -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="nombreAppartement" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-door-open"></i>
                                            </div>
                                            <span>Nombre d'Appartements <span class="required">*</span></span>
                                        </label>
                                        <input type="number" 
                                               class="form-control-modern @error('nombreAppartement') is-invalid @enderror" 
                                               id="nombreAppartement" 
                                               name="nombreAppartement" 
                                               value="{{ old('nombreAppartement') }}"
                                               min="1" 
                                               max="200"
                                               placeholder="Nombre d'appartements"
                                               required>
                                        @error('nombreAppartement')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nombre d'Étages -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="nombreEtages" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                            <span>Nombre d'Étages <span class="required">*</span></span>
                                        </label>
                                        <input type="number" 
                                               class="form-control-modern @error('nombreEtages') is-invalid @enderror" 
                                               id="nombreEtages" 
                                               name="nombreEtages" 
                                               value="{{ old('nombreEtages') }}"
                                               min="1" 
                                               max="50"
                                               placeholder="Nombre d'étages"
                                               required>
                                        @error('nombreEtages')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Surface Totale -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="surfaceTotale" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-ruler-combined"></i>
                                            </div>
                                            <span>Surface Totale (m²)</span>
                                        </label>
                                        <div class="input-with-unit">
                                            <input type="number" 
                                                   class="form-control-modern @error('surfaceTotale') is-invalid @enderror" 
                                                   id="surfaceTotale" 
                                                   name="surfaceTotale" 
                                                   value="{{ old('surfaceTotale') }}"
                                                   min="0" 
                                                   step="0.01"
                                                   placeholder="Surface en m²">
                                            <span class="input-unit">m²</span>
                                        </div>
                                        @error('surfaceTotale')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                        <div class="form-help">Optionnel - Laissez vide si non défini</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Information importante -->
                            <div class="info-card">
                                <div class="info-header">
                                    <div class="info-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h6>Information importante</h6>
                                </div>
                                <ul class="info-list">
                                    <li>Le nom du bloc doit être unique pour cet immeuble</li>
                                    <li>Vous pourrez modifier ces informations plus tard</li>
                                    <li>La surface totale est calculée automatiquement si non renseignée</li>
                                    <li>Tous les champs marqués d'un * sont obligatoires</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary-modern pulse-animation">
                                <i class="fas fa-plus me-2"></i>Créer le Bloc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS modernisé -->
    <style>
        /* Base */
        body {
            background-color: #FFEBD0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animations */
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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        .icon-bounce:hover {
            animation: bounce 1s ease infinite;
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

        /* Boutons */
        .btn-outline-modern {
            background: white;
            border: 1px solid #e2e8f0;
            color: #173B61;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-outline-modern:hover {
            background: #173B61;
            border-color: #173B61;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(23, 59, 97, 0.3);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            border: none;
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.05rem;
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

        .btn-cancel {
            background: white;
            border: 1px solid #e2e8f0;
            color: #7697A0;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            transform: translateY(-1px);
        }

        /* Alertes */
        .alert-danger-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(240, 5, 15, 0.3);
            background: rgba(240, 5, 15, 0.1);
            color: #7f1d1d;
            padding: 1.5rem;
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

        .alert-icon-wrapper.error {
            background: rgba(240, 5, 15, 0.2);
        }

        .alert-heading {
            font-weight: 700;
            color: #7f1d1d;
            margin: 0;
        }

        /* Card du formulaire */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 50%, #FD8916 100%);
        }

        /* En-tête du formulaire */
        .form-header {
            display: flex;
            align-items: center;
            padding: 2rem 2rem 1.5rem 2rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-icon {
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

        .form-icon i {
            font-size: 22px;
            color: white;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #173B61;
            margin: 0 0 0.25rem 0;
        }

        .form-subtitle {
            color: #7697A0;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Corps du formulaire */
        .form-body {
            padding: 2rem;
        }

        /* Groupes d'inputs modernisés */
        .input-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #173B61;
            font-size: 0.95rem;
        }

        .label-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
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
            color: #FD8916;
        }

        .required {
            color: #ef4444;
            font-weight: 700;
        }

        .form-control-modern {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            font-weight: 500;
            color: #173B61;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            width: 100%;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #FD8916;
            box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
            transform: translateY(-1px);
        }

        .form-control-modern.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-control-modern::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Input avec unité */
        .input-with-unit {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-unit {
            position: absolute;
            right: 16px;
            color: #7697A0;
            font-weight: 600;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid rgba(253, 137, 22, 0.2);
        }

        .input-with-unit .form-control-modern {
            padding-right: 60px;
        }

        .invalid-feedback-modern {
            color: #ef4444;
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .invalid-feedback-modern::before {
            content: '\f06a';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 0.5rem;
        }

        .form-help {
            color: #7697A0;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
            font-style: italic;
        }

        /* Card d'information */
        .info-card {
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.05), rgba(23, 97, 110, 0.05));
            border: 1px solid rgba(23, 59, 97, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
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
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
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
            color: #173B61;
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
            color: #173B61;
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
            color: #FD8916;
            position: absolute;
            left: 0;
            top: 0;
        }

        .info-list li:last-child {
            margin-bottom: 0;
        }

        /* Actions du formulaire */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem 2rem 2rem;
            border-top: 2px solid #f1f5f9;
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }

            .form-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .form-icon {
                margin: 0 0 1rem 0;
            }

            .form-body {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
            }

            .btn-primary-modern, .btn-cancel {
                width: 100%;
                text-align: center;
            }

            .input-with-unit {
                flex-direction: column;
                align-items: stretch;
            }

            .input-unit {
                position: static;
                margin-top: 0.5rem;
                text-align: center;
            }

            .input-with-unit .form-control-modern {
                padding-right: 16px;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.5rem;
            }

            .form-card {
                margin: 0 -0.5rem;
            }

            .info-list li {
                font-size: 0.85rem;
            }

            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Améliorations accessibilité */
        .btn:focus, .form-control-modern:focus {
            outline: 2px solid #FD8916;
            outline-offset: 2px;
        }

        /* Animation de loading pour le bouton de soumission */
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

        /* Effet de focus amélioré */
        .form-control-modern:focus + .input-unit {
            color: #FD8916;
            border-color: #FD8916;
        }

        /* Container principal */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
    </style>

    <!-- JavaScript pour les interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des inputs au focus
            const inputs = document.querySelectorAll('.form-control-modern');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-group-modern').classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.input-group-modern').classList.remove('focused');
                });
            });

            // Animation du bouton de soumission
            const submitBtn = document.querySelector('button[type="submit"]');
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function() {
                submitBtn.classList.add('btn-loading');
            });

            // Validation en temps réel
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const feedback = this.parentNode.querySelector('.invalid-feedback-modern');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                });
            });

            // Auto-calculation de la surface si les autres champs sont remplis
            const nombreAppart = document.getElementById('nombreAppartement');
            const nombreEtages = document.getElementById('nombreEtages');
            const surfaceTotale = document.getElementById('surfaceTotale');
            
            function autoCalculateSurface() {
                if (nombreAppart.value && nombreEtages.value && !surfaceTotale.value) {
                    // Estimation basique : 70m² par appartement en moyenne
                    const estimatedSurface = parseInt(nombreAppart.value) * 70;
                    surfaceTotale.placeholder = `Estimation: ${estimatedSurface} m²`;
                }
            }
            
            nombreAppart.addEventListener('input', autoCalculateSurface);
            nombreEtages.addEventListener('input', autoCalculateSurface);
        });
    </script>
@endsection