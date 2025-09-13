@extends('layouts.auth')

@section('title', 'Inscription')

@section('header-title', 'Créer un compte')
@section('header-subtitle', 'Rejoignez notre plateforme')

@section('content')
<form method="POST" action="{{ route('user.register.submit') }}">
    @csrf
    
    {{-- Nom complet --}}
    <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               name="name" 
               placeholder="Nom complet"
               value="{{ old('name') }}" 
               required 
               autofocus>
    </div>

    {{-- Prénom --}}
    <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" 
               class="form-control @error('prenom') is-invalid @enderror" 
               name="prenom" 
               placeholder="Prénom"
               value="{{ old('prenom') }}">
    </div>

    {{-- Email --}}
    <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               placeholder="Adresse email"
               value="{{ old('email') }}" 
               required>
    </div>

    {{-- Téléphone --}}
    <div class="input-group">
        <i class="fas fa-phone input-icon"></i>
        <input type="tel" 
               class="form-control @error('telephone') is-invalid @enderror" 
               name="telephone" 
               placeholder="Numéro de téléphone"
               value="{{ old('telephone') }}">
    </div>

    {{-- Rôle --}}
    <div class="input-group">
        <i class="fas fa-id-badge input-icon"></i>
        <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" required onchange="toggleSpecialiteField(this.value)">
            <option value="">Sélectionnez votre rôle</option>
            <option value="6" {{ old('role_id') == 6 ? 'selected' : '' }}>Promoteur</option>
            <option value="7" {{ old('role_id') == 7 ? 'selected' : '' }}>Syndic</option>
            <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Propriétaire</option>
            <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Locataire</option>
            <option value="5" {{ old('role_id') == 5 ? 'selected' : '' }}>Technicien</option>
        </select>
    </div>

    {{-- Spécialité (visible seulement pour technicien) --}}
    <div class="input-group" id="specialite-field" style="display: none;">
        <i class="fas fa-wrench input-icon"></i>
        <select class="form-control @error('specialite') is-invalid @enderror" name="specialite">
            <option value="">Sélectionnez votre spécialité</option>
            <option value="Plomberie" {{ old('specialite') == 'Plomberie' ? 'selected' : '' }}>Plomberie</option>
            <option value="Électricité" {{ old('specialite') == 'Électricité' ? 'selected' : '' }}>Électricité</option>
            <option value="Climatisation" {{ old('specialite') == 'Climatisation' ? 'selected' : '' }}>Climatisation</option>
            <option value="Maintenance générale" {{ old('specialite') == 'Maintenance générale' ? 'selected' : '' }}>Maintenance générale</option>
            <option value="Jardinage" {{ old('specialite') == 'Jardinage' ? 'selected' : '' }}>Jardinage</option>
            <option value="Sécurité" {{ old('specialite') == 'Sécurité' ? 'selected' : '' }}>Sécurité</option>
        </select>
    </div>

    {{-- Mot de passe --}}
    <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Mot de passe (min. 8 caractères)"
               required>
    </div>

    {{-- Confirmation mot de passe --}}
    <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" 
               class="form-control" 
               name="password_confirmation" 
               placeholder="Confirmer le mot de passe"
               required>
    </div>

    {{-- Conditions d'utilisation --}}
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="terms" required>
        <label class="form-check-label" for="terms">
            J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
        </label>
    </div>

    {{-- Bouton d'inscription --}}
    <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-user-plus me-2"></i>
        Créer mon compte
    </button>
</form>
@endsection

@push('scripts')
<script>
function toggleSpecialiteField(roleId) {
    const specialiteField = document.getElementById('specialite-field');
    if (roleId == '5') { // Technicien
        specialiteField.style.display = 'block';
    } else {
        specialiteField.style.display = 'none';
    }
}

// Afficher le champ spécialité si technicien est déjà sélectionné (après erreur de validation)
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role_id"]');
    if (roleSelect.value == '5') {
        toggleSpecialiteField('5');
    }
});
</script>
@endpush

@section('auth-links')
<div class="mt-3">
    <span class="text-muted">Déjà un compte ?</span>
    <a href="{{ route('user.login') }}" class="auth-link ms-1">
        <i class="fas fa-sign-in-alt me-1"></i>
        Se connecter
    </a>
</div>
@endsection