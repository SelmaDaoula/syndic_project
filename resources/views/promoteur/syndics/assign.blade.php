@extends('layouts.app')

@section('title', 'Assigner un Syndic - Promoteur')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-tie text-primary fs-2 me-3"></i>
                        <div>
                            <h1 class="h3 mb-1">Assigner un Syndic</h1>
                            <p class="text-muted mb-0">Pour l'immeuble: <strong>{{ $immeuble->nom }} -
                                    {{ $immeuble->adresse }}</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux immeubles
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Erreurs :</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Syndic Actuel -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Syndic Actuel
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($immeuble->syndic_id)
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                    <i class="fas fa-user-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $immeuble->syndic->name ?? 'Nom non disponible' }}</h6>
                                    <p class="text-muted mb-0">{{ $immeuble->syndic->email ?? 'Email non disponible' }}</p>
                                </div>
                            </div>
                            <form action="{{ route('promoteur.syndics.unassign') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir retirer ce syndic ?')">
                                    <i class="fas fa-user-times me-2"></i>Retirer le Syndic
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash text-muted fs-1 mb-3"></i>
                                <h6 class="text-muted">Aucun syndic assigné</h6>
                                <p class="text-muted small">Votre immeuble n'a pas encore de syndic.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigner Nouveau Syndic -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i>Assigner un Syndic
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($syndicatsDisponibles->count() > 0)
                            <form action="{{ route('promoteur.syndics.assign') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="syndic_id" class="form-label fw-semibold">
                                        Sélectionner un Syndic <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('syndic_id') is-invalid @enderror"
                                        id="syndic_id" name="syndic_id" required>
                                        <option value="">-- Choisir un syndic --</option>
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

                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Information</h6>
                                    <ul class="mb-0 small">
                                        <li>Le syndic recevra une notification de sa nouvelle mission</li>
                                        <li>Il pourra accepter ou refuser l'assignation</li>
                                        <li>Vous pouvez changer de syndic à tout moment</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>Assigner ce Syndic
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users-slash text-muted fs-1 mb-3"></i>
                                <h6 class="text-muted">Aucun syndic disponible</h6>
                                <p class="text-muted small">Tous les syndics sont déjà assignés ou aucun compte syndic n'existe.
                                </p>
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fas fa-phone me-2"></i>Contacter l'Administrateur
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection