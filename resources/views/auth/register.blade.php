@extends('layouts.register')

@section('title', 'Inscription')
@section('header-title', 'Rejoignez SyndicPro')
@section('header-subtitle', 'Création de compte')

@section('content')
<form method="POST" action="{{ route('user.register.submit') }}" id="registerForm">
    @csrf
    
    <!-- Grille 2 colonnes pour économiser l'espace vertical -->
    <div class="form-grid">
        <!-- Section Nom/Prénom -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-user"></i>
                </div>
                Identité
            </div>
            
            <div class="form-group">
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       name="name" 
                       placeholder="Nom complet"
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
                <i class="fas fa-user input-icon"></i>
            </div>
            
            <div class="form-group">
                <input type="text" 
                       class="form-control @error('prenom') is-invalid @enderror" 
                       name="prenom" 
                       placeholder="Prénom"
                       value="{{ old('prenom') }}">
                <i class="fas fa-user-tag input-icon"></i>
            </div>
        </div>

        <!-- Section Contact -->
        <div class="form-section">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                Contact
            </div>
            
            <div class="form-group">
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" 
                       placeholder="Email"
                       value="{{ old('email') }}" 
                       required>
                <i class="fas fa-envelope input-icon"></i>
            </div>
            
            <div class="form-group">
                <input type="tel" 
                       class="form-control @error('telephone') is-invalid @enderror" 
                       name="telephone" 
                       placeholder="Téléphone"
                       value="{{ old('telephone') }}">
                <i class="fas fa-phone input-icon"></i>
            </div>
        </div>

        <!-- Section Rôle (pleine largeur) -->
        <div class="form-section full-width">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                Profil professionnel
            </div>
            
            <div class="form-group">
                <div class="select-wrapper">
                    <select class="form-control select @error('role_id') is-invalid @enderror" 
                            name="role_id" 
                            required 
                            onchange="toggleSpecialiteField(this.value)">
                        <option value="">Sélectionnez votre rôle</option>
                        <option value="6" {{ old('role_id') == 6 ? 'selected' : '' }}>👨‍💼 Promoteur</option>
                        <option value="7" {{ old('role_id') == 7 ? 'selected' : '' }}>🏢 Syndic</option>
                        <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>🏠 Propriétaire</option>
                        <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>🔑 Locataire</option>
                        <option value="5" {{ old('role_id') == 5 ? 'selected' : '' }}>🔧 Technicien</option>
                    </select>
                </div>
                <i class="fas fa-id-badge input-icon"></i>
            </div>
        </div>

        <!-- Section Spécialité (conditionnelle, pleine largeur) -->
        <div class="form-section full-width specialite-section" id="specialite-field">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                Spécialité technique
            </div>
            
            <div class="form-group">
                <div class="select-wrapper">
                    <select class="form-control select @error('specialite') is-invalid @enderror" name="specialite">
                        <option value="">Choisissez votre spécialité</option>
                        <option value="Plomberie" {{ old('specialite') == 'Plomberie' ? 'selected' : '' }}>🚿 Plomberie</option>
                        <option value="Électricité" {{ old('specialite') == 'Électricité' ? 'selected' : '' }}>⚡ Électricité</option>
                        <option value="Climatisation" {{ old('specialite') == 'Climatisation' ? 'selected' : '' }}>❄️ Climatisation</option>
                        <option value="Maintenance générale" {{ old('specialite') == 'Maintenance générale' ? 'selected' : '' }}>🛠️ Maintenance</option>
                        <option value="Jardinage" {{ old('specialite') == 'Jardinage' ? 'selected' : '' }}>🌱 Jardinage</option>
                        <option value="Sécurité" {{ old('specialite') == 'Sécurité' ? 'selected' : '' }}>🛡️ Sécurité</option>
                    </select>
                </div>
                <i class="fas fa-wrench input-icon"></i>
            </div>
        </div>
    </div>

    <!-- Section Sécurité (pleine largeur) -->
    <div class="form-section full-width">
        <div class="section-title">
            <div class="section-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            Sécurité du compte
        </div>
        
        <div class="password-row">
            <div class="form-group">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Mot de passe"
                       required
                       id="password">
                <i class="fas fa-lock input-icon"></i>
                <div class="password-strength" id="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strength-fill"></div>
                    </div>
                    <div class="strength-text" id="strength-text"></div>
                </div>
            </div>
            
            <div class="form-group">
                <input type="password" 
                       class="form-control" 
                       name="password_confirmation" 
                       placeholder="Confirmation"
                       required
                       id="password_confirm">
                <i class="fas fa-check-circle input-icon"></i>
            </div>
        </div>
    </div>

    <!-- Conditions d'utilisation -->
    <div class="terms-section">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="terms" required>
            <label class="form-check-label" for="terms">
                J'accepte les 
                <a href="#" class="auth-link">conditions d'utilisation</a> 
                et la 
                <a href="#" class="auth-link">politique de confidentialité</a>.
            </label>
        </div>
    </div>

    <!-- Bouton d'inscription -->
    <button type="submit" class="btn-register">
        <i class="fas fa-rocket"></i>
        <span>Créer mon compte</span>
    </button>
</form>
@endsection

@section('auth-links')
<div>
    <span class="text-muted me-1">Déjà un compte ?</span>
    <a href="{{ route('user.login') }}" class="auth-link">
        <i class="fas fa-sign-in-alt"></i>
        Se connecter
    </a>
</div>
@endsection

@push('scripts')
<script>
function toggleSpecialiteField(roleId) {
    const specialiteField = document.getElementById('specialite-field');
    if (roleId == '5') { // Technicien
        specialiteField.classList.add('show');
    } else {
        specialiteField.classList.remove('show');
    }
}

function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/[0-9]/.test(password)) strength += 1;
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    return strength;
}

document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role_id"]');
    if (roleSelect && roleSelect.value == '5') {
        toggleSpecialiteField('5');
    }
    
    const passwordInput = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const strengthIndicator = document.getElementById('password-strength');
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            if (password.length > 0) {
                strengthIndicator.classList.add('show');
                
                const strength = checkPasswordStrength(password);
                strengthFill.className = 'strength-fill';
                
                if (strength <= 2) {
                    strengthFill.classList.add('weak');
                    strengthText.textContent = 'Faible';
                    strengthText.style.color = 'var(--error)';
                } else if (strength <= 3) {
                    strengthFill.classList.add('medium');
                    strengthText.textContent = 'Moyen';
                    strengthText.style.color = 'var(--warning)';
                } else if (strength <= 4) {
                    strengthFill.classList.add('good');
                    strengthText.textContent = 'Bien';
                    strengthText.style.color = 'var(--accent)';
                } else {
                    strengthFill.classList.add('strong');
                    strengthText.textContent = 'Excellent';
                    strengthText.style.color = 'var(--success)';
                }
            } else {
                strengthIndicator.classList.remove('show');
            }
        });
    }
    
    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (passwordInput.value !== passwordConfirm.value) {
                passwordConfirm.style.borderColor = 'var(--error)';
            } else if (passwordInput.value.length > 0) {
                passwordConfirm.style.borderColor = 'var(--success)';
            } else {
                passwordConfirm.style.borderColor = 'var(--gray-300)';
            }
        });
    }
});
</script>
@endpush