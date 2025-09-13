@extends('layouts.app')

@section('title', 'Modifier Appartement')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit text-warning fs-2 me-3"></i>
                    <div>
                        <h1 class="h3 mb-1">Modifier Appartement N° {{ $appartement->numero }}</h1>
                        <p class="text-muted mb-0">{{ $appartement->bloc->immeuble->nom }} - {{ $appartement->bloc->nom }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('promoteur.appartements.show', $appartement->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Erreurs de validation :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Formulaire de modification
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('promoteur.appartements.update', $appartement->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Numéro d'appartement -->
                            <div class="col-md-6">
                                <label for="numero" class="form-label">
                                    <i class="fas fa-hashtag me-1"></i>
                                    Numéro d'appartement <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('numero') is-invalid @enderror" 
                                       id="numero" 
                                       name="numero" 
                                       value="{{ old('numero', $appartement->numero) }}" 
                                       required
                                       placeholder="Ex: 101, 202, A15...">
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Numéro unique dans ce bloc</div>
                            </div>

                            <!-- Type d'appartement -->
                            <div class="col-md-6">
                                <label for="type_appartement" class="form-label">
                                    <i class="fas fa-home me-1"></i>
                                    Type d'appartement <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type_appartement') is-invalid @enderror" 
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Surface -->
                            <div class="col-md-6">
                                <label for="surface" class="form-label">
                                    <i class="fas fa-ruler-combined me-1"></i>
                                    Surface (m²) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('surface') is-invalid @enderror" 
                                           id="surface" 
                                           name="surface" 
                                           value="{{ old('surface', $appartement->surface) }}" 
                                           step="0.1" 
                                           min="10" 
                                           max="1000" 
                                           required
                                           placeholder="75">
                                    <span class="input-group-text">m²</span>
                                    @error('surface')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Entre 10 et 1000 m²</div>
                            </div>

                            <!-- Nombre de pièces -->
                            <div class="col-md-6">
                                <label for="nombre_pieces" class="form-label">
                                    <i class="fas fa-door-closed me-1"></i>
                                    Nombre de pièces <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('nombre_pieces') is-invalid @enderror" 
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-12">
                                <label for="statut" class="form-label">
                                    <i class="fas fa-flag me-1"></i>
                                    Statut <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
    @foreach($statutsAppartement as $statut)
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" 
                       type="radio"     
                       name="statut" 
                       id="statut_{{ $statut }}" 
                       value="{{ $statut }}"
                       {{ old('statut', $appartement->statut) == $statut ? 'checked' : '' }}
                       required>
                <label class="form-check-label" for="statut_{{ $statut }}">
                    @switch($statut)
                        @case('libre')
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Libre
                            @break
                        @case('occupe')
                            <i class="fas fa-user text-warning me-1"></i>
                            Occupé
                            @break
                        @case('travaux')
                            <i class="fas fa-tools text-info me-1"></i>
                            En travaux
                            @break
                        @case('reserve')
                            <i class="fas fa-clock text-secondary me-1"></i>
                            Réservé
                            @break
                        @case('maintenance')
                            <i class="fas fa-wrench text-danger me-1"></i>
                            En maintenance
                            @break
                        @default
                            <i class="fas fa-question text-muted me-1"></i>
                            {{ ucfirst($statut) }}
                    @endswitch
                </label>
            </div>
        </div>
    @endforeach
</div>
                                @error('statut')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations du bloc (en lecture seule) -->
                        <hr class="my-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">
                                    <i class="fas fa-building me-1"></i>
                                    Immeuble
                                </label>
                                <input type="text" class="form-control-plaintext" readonly 
                                       value="{{ $appartement->bloc->immeuble->nom }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">
                                    <i class="fas fa-layer-group me-1"></i>
                                    Bloc
                                </label>
                                <input type="text" class="form-control-plaintext" readonly 
                                       value="{{ $appartement->bloc->nom }}">
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <hr class="my-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('promoteur.appartements.show', $appartement->id) }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="fas fa-undo me-2"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aide contextuelle -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Types d'appartement</h6>
                        <ul class="small mb-0">
                            <li><strong>Studio :</strong> 1 pièce principale</li>
                            <li><strong>F1 :</strong> 1 pièce + cuisine séparée</li>
                            <li><strong>F2 :</strong> 2 pièces (salon + chambre)</li>
                            <li><strong>F3 :</strong> 3 pièces (salon + 2 chambres)</li>
                            <li><strong>Duplex :</strong> Sur 2 niveaux</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-success">Statuts disponibles</h6>
                        <ul class="small mb-0">
                            <li><strong>Libre :</strong> Disponible à la location</li>
                            <li><strong>Occupé :</strong> Actuellement loué</li>
                            <li><strong>En travaux :</strong> Rénovation en cours</li>
                            <li><strong>Réservé :</strong> En cours de négociation</li>
                            <li><strong>Maintenance :</strong> Maintenance technique</li>
                        </ul>
                    </div>
                    

                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Important :</strong> Le numéro d'appartement doit être unique dans ce bloc.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-suggestion du nombre de pièces selon le type
document.getElementById('type_appartement').addEventListener('change', function() {
    const type = this.value;
    const nombrePiecesSelect = document.getElementById('nombre_pieces');
    
    // Suggestions automatiques
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
    
    // Suggestions de surface moyennes
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

// Améliorer l'apparence des statuts
document.querySelectorAll('input[name="statut"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        // Retirer la classe active de tous les labels
        document.querySelectorAll('label[for^="statut_"]').forEach(function(label) {
            label.classList.remove('bg-light', 'border-primary');
        });
        
        // Ajouter la classe active au label sélectionné
        const selectedLabel = document.querySelector('label[for="' + this.id + '"]');
        selectedLabel.classList.add('bg-light', 'border-primary');
    });
});
</script>
@endsection