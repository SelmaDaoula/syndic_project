@extends('layouts.app')

@section('title', 'Appartements')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-door-open text-primary fs-2 me-3"></i>
                    <div>
                        <h1 class="h3 mb-1">Liste des Appartements</h1>
                        <p class="text-muted mb-0">Gestion des appartements générés</p>
                    </div>
                </div>
                <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux Immeubles
                </a>
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

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-home fs-2 mb-2"></i>
                    <h6 class="card-title">Total</h6>
                    <h3 class="mb-0">{{ $appartements->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fs-2 mb-2"></i>
                    <h6 class="card-title">Libres</h6>
                    <h3 class="mb-0">{{ $appartements->where('statut', 'libre')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-user fs-2 mb-2"></i>
                    <h6 class="card-title">Occupés</h6>
                    <h3 class="mb-0">{{ $appartements->where('statut', 'occupe')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-tools fs-2 mb-2"></i>
                    <h6 class="card-title">Travaux</h6>
                    <h3 class="mb-0">{{ $appartements->where('statut', 'travaux')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fs-2 mb-2"></i>
                    <h6 class="card-title">Réservés</h6>
                    <h3 class="mb-0">{{ $appartements->where('statut', 'reserve')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-wrench fs-2 mb-2"></i>
                    <h6 class="card-title">Maintenance</h6>
                    <h3 class="mb-0">{{ $appartements->where('statut', 'maintenance')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des appartements -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Appartements générés automatiquement
            </h5>
        </div>
        
        <div class="card-body p-0">
            @if($appartements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
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
                            @foreach($appartements as $appartement)
                                <tr>
                                    <td>
                                        <strong>{{ $appartement->bloc->immeuble->nom }}</strong>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-secondary">{{ $appartement->bloc->nom }}</span>
                                    </td>
                                    
                                    <td>
                                        <strong class="text-primary fs-5">{{ $appartement->numero }}</strong>
                                    </td>
                                    
                                    <td>{{ $appartement->type_appartement ?? 'F3' }}</td>
                                    
                                    <td>{{ $appartement->surface }} m²</td>
                                    
                                    <td>{{ $appartement->nombre_pieces }} pièces</td>
                                    
                                    <td>
                                        @switch($appartement->statut)
                                            @case('libre')
                                                <span class="badge bg-success">Libre</span>
                                                @break
                                            @case('occupe')
                                                <span class="badge bg-warning">Occupé</span>
                                                @break
                                            @case('travaux')
                                                <span class="badge bg-info">En travaux</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($appartement->statut) }}</span>
                                        @endswitch
                                    </td>
                                    
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="Voir" 
                                                    onclick="viewApartment({{ $appartement->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" title="Modifier"
                                                    onclick="editApartment({{ $appartement->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h5>Aucun appartement trouvé</h5>
                    <p class="text-muted">
                        Aucun appartement n'a été généré pour ce bloc.
                    </p>
                    <a href="{{ route('promoteur.immeubles.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux Immeubles
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($appartements->hasPages())
            <div class="card-footer">
                {{ $appartements->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Ces fonctions sont maintenant remplacées par des liens directs
// mais on les garde pour la compatibilité
function viewApartment(id) {
    window.location.href = `/promoteur/appartements/${id}`;
}

function editApartment(id) {
    window.location.href = `/promoteur/appartements/${id}/edit`;
}
</script>
@endsection