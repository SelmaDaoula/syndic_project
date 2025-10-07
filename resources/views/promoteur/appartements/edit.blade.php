@extends('layouts.app')

@section('title', 'Modifier Appartement')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header moderne -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-card fade-in-up">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="ms-3">
                                <h1 class="page-title">Modifier Appartement N° {{ $appartement->numero }}</h1>
                                <p class="page-subtitle">{{ $appartement->bloc->immeuble->nom }} - {{ $appartement->bloc->nom }}</p>
                            </div>
                        </div>
                        <a href="{{ route('promoteur.appartements.show', $appartement->id) }}" class="btn btn-outline-modern">
                            <i class="fas fa-arrow-left me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages modernisés -->
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

        <div class="row">
            <div class="col-lg-8">
                <!-- Formulaire principal -->
                <div class="form-card fade-in-left">
                    <div class="form-header">
                        <div class="form-icon">
                            <i class="fas fa-edit icon-bounce"></i>
                        </div>
                        <div>
                            <h3 class="form-title">Formulaire de modification</h3>
                            <p class="form-subtitle">Modifiez les informations de l'appartement</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('promoteur.appartements.update', $appartement->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-body">
                            <div class="row g-4">
                                <!-- Numéro d'appartement -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="numero" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-hashtag"></i>
                                            </div>
                                            <span>Numéro d'appartement <span class="required">*</span></span>
                                        </label>
                                        <input type="text" 
                                               class="form-control-modern @error('numero') is-invalid @enderror" 
                                               id="numero" 
                                               name="numero" 
                                               value="{{ old('numero', $appartement->numero) }}" 
                                               required
                                               placeholder="Ex: 101, 202, A15...">
                                        @error('numero')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                        <div class="form-help">Numéro unique dans ce bloc</div>
                                    </div>
                                </div>

                                <!-- Type d'appartement -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="type_appartement" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <span>Type d'appartement <span class="required">*</span></span>
                                        </label>
                                        <select class="form-control-modern @error('type_appartement') is-invalid @enderror" 
                                                id="type_appartement" 
                                                name="type_appartement" 
                                                required>
                                            <option value="">Choisir un type</option>
                                            @foreach($typesAppartement as $type)
                                                <option value="{{ $type }}" 
                                                        {{ old('type_appartement', $appartement->type_appartement) == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type_appartement')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Surface -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="surface" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-ruler-combined"></i>
                                            </div>
                                            <span>Surface (m²) <span class="required">*</span></span>
                                        </label>
                                        <div class="input-with-unit">
                                            <input type="number" 
                                                   class="form-control-modern @error('surface') is-invalid @enderror" 
                                                   id="surface" 
                                                   name="surface" 
                                                   value="{{ old('surface', $appartement->surface) }}" 
                                                   step="0.1" 
                                                   min="10" 
                                                   max="1000" 
                                                   required
                                                   placeholder="75">
                                            <span class="input-unit">m²</span>
                                        </div>
                                        @error('surface')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                        <div class="form-help">Entre 10 et 1000 m²</div>
                                    </div>
                                </div>

                                <!-- Nombre de pièces -->
                                <div class="col-md-6">
                                    <div class="input-group-modern">
                                        <label for="nombre_pieces" class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-door-closed"></i>
                                            </div>
                                            <span>Nombre de pièces <span class="required">*</span></span>
                                        </label>
                                        <select class="form-control-modern @error('nombre_pieces') is-invalid @enderror" 
                                                id="nombre_pieces" 
                                                name="nombre_pieces" 
                                                required>
                                            <option value="">Choisir</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" 
                                                        {{ old('nombre_pieces', $appartement->nombre_pieces) == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i == 1 ? 'pièce' : 'pièces' }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('nombre_pieces')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="col-12">
                                    <div class="input-group-modern">
                                        <label class="form-label-modern">
                                            <div class="label-icon">
                                                <i class="fas fa-flag"></i>
                                            </div>
                                            <span>Statut <span class="required">*</span></span>
                                        </label>
                                        <div class="status-options">
                                            @foreach($statutsAppartement as $statut)
                                                <div class="status-option">
                                                    <input class="status-radio" 
                                                           type="radio"     
                                                           name="statut" 
                                                           id="statut_{{ $statut }}" 
                                                           value="{{ $statut }}"
                                                           {{ old('statut', $appartement->statut) == $statut ? 'checked' : '' }}
                                                           required>
                                                    <label class="status-label" for="statut_{{ $statut }}">
                                                        <div class="status-icon">
                                                            @switch($statut)
                                                                @case('libre')
                                                                    <i class="fas fa-check-circle"></i>
                                                                    @break
                                                                @case('occupe')
                                                                    <i class="fas fa-user"></i>
                                                                    @break
                                                                @case('travaux')
                                                                    <i class="fas fa-tools"></i>
                                                                    @break
                                                                @case('reserve')
                                                                    <i class="fas fa-clock"></i>
                                                                    @break
                                                                @case('maintenance')
                                                                    <i class="fas fa-wrench"></i>
                                                                    @break
                                                            @endswitch
                                                        </div>
                                                        <span class="status-text">{{ ucfirst($statut) }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('statut')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informations du bloc -->
                            <div class="bloc-info-section">
                                <h6 class="section-title">
                                    <i class="fas fa-building me-2"></i>
                                    Informations du bloc
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="readonly-field">
                                            <label class="readonly-label">
                                                <i class="fas fa-building me-1"></i>
                                                Immeuble
                                            </label>
                                            <div class="readonly-value">{{ $appartement->bloc->immeuble->nom }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="readonly-field">
                                            <label class="readonly-label">
                                                <i class="fas fa-layer-group me-1"></i>
                                                Bloc
                                            </label>
                                            <div class="readonly-value">{{ $appartement->bloc->nom }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions du formulaire -->
                        <div class="form-actions">
                            <a href="{{ route('promoteur.appartements.show', $appartement->id) }}" 
                               class="btn btn-cancel">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            
                            <div class="action-buttons">
                                <button type="reset" class="btn btn-reset">
                                    <i class="fas fa-undo me-2"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary-modern pulse-animation">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aide contextuelle -->
            <div class="col-lg-4">
                <div class="help-card fade-in-right">
                    <div class="help-header">
                        <div class="header-content">
                            <div class="header-icon-small">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h6 class="help-title">Aide</h6>
                        </div>
                    </div>
                    <div class="help-body">
                        <div class="help-section">
                            <h6 class="help-section-title">Types d'appartement</h6>
                            <ul class="help-list">
                                <li><strong>Studio :</strong> 1 pièce principale</li>
                                <li><strong>F1 :</strong> 1 pièce + cuisine séparée</li>
                                <li><strong>F2 :</strong> 2 pièces (salon + chambre)</li>
                                <li><strong>F3 :</strong> 3 pièces (salon + 2 chambres)</li>
                                <li><strong>Duplex :</strong> Sur 2 niveaux</li>
                            </ul>
                        </div>
                        
                        <div class="help-section">
                            <h6 class="help-section-title">Statuts disponibles</h6>
                            <ul class="help-list">
                                <li><strong>Libre :</strong> Disponible à la location</li>
                                <li><strong>Occupé :</strong> Actuellement loué</li>
                                <li><strong>En travaux :</strong> Rénovation en cours</li>
                                <li><strong>Réservé :</strong> En cours de négociation</li>
                                <li><strong>Maintenance :</strong> Maintenance technique</li>
                            </ul>
                        </div>
                        
                        <!-- Information importante modernisée -->
                        <div class="info-card-help">
                            <div class="info-header">
                                <div class="info-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h6>Important</h6>
                            </div>
                            <p class="info-text">Le numéro d'appartement doit être unique dans ce bloc.</p>
                        </div>
                    </div>
                </div>
            </div>
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

        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-left { animation: fadeInLeft 0.6s ease-out; }
        .fade-in-right { animation: fadeInRight 0.6s ease-out; }
        .slide-down { animation: slideDown 0.4s ease-out; }
        .pulse-animation { animation: pulse 2s infinite; }
        .icon-bounce:hover { animation: bounce 1s ease infinite; }

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
            background: linear-gradient(135deg, var(--warning) 0%, #fbbf24 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
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
            color: var(--primary-light);
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

        .btn-reset {
            background: white;
            border: 1px solid var(--warning);
            color: var(--warning);
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-1px);
        }

        /* Alertes */
        .alert-danger-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.1);
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
            background: rgba(239, 68, 68, 0.2);
        }

        .alert-heading {
            font-weight: 700;
            color: #7f1d1d;
            margin: 0;
        }

        /* Formulaire */
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
        }

        .form-header {
            display: flex;
            align-items: center;
            padding: 2rem 2rem 1.5rem 2rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--warning) 0%, #fbbf24 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .form-icon i {
            font-size: 22px;
            color: white;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .form-subtitle {
            color: var(--primary-light);
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-body {
            padding: 2rem;
        }

        /* Groupes d'inputs */
        .input-group-modern {
            margin-bottom: 1.5rem;
        }

        .form-label-modern {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            font-size: 0.95rem;
        }

        .label-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
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
            color: var(--accent);
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
            color: var(--primary);
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            width: 100%;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: var(--accent);
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
            color: var(--primary-light);
            font-weight: 600;
            font-size: 0.9rem;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
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
            color: var(--primary-light);
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.5rem;
            font-style: italic;
        }

        /* Options de statut */
        .status-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .status-option {
            position: relative;
        }

        .status-radio {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .status-label {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .status-label:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .status-radio:checked + .status-label {
            border-color: var(--accent);
            background: linear-gradient(135deg, rgba(253, 137, 22, 0.1), rgba(253, 137, 22, 0.05));
            color: var(--primary);
        }

        .status-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 0.9rem;
            color: white;
        }

        .status-option:nth-child(1) .status-icon { background: linear-gradient(135deg, var(--success), #34d399); }
        .status-option:nth-child(2) .status-icon { background: linear-gradient(135deg, var(--warning), #fbbf24); }
        .status-option:nth-child(3) .status-icon { background: linear-gradient(135deg, var(--info), #60a5fa); }
        .status-option:nth-child(4) .status-icon { background: linear-gradient(135deg, var(--secondary), #9ca3af); }
        .status-option:nth-child(5) .status-icon { background: linear-gradient(135deg, var(--danger), #f87171); }

        /* Section informations bloc */
        .bloc-info-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f1f5f9;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .readonly-field {
            margin-bottom: 1rem;
        }

        .readonly-label {
            font-size: 0.85rem;
            color: var(--primary-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            display: block;
        }

        .readonly-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, var(--accent-light), #FFF8E1);
            border-radius: 8px;
            border: 1px solid rgba(253, 137, 22, 0.2);
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

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        /* Card d'aide */
        .help-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }

        .help-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--info) 0%, #60a5fa 100%);
        }

        .help-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--info) 0%, #60a5fa 100%);
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

        .help-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .help-body {
            padding: 1.5rem;
        }

        .help-section {
            margin-bottom: 1.5rem;
        }

        .help-section:last-child {
            margin-bottom: 0;
        }

        .help-section-title {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .help-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .help-list li {
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            padding-left: 1rem;
            position: relative;
            color: var(--primary-light);
            line-height: 1.4;
        }

        .help-list li::before {
            content: '•';
            color: var(--accent);
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        /* Card d'information dans l'aide */
        .info-card-help {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.02));
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .info-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .info-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, var(--warning), #fbbf24);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 0.75rem;
            color: white;
        }

        .info-header h6 {
            color: var(--warning);
            font-weight: 700;
            margin: 0;
            font-size: 0.9rem;
        }

        .info-text {
            color: var(--primary);
            font-weight: 500;
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .header-card {
                padding: 1.5rem;
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
            
            .action-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .status-options {
                grid-template-columns: 1fr;
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
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .form-card {
                margin: 0 -0.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn-primary-modern, .btn-cancel, .btn-reset {
                width: 100%;
                text-align: center;
            }
        }

        /* Container principal */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation du bouton de soumission
            const submitBtn = document.querySelector('button[type="submit"]');
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function() {
                submitBtn.classList.add('btn-loading');
            });

            // Validation en temps réel
            const inputs = document.querySelectorAll('.form-control-modern');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const feedback = this.parentNode.querySelector('.invalid-feedback-modern');
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                });
            });

            // Auto-suggestion du nombre de pièces selon le type
            document.getElementById('type_appartement').addEventListener('change', function() {
                const type = this.value;
                const nombrePiecesSelect = document.getElementById('nombre_pieces');
                
                const suggestions = {
                    'studio': 1,
                    'F2': 2,
                    'F3': 3,
                    'F4': 4,
                    'F5+': 5
                };
                
                if (suggestions[type] && !nombrePiecesSelect.value) {
                    nombrePiecesSelect.value = suggestions[type];
                }
            });

            // Auto-suggestion de surface selon le type
            document.getElementById('type_appartement').addEventListener('change', function() {
                const type = this.value;
                const surfaceInput = document.getElementById('surface');
                
                const surfaceSuggestions = {
                    'studio': 25,
                    'F2': 50,
                    'F3': 75,
                    'F4': 90,
                    'F5+': 110
                };
                
                if (surfaceSuggestions[type] && !surfaceInput.value) {
                    surfaceInput.value = surfaceSuggestions[type];
                }
            });
        });
    </script>
@endsection