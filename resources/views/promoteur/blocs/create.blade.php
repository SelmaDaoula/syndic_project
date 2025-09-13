@extends('layouts.app')

@section('title', 'Créer un Bloc - Promoteur')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-th-large text-primary fs-2 me-3"></i>
                    <div>
                        <h1 class="h3 mb-1">Créer un Bloc</h1>
                        <p class="text-muted mb-0">Pour l'immeuble: <strong>{{ $immeuble->nom }} - {{ $immeuble->adresse }}</strong></p>
                    </div>
                </div>
                <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux immeubles
                </a>
            </div>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Erreurs de validation :</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Nouveau Bloc
                    </h3>
                </div>
                
                <form action="{{ route('promoteur.blocs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="immeuble_id" value="{{ $immeuble->id }}">
                    
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Nom du Bloc -->
                            <div class="col-md-6">
                                <label for="nom" class="form-label fw-semibold">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    Nom du Bloc <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}"
                                       placeholder="Ex: Bloc A, Bloc 1..."
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nombre d'Appartements -->
                            <div class="col-md-6">
                                <label for="nombreAppartement" class="form-label fw-semibold">
                                    <i class="fas fa-door-open text-success me-2"></i>
                                    Nombre d'Appartements <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('nombreAppartement') is-invalid @enderror" 
                                       id="nombreAppartement" 
                                       name="nombreAppartement" 
                                       value="{{ old('nombreAppartement') }}"
                                       min="1" 
                                       max="200"
                                       placeholder="Nombre d'appartements"
                                       required>
                                @error('nombreAppartement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nombre d'Étages -->
                            <div class="col-md-6">
                                <label for="nombreEtages" class="form-label fw-semibold">
                                    <i class="fas fa-layer-group text-info me-2"></i>
                                    Nombre d'Étages <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('nombreEtages') is-invalid @enderror" 
                                       id="nombreEtages" 
                                       name="nombreEtages" 
                                       value="{{ old('nombreEtages') }}"
                                       min="1" 
                                       max="50"
                                       placeholder="Nombre d'étages"
                                       required>
                                @error('nombreEtages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Surface Totale -->
                            <div class="col-md-6">
                                <label for="surfaceTotale" class="form-label fw-semibold">
                                    <i class="fas fa-ruler-combined text-warning me-2"></i>
                                    Surface Totale (m²)
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control form-control-lg @error('surfaceTotale') is-invalid @enderror" 
                                           id="surfaceTotale" 
                                           name="surfaceTotale" 
                                           value="{{ old('surfaceTotale') }}"
                                           min="0" 
                                           step="0.01"
                                           placeholder="Surface en m²">
                                    <span class="input-group-text">m²</span>
                                    @error('surfaceTotale')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Optionnel - Laissez vide si non défini</div>
                            </div>
                        </div>

                        <!-- Information importante -->
                        <div class="alert alert-info mt-4" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Information importante</h6>
                            <ul class="mb-0">
                                <li>Le nom du bloc doit être unique pour cet immeuble</li>
                                <li>Vous pourrez modifier ces informations plus tard</li>
                                <li>La surface totale est calculée automatiquement si non renseignée</li>
                                <li>Tous les champs marqués d'un * sont obligatoires</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Créer le Bloc
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection