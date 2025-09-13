@extends('layouts.app')

@section('title', 'Détail Appartement')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-door-open text-primary fs-2 me-3"></i>
                    <div>
                        <h1 class="h3 mb-1">Appartement N° {{ $appartement->numero }}</h1>
                        <p class="text-muted mb-0">{{ $appartement->bloc->immeuble->nom }} - {{ $appartement->bloc->nom }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('promoteur.appartements.edit', $appartement->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('promoteur.appartements.index', ['bloc_id' => $appartement->bloc_id]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations de l'appartement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="fas fa-hashtag text-primary fs-4"></i>
                                </div>
                                <div>
                                    <p class="small text-muted mb-0">Numéro</p>
                                    <p class="h4 mb-0">{{ $appartement->numero }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="fas fa-home text-success fs-4"></i>
                                </div>
                                <div>
                                    <p class="small text-muted mb-0">Type</p>
                                    <p class="h4 mb-0">{{ $appartement->type_appartement }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="fas fa-ruler-combined text-info fs-4"></i>
                                </div>
                                <div>
                                    <p class="small text-muted mb-0">Surface</p>
                                    <p class="h4 mb-0">{{ $appartement->surface }} m²</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="fas fa-door-closed text-warning fs-4"></i>
                                </div>
                                <div>
                                    <p class="small text-muted mb-0">Nombre de pièces</p>
                                    <p class="h4 mb-0">{{ $appartement->nombre_pieces }} pièces</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut et actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-flag me-2"></i>
                        Statut
                    </h5>
                </div>
                <div class="card-body text-center">
                    @switch($appartement->statut)
                        @case('libre')
                            <div class="text-success mb-3">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                            <h4 class="text-success">Libre</h4>
                            <p class="text-muted">Appartement disponible</p>
                            @break
                        @case('occupe')
                            <div class="text-warning mb-3">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <h4 class="text-warning">Occupé</h4>
                            <p class="text-muted">Appartement occupé</p>
                            @break
                        @case('travaux')
                            <div class="text-info mb-3">
                                <i class="fas fa-tools fa-3x"></i>
                            </div>
                            <h4 class="text-info">En travaux</h4>
                            <p class="text-muted">Rénovation en cours</p>
                            @break
                        @case('reserve')
                            <div class="text-secondary mb-3">
                                <i class="fas fa-clock fa-3x"></i>
                            </div>
                            <h4 class="text-secondary">Réservé</h4>
                            <p class="text-muted">En cours de négociation</p>
                            @break
                        @case('maintenance')
                            <div class="text-danger mb-3">
                                <i class="fas fa-wrench fa-3x"></i>
                            </div>
                            <h4 class="text-danger">Maintenance</h4>
                            <p class="text-muted">Maintenance technique</p>
                            @break
                    @endswitch
                </div>
            </div>

            <!-- Informations du bloc -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Informations du bloc
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Immeuble</label>
                        <p class="mb-0"><strong>{{ $appartement->bloc->immeuble->nom }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Bloc</label>
                        <p class="mb-0"><strong>{{ $appartement->bloc->nom }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Adresse</label>
                        <p class="mb-0">{{ $appartement->bloc->immeuble->adresse }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('promoteur.appartements.edit', $appartement->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Modifier l'appartement
                    </a>
                    
                    @if($appartement->statut !== 'occupe')
                        <form method="POST" action="{{ route('promoteur.appartements.destroy', $appartement->id) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet appartement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-danger w-100" disabled title="Impossible de supprimer un appartement occupé">
                            <i class="fas fa-trash me-2"></i>Supprimer (Bloqué)
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Historique (optionnel pour plus tard) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Informations complémentaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Créé le :</strong> {{ $appartement->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dernière modification :</strong> {{ $appartement->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($appartement->proprietaire)
                        <hr>
                        <h6>Propriétaire actuel</h6>
                        <p>{{ $appartement->proprietaire->nom }} {{ $appartement->proprietaire->prenom }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection