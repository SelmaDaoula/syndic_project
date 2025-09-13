{{-- Messages d'erreur de validation --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Erreur :</strong>
        @if ($errors->count() === 1)
            {{ $errors->first() }}
        @else
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

{{-- Message de succès --}}
@if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

{{-- Message d'erreur --}}
@if (session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-times-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

{{-- Message d'information --}}
@if (session('info'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        {{ session('info') }}
    </div>
@endif

{{-- Message d'avertissement --}}
@if (session('warning'))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('warning') }}
    </div>
@endif