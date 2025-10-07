@extends('layouts.app')

@section('title', 'Créer un Ticket')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header moderne -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="header-card fade-in-up">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="header-icon">
                                <i class="fas fa-plus-circle icon-bounce"></i>
                            </div>
                            <div class="ms-3">
                                <h1 class="page-title">Créer un Ticket</h1>
                                <p class="page-subtitle">{{ $immeuble->nom }} - Nouveau signalement</p>
                            </div>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('syndic.tickets.index') }}" class="btn btn-outline-modern">
                                <i class="fas fa-arrow-left me-2"></i>Retour aux tickets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if($errors->any())
            <div class="alert alert-danger-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <strong>Erreurs détectées :</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire de création -->
        <form action="{{ route('syndic.tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <!-- Colonne principale -->
                <div class="col-lg-8">
                    <!-- Informations générales -->
                    <div class="form-card fade-in-left">
                        <div class="form-header">
                            <div class="form-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h3 class="form-title">Informations du Ticket</h3>
                                <p class="form-subtitle">Détails de l'incident à signaler</p>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="row g-3">
                                <!-- Titre -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="titre" class="form-label">
                                            <i class="fas fa-heading me-2"></i>
                                            Titre du ticket <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control modern-input" id="titre" name="titre"
                                            value="{{ old('titre') }}" placeholder="Ex: Fuite d'eau dans la salle de bain"
                                            required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-2"></i>
                                            Description détaillée <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control modern-textarea" id="description" name="description"
                                            rows="5" placeholder="Décrivez l'incident en détail..."
                                            required>{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <!-- Appartement -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="appartement_id" class="form-label">
                                            <i class="fas fa-home me-2"></i>
                                            Appartement concerné <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select" id="appartement_id" name="appartement_id"
                                            required>
                                            <option value="">Sélectionner un appartement</option>
                                            @foreach($appartements as $appartement)
                                                <option value="{{ $appartement->id }}" {{ old('appartement_id') == $appartement->id ? 'selected' : '' }}>
                                                    Apt. {{ $appartement->numero }} -
                                                    {{ $appartement->bloc->nom ?? 'Bloc principal' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Type d'incident -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type_incident" class="form-label">
                                            <i class="fas fa-tools me-2"></i>
                                            Type d'incident <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select" id="type_incident" name="type_incident"
                                            required>
                                            <option value="">Sélectionner le type</option>
                                            <option value="plomberie" {{ old('type_incident') === 'plomberie' ? 'selected' : '' }}>Plomberie</option>
                                            <option value="electricite" {{ old('type_incident') === 'electricite' ? 'selected' : '' }}>Électricité</option>
                                            <option value="chauffage" {{ old('type_incident') === 'chauffage' ? 'selected' : '' }}>Chauffage</option>
                                            <option value="nettoyage" {{ old('type_incident') === 'nettoyage' ? 'selected' : '' }}>Nettoyage</option>
                                            <option value="securite" {{ old('type_incident') === 'securite' ? 'selected' : '' }}>Sécurité</option>
                                            <option value="ascenseur" {{ old('type_incident') === 'ascenseur' ? 'selected' : '' }}>Ascenseur</option>
                                            <option value="ventilation" {{ old('type_incident') === 'ventilation' ? 'selected' : '' }}>Ventilation</option>
                                            <option value="autre" {{ old('type_incident') === 'autre' ? 'selected' : '' }}>
                                                Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Priorité -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priorite" class="form-label">
                                            <i class="fas fa-flag me-2"></i>
                                            Priorité <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select modern-select" id="priorite" name="priorite" required>
                                            <option value="">Sélectionner la priorité</option>
                                            <option value="faible" class="text-success" {{ old('priorite') === 'faible' ? 'selected' : '' }}>🟢 Faible</option>
                                            <option value="normale" class="text-warning" {{ old('priorite') === 'normale' ? 'selected' : '' }}>🟡 Normale</option>
                                            <option value="haute" class="text-danger" {{ old('priorite') === 'haute' ? 'selected' : '' }}>🔴 Haute</option>
                                            <option value="urgente" class="text-dark" {{ old('priorite') === 'urgente' ? 'selected' : '' }}>⚫ Urgente</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Coût estimé -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cout_estime" class="form-label">
                                            <i class="fas fa-euro-sign me-2"></i>
                                            Coût estimé (TND)
                                        </label>
                                        <input type="number" class="form-control modern-input" id="cout_estime"
                                            name="cout_estime" value="{{ old('cout_estime') }}" step="0.01" min="0"
                                            placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload photos -->
                    <div class="form-card fade-in-left mt-4">
                        <div class="form-header">
                            <div class="form-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div>
                                <h3 class="form-title">Photos de l'incident</h3>
                                <p class="form-subtitle">Joindre des photos pour mieux comprendre le problème</p>
                            </div>
                        </div>

                        <div class="form-body">
                            <div class="upload-zone" id="uploadZone">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <h4>Glissez vos photos ici</h4>
                                    <p>ou cliquez pour sélectionner des fichiers</p>
                                    <input type="file" class="upload-input" id="photos" name="photos[]" multiple
                                        accept="image/*">
                                </div>
                                <div class="upload-preview" id="preview"></div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats acceptés: JPG, PNG, GIF. Taille max: 2MB par fichier.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Assignation -->
                    <div class="form-card fade-in-right">
                        <div class="form-header-simple">
                            <h4 class="form-title-simple">
                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                Assignation
                            </h4>
                        </div>
                        <div class="form-body">
                            <div class="form-group">
                                <label for="assignee_id" class="form-label">Assigner à un technicien</label>
                                <select class="form-select modern-select" id="assignee_id" name="assignee_id">
                                    <option value="">Assigner plus tard</option>
                                    @foreach($techniciens as $technicien)
                                        <option value="{{ $technicien->user_id }}" {{ old('assignee_id') == $technicien->user_id ? 'selected' : '' }}>
                                            {{ $technicien->user->name }}
                                            @if($technicien->specialites)
                                                - {{ implode(', ', json_decode($technicien->specialites, true)) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Les techniciens sont filtrés par spécialité selon le type d'incident
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé -->
                    <div class="form-card fade-in-right mt-4">
                        <div class="form-header-simple">
                            <h4 class="form-title-simple">
                                <i class="fas fa-clipboard-check me-2 text-success"></i>
                                Résumé
                            </h4>
                        </div>
                        <div class="form-body">
                            <div class="summary-item">
                                <span class="summary-label">Immeuble:</span>
                                <span class="summary-value">{{ $immeuble->nom }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Créé par:</span>
                                <span class="summary-value">{{ Auth::user()->name }} (Syndic)</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Date:</span>
                                <span class="summary-value">{{ now()->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Statut initial:</span>
                                <span class="summary-value">
                                    <span class="status-badge status-warning">Ouvert</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-card fade-in-right mt-4">
                        <div class="form-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-modern btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Créer le Ticket
                                </button>
                                <a href="{{ route('syndic.tickets.index') }}" class="btn btn-outline-modern">
                                    <i class="fas fa-times me-2"></i>
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JavaScript pour les interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gestion de l'upload de photos
            const uploadZone = document.getElementById('uploadZone');
            const fileInput = document.getElementById('photos');
            const preview = document.getElementById('preview');

            uploadZone.addEventListener('click', () => fileInput.click());

            uploadZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadZone.classList.add('drag-over');
            });

            uploadZone.addEventListener('dragleave', () => {
                uploadZone.classList.remove('drag-over');
            });

            uploadZone.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadZone.classList.remove('drag-over');
                fileInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files);
            });

            fileInput.addEventListener('change', (e) => {
                showPreview(e.target.files);
            });

            function showPreview(files) {
                preview.innerHTML = '';
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = document.createElement('div');
                            img.className = 'preview-item';
                            img.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview">
                                    <div class="preview-name">${file.name}</div>
                                `;
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Filtrer les techniciens par spécialité
            const typeSelect = document.getElementById('type_incident');
            const technicienSelect = document.getElementById('assignee_id');
            const originalOptions = Array.from(technicienSelect.options);

            typeSelect.addEventListener('change', function () {
                const selectedType = this.value;

                // Réinitialiser les options
                technicienSelect.innerHTML = '<option value="">Assigner plus tard</option>';

                // Filtrer et ajouter les techniciens correspondants
                originalOptions.slice(1).forEach(option => {
                    const specialites = option.textContent.toLowerCase();
                    if (!selectedType || specialites.includes(selectedType) || specialites.includes('general')) {
                        technicienSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
        });
    </script>

    <!-- CSS moderne complet -->
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

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .fade-in-left {
            animation: fadeInLeft 0.6s ease-out;
        }

        .fade-in-right {
            animation: fadeInRight 0.6s ease-out;
        }

        .slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .icon-bounce:hover {
            animation: bounce 1s ease infinite;
        }

        /* Header moderne */
        .header-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        /* Boutons */
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
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline-modern:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Alertes */
        .alert-danger-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
            padding: 1.25rem;
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

        /* Cards de formulaire */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .form-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .form-subtitle {
            color: var(--primary-light);
            margin: 0;
            font-size: 0.9rem;
        }

        .form-header-simple {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-title-simple {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            display: flex;
            align-items: center;
        }

        .form-body {
            padding: 2rem;
        }

        /* Champs de formulaire modernes */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .modern-input,
        .modern-textarea,
        .modern-select {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .modern-input:focus,
        .modern-textarea:focus,
        .modern-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(253, 137, 22, 0.1);
            transform: translateY(-1px);
        }

        .modern-textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Zone d'upload */
        .upload-zone {
            border: 3px dashed #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: var(--accent);
            background: rgba(253, 137, 22, 0.05);
        }

        .upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 1rem;
        }

        .upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .preview-name {
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            font-size: 0.7rem;
            color: var(--primary-light);
            text-align: center;
        }

        /* Résumé */
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 600;
            color: var(--primary-light);
            font-size: 0.9rem;
        }

        .summary-value {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.9rem;
        }

        /* Badges de statut */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #92400e;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .page-title {
                font-size: 1.75rem;
            }

            .header-card {
                padding: 1.5rem;
            }

            .form-body {
                padding: 1.5rem;
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

            .upload-zone {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .form-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection