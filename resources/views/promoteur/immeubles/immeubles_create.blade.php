@extends('layouts.app')

@section('title', 'Créer un Immeuble')

@section('breadcrumb', 'Promoteur / Immeubles / Créer')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Header Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building me-3 fs-4"></i>
                                <div>
                                    <h4 class="mb-0">Créer un Immeuble</h4>
                                    <small class="opacity-75">Remplissez les informations de votre immeuble</small>
                                </div>
                            </div>
                            <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Messages d'erreur -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Erreurs de validation :</strong>
                        </div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Formulaire Principal -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('promoteur.immeubles.store') }}" method="POST">
                            @csrf

                            <!-- Nom de l'immeuble -->
                            <div class="mb-4">
                                <label for="nom" class="form-label fw-semibold">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    Nom de l'Immeuble <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror"
                                    id="nom" name="nom" value="{{ old('nom') }}" placeholder="Ex: Résidence Les Jardins"
                                    required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse -->
                            <div class="mb-4">
                                <label for="adresse" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    Adresse Complète <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('adresse') is-invalid @enderror" id="adresse"
                                    name="adresse" rows="3" placeholder="Ex: Avenue Bourguiba, Tunis 1000, Tunisie"
                                    required>{{ old('adresse') }}</textarea>
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Grille pour champs numériques -->
                            <div class="row mb-4">
                                <!-- Surface totale -->
                                <div class="col-md-6 mb-3">
                                    <label for="surfaceTotal" class="form-label fw-semibold">
                                        <i class="fas fa-ruler-combined text-info me-2"></i>
                                        Surface Totale (m²)
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                            class="form-control @error('surfaceTotal') is-invalid @enderror"
                                            id="surfaceTotal" name="surfaceTotal" value="{{ old('surfaceTotal') }}"
                                            step="0.01" min="0" max="999999" placeholder="Ex: 2500">
                                        <span class="input-group-text">m²</span>
                                        @error('surfaceTotal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Année de construction -->
                                <div class="col-md-6 mb-3">
                                    <label for="anneeConstruction" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-warning me-2"></i>
                                        Année de Construction
                                    </label>
                                    <input type="number"
                                        class="form-control @error('anneeConstruction') is-invalid @enderror"
                                        id="anneeConstruction" name="anneeConstruction"
                                        value="{{ old('anneeConstruction', date('Y')) }}" min="1900"
                                        max="{{ date('Y') + 10 }}" placeholder="Ex: {{ date('Y') }}">
                                    @error('anneeConstruction')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="mb-4">
                                <label for="statut" class="form-label fw-semibold">
                                    <i class="fas fa-info-circle text-secondary me-2"></i>
                                    Statut de l'Immeuble <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg @error('statut') is-invalid @enderror" id="statut"
                                    name="statut" required>
                                    <option value="">-- Sélectionnez un statut --</option>
                                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>
                                        <i class="fas fa-check-circle"></i> Actif - Prêt pour l'occupation
                                    </option>
                                    <option value="construction" {{ old('statut') == 'construction' ? 'selected' : '' }}>
                                        <i class="fas fa-hammer"></i> En Construction
                                    </option>
                                    <option value="maintenance" {{ old('statut') == 'maintenance' ? 'selected' : '' }}>
                                        <i class="fas fa-tools"></i> Maintenance - En cours de réparation
                                    </option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Information importante -->
                            <div class="alert alert-info border-start border-primary border-4" role="alert">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle fs-4 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading">Information importante</h6>
                                        <ul class="mb-0 small">
                                            <li>Un promoteur ne peut créer qu'un seul immeuble</li>
                                            <li>Vous pourrez ajouter des blocs après la création</li>
                                            <li>L'assignation d'un syndic se fera ultérieurement</li>
                                            <li>Tous les champs marqués d'un <span class="text-danger">*</span> sont
                                                obligatoires</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="d-flex justify-content-between pt-3 border-top">
                                <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>

                                <div class="d-flex gap-2">
                                    <button type="reset" class="btn btn-outline-warning" id="resetBtn">
                                        <i class="fas fa-undo me-2"></i>Réinitialiser
                                    </button>

                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Créer l'Immeuble
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Étapes suivantes -->
                <div class="card mt-4 border-success">
                    <div class="card-header bg-light-success border-success">
                        <h6 class="card-title mb-0 text-success">
                            <i class="fas fa-lightbulb me-2"></i>Prochaines étapes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-success rounded-pill fs-6 me-2">1</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">Créer l'immeuble</h6>
                                        <small class="text-muted">Remplir ce formulaire</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-secondary rounded-pill fs-6 me-2">2</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">Ajouter des blocs</h6>
                                        <small class="text-muted">Définir la structure</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-secondary rounded-pill fs-6 me-2">3</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-1">Assigner un syndic</h6>
                                        <small class="text-muted">Gestion de l'immeuble</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-light-success {
                background-color: #d1e7dd !important;
            }

            .border-4 {
                border-width: 4px !important;
            }

            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            }

            .alert-info {
                background-color: #e7f3ff;
                border-color: #b6d7ff;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Validation de l'année
                const anneeInput = document.getElementById('anneeConstruction');
                if (anneeInput) {
                    anneeInput.addEventListener('input', function () {
                        const currentYear = new Date().getFullYear();
                        const inputYear = parseInt(this.value);

                        if (inputYear > currentYear + 10) {
                            this.setCustomValidity('L\'année ne peut pas être trop éloignée dans le futur');
                        } else if (inputYear < 1900) {
                            this.setCustomValidity('L\'année ne peut pas être antérieure à 1900');
                        } else {
                            this.setCustomValidity('');
                        }
                    });
                }

                // Confirmation avant réinitialisation
                const resetBtn = document.getElementById('resetBtn');
                if (resetBtn) {
                    resetBtn.addEventListener('click', function (e) {
                        if (!confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                            e.preventDefault();
                        }
                    });
                }

                // Animation de soumission
                const form = document.getElementById('immeubleForm');
                form.addEventListener('submit', function () {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
                    submitBtn.disabled = true;
                });
            });
        </script>
    @endpush
@endsection