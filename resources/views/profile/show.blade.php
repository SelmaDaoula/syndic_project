@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header avec breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center fade-in-up">
                    <div>
                        <h1 class="page-title">Mon Profil</h1>
                        <p class="page-subtitle">Gérez vos informations personnelles et vos préférences</p>
                    </div>
                    <button id="editToggle" class="btn btn-primary-modern pulse-animation">
                        <i class="fas fa-edit me-2"></i><span>Modifier le profil</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="alert alert-danger-modern slide-down" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-wrapper error">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">{{ session('error') ?? $errors->first() }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Contenu principal -->
        <div class="row g-4">
            
            <!-- Colonne gauche - Informations principales -->
            <div class="col-lg-4">
                <div class="profile-main-card fade-in-left">
                    <!-- Header avec avatar -->
                    <div class="profile-header">
                        <div class="avatar-section" id="avatarSection">
                            <div class="avatar-wrapper">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="avatar-img">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                
                                <!-- Bouton modification avatar -->
                                <button class="avatar-edit-btn" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Informations utilisateur -->
                        <div class="user-info-section">
                            <h2 class="user-name">{{ $user->name }}</h2>
                            <p class="user-email">{{ $user->email }}</p>
                            
                            <!-- Badge rôle -->
                            <div class="role-badge-wrapper">
                                <span class="role-badge role-{{ strtolower($user->getRoleName()) }}">
                                    @php
                                        $roleIcons = [
                                            1 => 'shield-check',    // Admin
                                            3 => 'home',           // Propriétaire
                                            4 => 'key',            // Locataire
                                            5 => 'wrench',         // Technicien
                                            6 => 'building-office', // Promoteur
                                            7 => 'briefcase',      // Syndic
                                        ];
                                        $roleIcon = $roleIcons[$user->role_id] ?? 'user';
                                    @endphp
                                    <i class="fas fa-{{ $roleIcon }} me-2"></i>
                                    {{ $user->getRoleName() }}
                                </span>
                            </div>

                            <!-- Statut compte -->
                            <div class="account-status">
                                <div class="status-indicator active">
                                    <div class="status-dot"></div>
                                    <span>Compte Actif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    @if($user->isPromoteur())
                        <div class="stats-section">
                            <div class="stats-elegant">
                                <div class="stats-item">
                                    <span class="stats-number counter-animation">{{ $user->promoteur->immeubles->count() ?? 0 }}</span>
                                    <span class="stats-text">Immeubles gérés</span>
                                </div>
                                <div class="stats-divider"></div>
                                <div class="stats-item">
                                    <span class="stats-status {{ $user->promoteur->hasValidSubscription() ? 'active' : 'expired' }}">
                                        {{ $user->promoteur->hasValidSubscription() ? 'Actif' : 'Expiré' }}
                                    </span>
                                    <span class="stats-text">Statut abonnement</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions rapides -->
                    <div class="quick-actions">
                        <button class="action-btn" onclick="showPasswordModal()">
                            <div class="action-icon-wrapper">
                                <i class="fas fa-key"></i>
                            </div>
                            <span>Mot de passe</span>
                        </button>
                        <a href="{{ route('settings.index') }}" class="action-btn">
                            <div class="action-icon-wrapper">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span>Paramètres</span>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="action-btn">
                            <div class="action-icon-wrapper">
                                <i class="fas fa-bell"></i>
                            </div>
                            <span>Notifications</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="action-btn">
                            <div class="action-icon-wrapper">
                                <i class="fas fa-arrow-left"></i>
                            </div>
                            <span>Retour</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Informations détaillées -->
            <div class="col-lg-8">
                <div class="profile-details-section fade-in-right">
                    
                    <!-- Formulaire des informations personnelles -->
                    <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="details-card">
                            <div class="card-header-modern">
                                <h3 class="card-title">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Informations personnelles
                                </h3>
                            </div>
                            <div class="card-body-modern">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label class="info-label">Nom d'utilisateur</label>
                                        <input type="text" class="info-value-input editable-field" name="name" 
                                               value="{{ old('name', $user->name) }}" readonly>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Adresse email</label>
                                        <input type="email" class="info-value-input editable-field" name="email" 
                                               value="{{ old('email', $user->email) }}" readonly>
                                    </div>
                                    @if($profile)
                                        <div class="info-item">
                                            <label class="info-label">Prénom</label>
                                            <input type="text" class="info-value-input editable-field" name="prenom" 
                                                   value="{{ old('prenom', $profile->prenom) }}" readonly>
                                        </div>
                                        <div class="info-item">
                                            <label class="info-label">Nom</label>
                                            <input type="text" class="info-value-input editable-field" name="nom" 
                                                   value="{{ old('nom', $profile->nom) }}" readonly>
                                        </div>
                                        <div class="info-item">
                                            <label class="info-label">Téléphone</label>
                                            <input type="text" class="info-value-input editable-field" name="telephone" 
                                                   value="{{ old('telephone', $profile->telephone) }}" readonly>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Boutons de sauvegarde (positionnés après la première carte) -->
                        <div class="form-actions" id="formActions" style="display: none; margin-top: 2rem;">
                            <button type="submit" class="btn btn-success-modern">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                            <button type="button" class="btn btn-secondary-modern" onclick="cancelEdit()">
                                <i class="fas fa-times me-2"></i>Annuler
                            </button>
                        </div>

                        <!-- Informations spécifiques au rôle -->
                        @if($profile)
                            <div class="details-card">
                                <div class="card-header-modern">
                                    <h3 class="card-title">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>
                                        Informations {{ strtolower($user->getRoleName()) }}
                                    </h3>
                                </div>
                                <div class="card-body-modern">
                                    <div class="info-grid">
                                        @if($user->isPromoteur())
                                            <div class="info-item">
                                                <label class="info-label">Statut</label>
                                                <div class="info-value">
                                                    <span class="status-chip {{ $profile->isActive() ? 'active' : 'suspended' }}">
                                                        {{ $profile->isActive() ? 'Actif' : 'Suspendu' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <label class="info-label">Abonnement</label>
                                                <div class="info-value">
                                                    @if($profile->hasValidSubscription())
                                                        <span class="status-chip active">Valide</span>
                                                    @else
                                                        <span class="status-chip inactive">Expiré</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($user->isProprietaire())
                                            <div class="info-item">
                                                <label class="info-label">Date d'acquisition</label>
                                                <input type="date" class="info-value-input editable-field" name="date_acquisition" 
                                                       value="{{ old('date_acquisition', $profile->date_acquisition?->format('Y-m-d')) }}" readonly>
                                            </div>
                                            @if($profile->appartement)
                                                <div class="info-item">
                                                    <label class="info-label">Appartement</label>
                                                    <div class="info-value">{{ $profile->appartement->nom ?? 'Non assigné' }}</div>
                                                </div>
                                            @endif
                                        @elseif($user->isTechnicien())
                                            <div class="info-item">
                                                <label class="info-label">Spécialité</label>
                                                <input type="text" class="info-value-input editable-field" name="specialite" 
                                                       value="{{ old('specialite', $profile->specialite ?? '') }}" readonly>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    <!-- Activité récente -->
                    <div class="details-card">
                        <div class="card-header-modern">
                            <h3 class="card-title">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                Activité récente
                            </h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="activity-timeline">
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Profil consulté</div>
                                        <div class="activity-time">Maintenant</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Dernière connexion</div>
                                        <div class="activity-time">{{ $user->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">Compte créé</div>
                                        <div class="activity-time">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Input caché pour upload avatar -->
    <form id="avatarForm" method="POST" action="{{ route('profile.update.avatar') }}" enctype="multipart/form-data" style="display: none;">
        @csrf
        @method('PUT')
        <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="uploadAvatar()">
    </form>

    <!-- Modal Mot de passe -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-modern">
                <div class="modal-header">
                    <h5 class="modal-title">Changer le mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.update.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary-modern">
                            <i class="fas fa-save me-2"></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;
        let originalValues = {};

        // Basculer mode édition
        document.getElementById('editToggle').addEventListener('click', function() {
            toggleEditMode();
        });

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const button = document.getElementById('editToggle');
            const fields = document.querySelectorAll('.editable-field');
            const actions = document.getElementById('formActions');

            if (isEditMode) {
                // Sauvegarder valeurs originales
                fields.forEach(field => {
                    originalValues[field.name] = field.value;
                    field.removeAttribute('readonly');
                    field.classList.add('editing');
                });
                
                button.innerHTML = '<i class="fas fa-times me-2"></i><span>Annuler</span>';
                button.classList.remove('btn-primary-modern');
                button.classList.add('btn-secondary-modern');
                actions.style.display = 'flex';
            } else {
                cancelEdit();
            }
        }

        function cancelEdit() {
            const button = document.getElementById('editToggle');
            const fields = document.querySelectorAll('.editable-field');
            const actions = document.getElementById('formActions');

            // Restaurer valeurs originales
            fields.forEach(field => {
                field.value = originalValues[field.name] || '';
                field.setAttribute('readonly', '');
                field.classList.remove('editing');
            });

            button.innerHTML = '<i class="fas fa-edit me-2"></i><span>Modifier le profil</span>';
            button.classList.remove('btn-secondary-modern');
            button.classList.add('btn-primary-modern');
            actions.style.display = 'none';
            isEditMode = false;
        }

        // Upload avatar
        function uploadAvatar() {
            document.getElementById('avatarForm').submit();
        }

        // Modal mot de passe
        function showPasswordModal() {
            new bootstrap.Modal(document.getElementById('passwordModal')).show();
        }

        // Animation des compteurs
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter-animation');
            counters.forEach(counter => {
                const finalValue = parseInt(counter.textContent) || 0;
                let currentValue = 0;
                const increment = Math.ceil(finalValue / 20);
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        counter.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        counter.textContent = currentValue;
                    }
                }, 50);
            });
        });
    </script>

    <style>
        /* Styles identiques à votre design original */
        body {
            background-color: #FFEBD0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .fade-in-left { animation: fadeInLeft 0.6s ease-out; }
        .fade-in-right { animation: fadeInRight 0.6s ease-out; }
        .slide-down { animation: slideDown 0.4s ease-out; }
        .pulse-animation { animation: pulse 2s infinite; }

        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Header */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #173B61;
            margin: 0;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            color: #7697A0;
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Boutons */
        .btn-primary-modern {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 137, 22, 0.4);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        /* Alertes */
        .alert-success-modern, .alert-danger-modern {
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .alert-success-modern {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .alert-danger-modern {
            background: rgba(240, 5, 15, 0.1);
            color: #7f1d1d;
        }

        .alert-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-icon-wrapper.success { background: rgba(16, 185, 129, 0.2); }
        .alert-icon-wrapper.error { background: rgba(240, 5, 15, 0.2); }

        /* Card principale profil */
        .profile-main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-main-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 50%, #FD8916 100%);
        }

        .profile-header {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(23, 59, 97, 0.1);
        }

        /* Avatar */
        .avatar-section {
            margin-bottom: 2rem;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar-img, .avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(253, 137, 22, 0.3);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .avatar-placeholder {
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            border: none;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(253, 137, 22, 0.4);
            transition: all 0.3s ease;
        }

        .avatar-edit-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(253, 137, 22, 0.5);
        }

        /* Informations utilisateur */
        .user-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #173B61;
            margin: 0 0 0.5rem 0;
        }

        .user-email {
            color: #7697A0;
            margin: 0 0 1rem 0;
            font-size: 0.95rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .role-badge.role-promoteur {
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.2), rgba(23, 59, 97, 0.1));
            color: #173B61;
            border: 1px solid rgba(23, 59, 97, 0.3);
        }

        .role-badge.role-syndic {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .account-status {
            margin-bottom: 1rem;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-indicator.active {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s infinite;
        }

        /* Statistiques élégantes - Meilleur design */
        .stats-section {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(23, 59, 97, 0.1);
        }

        .stats-elegant {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #fafbfc 0%, #f8fafc 100%);
            border: 1px solid rgba(23, 59, 97, 0.1);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .stats-elegant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #173B61 0%, #FD8916 100%);
        }

        .stats-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #173B61;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .stats-status {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-status.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.1));
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .stats-status.expired {
            background: linear-gradient(135deg, rgba(240, 5, 15, 0.15), rgba(240, 5, 15, 0.1));
            color: #7f1d1d;
            border: 1px solid rgba(240, 5, 15, 0.3);
        }

        .stats-text {
            font-size: 0.8rem;
            color: #7697A0;
            font-weight: 600;
            text-align: center;
            line-height: 1.2;
        }

        .stats-divider {
            width: 1px;
            height: 50px;
            background: linear-gradient(to bottom, transparent, rgba(23, 59, 97, 0.2), transparent);
            margin: 0 1rem;
        }

        .stats-elegant:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.1);
        }

        /* Actions rapides */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            padding: 2rem;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.25rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #173B61;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(23, 59, 97, 0.05), rgba(23, 97, 110, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .action-btn:hover::before {
            opacity: 1;
        }

        .action-btn:hover {
            background: white;
            border-color: #173B61;
            color: #173B61;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .action-icon-wrapper {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
        }

        .action-btn:hover .action-icon-wrapper {
            transform: scale(1.1);
        }

        .action-btn i {
            font-size: 1.2rem;
        }

        .action-btn span {
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Section détails - Espacement équilibré */
        .profile-details-section {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* Premier espacement après "Informations personnelles" */
        .details-card:nth-child(1) {
            margin-bottom: 2.5rem;
        }

        /* Deuxième espacement après "Informations promoteur" */
        .details-card:nth-child(3) {
            margin-bottom: 2.5rem;
        }

        .form-actions {
            margin-top: 2rem;
            margin-bottom: 2.5rem;
        }

        .details-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            position: relative;
        }

        .details-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 50%, #FD8916 100%);
        }

        .card-header-modern {
            padding: 1.5rem 2rem 1rem;
            border-bottom: 1px solid rgba(23, 59, 97, 0.1);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #173B61;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .text-primary {
            color: #FD8916 !important;
        }

        .card-body-modern {
            padding: 2rem;
        }

        /* Grille d'informations */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #7697A0;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            padding: 12px 16px;
            border-radius: 10px;
            color: #173B61;
            font-weight: 500;
            border: 1px solid rgba(253, 137, 22, 0.2);
        }

        .info-value-input {
            background: linear-gradient(135deg, #FFEBD0, #FFF8E1);
            padding: 12px 16px;
            border-radius: 10px;
            color: #173B61;
            font-weight: 500;
            border: 1px solid rgba(253, 137, 22, 0.2);
            transition: all 0.3s ease;
        }

        .info-value-input[readonly] {
            cursor: default;
        }

        .info-value-input.editing {
            background: white;
            border-color: #FD8916;
            box-shadow: 0 0 0 0.2rem rgba(253, 137, 22, 0.25);
        }

        .info-value-input:focus {
            outline: none;
            background: white;
            border-color: #FD8916;
            box-shadow: 0 0 0 0.2rem rgba(253, 137, 22, 0.25);
        }

        /* Status chips */
        .status-chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-chip.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            color: #065f46;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-chip.inactive {
            background: linear-gradient(135deg, rgba(240, 5, 15, 0.2), rgba(240, 5, 15, 0.1));
            color: #7f1d1d;
            border: 1px solid rgba(240, 5, 15, 0.3);
        }

        .status-chip.suspended {
            background: linear-gradient(135deg, rgba(253, 137, 22, 0.2), rgba(253, 137, 22, 0.1));
            color: #92400e;
            border: 1px solid rgba(253, 137, 22, 0.3);
        }

        /* Timeline d'activité */
        .activity-timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: linear-gradient(135deg, #fafbfc, #f8fafc);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: white;
            border-color: #173B61;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(23, 59, 97, 0.3);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #173B61;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #7697A0;
            font-weight: 500;
        }

        /* Actions du formulaire */
        .form-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Modals */
        .modal-modern .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .modal-modern .modal-header {
            border-bottom: 1px solid rgba(23, 59, 97, 0.1);
            padding: 1.5rem 2rem 1rem;
        }

        .modal-modern .modal-title {
            font-weight: 700;
            color: #173B61;
        }

        .modal-modern .modal-body {
            padding: 2rem;
        }

        .modal-modern .modal-footer {
            border-top: 1px solid rgba(23, 59, 97, 0.1);
            padding: 1rem 2rem 1.5rem;
        }

        .modal-modern .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .modal-modern .form-control:focus {
            border-color: #FD8916;
            box-shadow: 0 0 0 0.2rem rgba(253, 137, 22, 0.25);
        }

        .modal-modern .form-label {
            font-weight: 600;
            color: #173B61;
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .quick-actions {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-elegant {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .stats-divider {
                width: 50px;
                height: 1px;
                background: linear-gradient(to right, transparent, rgba(23, 59, 97, 0.2), transparent);
                margin: 0;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .profile-details-section {
                gap: 4.5rem;
            }
        }

        @media (max-width: 576px) {
            .profile-header, .card-body-modern {
                padding: 1.5rem;
            }

            .quick-actions {
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .action-btn {
                padding: 1rem;
            }

            .action-icon-wrapper {
                width: 35px;
                height: 35px;
                margin-bottom: 0.5rem;
            }

            .action-btn span {
                font-size: 0.75rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .user-name {
                font-size: 1.3rem;
            }

            .avatar-img, .avatar-placeholder {
                width: 80px;
                height: 80px;
            }

            .avatar-edit-btn {
                width: 30px;
                height: 30px;
            }

            .profile-details-section {
                gap: 3.5rem;
            }

            .stats-elegant {
                padding: 1.25rem;
            }

            .stats-number {
                font-size: 1.8rem;
            }

            .stats-text {
                font-size: 0.75rem;
            }
        }

        /* Améliorations d'accessibilité */
        .btn:focus, .action-btn:focus {
            outline: 2px solid #FD8916;
            outline-offset: 2px;
        }

        /* Effet de brillance sur les cartes au hover */
        .details-card:hover::before {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 50%, #173B61 100%);
        }

        .profile-main-card:hover::before {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 50%, #173B61 100%);
        }
    </style>
@endsection