@extends('layouts.auth')

@section('title', 'Connexion')
@section('form-title', 'Bon retour !')
@section('form-subtitle', 'Connectez-vous pour accéder à votre espace SyndicPro')

@section('form-content')
<form method="POST" action="{{ route('user.login.submit') }}">
    @csrf
    
    {{-- Email --}}
    <div class="form-group">
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               placeholder="Adresse email"
               value="{{ old('email') }}" 
               required 
               autofocus>
        <i class="fas fa-envelope input-icon"></i>
    </div>

    {{-- Mot de passe --}}
    <div class="form-group">
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Mot de passe"
               required>
        <i class="fas fa-lock input-icon"></i>
    </div>

    {{-- Se souvenir de moi --}}
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">
            Se souvenir de moi
        </label>
    </div>

    {{-- Bouton de connexion --}}
    <button type="submit" class="btn-primary">
        <i class="fas fa-sign-in-alt"></i>
        <span>Se connecter</span>
    </button>
</form>
@endsection

@section('auth-links')
{{-- Mot de passe oublié --}}
@if (Route::has('user.password.request'))
    <div class="mb-3">
        <a href="{{ route('user.password.request') }}" class="auth-link">
            <i class="fas fa-key"></i>
            Mot de passe oublié ?
        </a>
    </div>
@endif

{{-- Inscription --}}
@if (Route::has('user.register'))
    <div>
        <span class="text-muted me-2">Pas encore de compte ?</span>
        <a href="{{ route('user.register') }}" class="auth-link">
            <i class="fas fa-user-plus"></i>
            Créer un compte
        </a>
    </div>
@endif
@endsection