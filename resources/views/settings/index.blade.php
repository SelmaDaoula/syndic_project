@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center fade-in-up">
                    <div>
                        <h1 class="page-title">Paramètres</h1>
                        <p class="page-subtitle">Gérez vos préférences</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-modern">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
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

        <!-- Interface compacte -->
        <div class="row g-4">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Thème -->
                    <div class="settings-card fade-in-up">
                        <div class="settings-header">
                            <i class="fas fa-palette settings-icon"></i>
                            <h3>Apparence</h3>
                        </div>
                        <div class="settings-content">
                            <div class="theme-selector">
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="light" checked>
                                    <span class="theme-preview light">
                                        <i class="fas fa-sun"></i>
                                        <span>Clair</span>
                                    </span>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="dark">
                                    <span class="theme-preview dark">
                                        <i class="fas fa-moon"></i>
                                        <span>Sombre</span>
                                    </span>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="auto">
                                    <span class="theme-preview auto">
                                        <i class="fas fa-adjust"></i>
                                        <span>Auto</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="settings-card fade-in-up">
                        <div class="settings-header">
                            <i class="fas fa-bell settings-icon"></i>
                            <h3>Notifications</h3>
                        </div>
                        <div class="settings-content">
                            <div class="setting-row">
                                <span class="setting-name">Notifications email</span>
                                <label class="switch">
                                    <input type="checkbox" name="email_notifications" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="setting-row">
                                <span class="setting-name">Rappels échéance</span>
                                <label class="switch">
                                    <input type="checkbox" name="deadline_reminders" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Préférences -->
                    <div class="settings-card fade-in-up">
                        <div class="settings-header">
                            <i class="fas fa-cog settings-icon"></i>
                            <h3>Préférences</h3>
                        </div>
                        <div class="settings-content">
                            <div class="setting-row">
                                <span class="setting-name">Langue</span>
                                <select name="language" class="form-select-compact">
                                    <option value="fr" selected>Français</option>
                                    <option value="en">English</option>
                                    <option value="ar">العربية</option>
                                </select>
                            </div>
                            <div class="setting-row">
                                <span class="setting-name">Format date</span>
                                <select name="date_format" class="form-select-compact">
                                    <option value="dd/mm/yyyy" selected>DD/MM/YYYY</option>
                                    <option value="mm/dd/yyyy">MM/DD/YYYY</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="settings-actions">
                        <button type="submit" class="btn btn-success-modern">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar rapide -->
            <div class="col-lg-4">
                <div class="quick-settings fade-in-up">
                    <h4>Actions rapides</h4>
                    <div class="quick-actions-list">
                        <button class="quick-action-btn" onclick="showPasswordModal()">
                            <i class="fas fa-key"></i>
                            <span>Changer mot de passe</span>
                        </button>
                        <button class="quick-action-btn" onclick="exportData()">
                            <i class="fas fa-download"></i>
                            <span>Exporter données</span>
                        </button>
                        <a href="{{ route('notifications.index') }}" class="quick-action-btn">
                            <i class="fas fa-bell"></i>
                            <span>Voir notifications</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mode sombre simple
        document.addEventListener('DOMContentLoaded', function() {
            const themeInputs = document.querySelectorAll('input[name="theme"]');
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            // Appliquer thème sauvé
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.querySelector(`input[value="${savedTheme}"]`).checked = true;
            
            // Écouter changements
            themeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.checked) {
                        const theme = this.value === 'auto' ? 
                            (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : 
                            this.value;
                        
                        document.documentElement.setAttribute('data-theme', theme);
                        localStorage.setItem('theme', this.value);
                        
                        // Animation
                        document.body.style.transition = 'all 0.3s ease';
                        setTimeout(() => document.body.style.transition = '', 300);
                    }
                });
            });
        });

        function showPasswordModal() {
            // Redirection vers profil ou modal
            window.location.href = '{{ route("profile.show") }}';
        }

        function exportData() {
            alert('Export des données - Fonctionnalité à implémenter');
        }
    </script>

    <style>
        /* Variables thème */
        :root {
            --bg-primary: #FFEBD0;
            --bg-card: rgba(255, 255, 255, 0.95);
            --text-primary: #173B61;
            --text-secondary: #7697A0;
            --border: rgba(255,255,255,0.2);
            --accent: #FD8916;
        }

        [data-theme="dark"] {
            --bg-primary: #1a1a2e;
            --bg-card: rgba(26, 26, 46, 0.95);
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --border: rgba(255,255,255,0.1);
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        /* Header */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-secondary);
            margin: 0;
        }

        /* Boutons */
        .btn-outline-modern {
            background: transparent;
            border: 2px solid var(--accent);
            color: var(--accent);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline-modern:hover {
            background: var(--accent);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, #10b981, #34d399);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        /* Alertes */
        .alert-success-modern {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: #065f46;
        }

        .alert-icon-wrapper.success {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cards settings */
        .settings-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 16px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .settings-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .settings-icon {
            color: var(--accent);
            font-size: 1.2rem;
        }

        .settings-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .settings-content {
            padding: 1.5rem;
        }

        /* Sélecteur de thème */
        .theme-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .theme-option input {
            display: none;
        }

        .theme-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--bg-card);
        }

        .theme-preview:hover {
            border-color: var(--accent);
        }

        .theme-option input:checked + .theme-preview {
            border-color: var(--accent);
            background: rgba(253, 137, 22, 0.1);
        }

        /* Rows de paramètres */
        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }

        .setting-row:last-child {
            border-bottom: none;
        }

        .setting-name {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* Switch toggle */
        .switch {
            position: relative;
            width: 50px;
            height: 28px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ccc;
            border-radius: 28px;
            transition: 0.3s;
        }

        .switch .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        .switch input:checked + .slider {
            background: var(--accent);
        }

        .switch input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* Select compact */
        .form-select-compact {
            padding: 6px 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-card);
            color: var(--text-primary);
            min-width: 120px;
        }

        /* Actions */
        .settings-actions {
            text-align: center;
            padding: 1rem 0;
        }

        /* Sidebar rapide */
        .quick-settings {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .quick-settings h4 {
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            font-size: 1.1rem;
        }

        .quick-actions-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            background: rgba(253, 137, 22, 0.1);
            border-color: var(--accent);
            color: var(--text-primary);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { 
            animation: fadeInUp 0.6s ease-out; 
        }

        .slide-down { 
            animation: slideDown 0.4s ease-out; 
        }

        /* Responsive */
        @media (max-width: 991px) {
            .theme-selector {
                grid-template-columns: 1fr;
            }
            
            .setting-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .settings-header {
                padding: 1rem;
            }
            
            .settings-content {
                padding: 1rem;
            }
        }
    </style>
@endsection