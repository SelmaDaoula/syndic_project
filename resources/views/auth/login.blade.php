@extends('layouts.auth')

@section('title', 'Connexion')

@section('header-title', 'Connexion')
@section('header-subtitle', 'Accédez à votre espace personnel')

@section('content')
<form method="POST" action="{{ route('user.login.submit') }}">
    @csrf
    
    {{-- Email --}}
    <div class="input-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" 
               placeholder="Adresse email"
               value="{{ old('email') }}" 
               required 
               autofocus>
    </div>

    {{-- Mot de passe --}}
    <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Mot de passe"
               required>
    </div>

    {{-- Se souvenir de moi --}}
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">
            Se souvenir de moi
        </label>
    </div>

    {{-- Bouton de connexion --}}
    <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-sign-in-alt me-2"></i>
        Se connectera
    </button>
</form>
@endsection

@section('auth-links')
{{-- Mot de passe oublié --}}
@if (Route::has('user.password.request'))
    <a href="{{ route('user.password.request') }}" class="auth-link">
        <i class="fas fa-key me-1"></i>
        Mot de passe oublié ?
    </a>
@endif

{{-- Inscription --}}
@if (Route::has('user.register'))
    <div class="mt-3">
        <span class="text-muted">Pas encore de compte ?</span>
        <a href="{{ route('user.register') }}" class="auth-link ms-1">
            <i class="fas fa-user-plus me-1"></i>
            Créer un compte
        </a>
    </div>
@endif
@endsection