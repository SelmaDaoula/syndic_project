<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Authentification') - SmartSyndic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Palette simplifiée et professionnelle */
            --primary: #173B61;               /* Bleu principal uniquement pour accents */
            --primary-light: #7697A0;         /* Bleu-gris pour textes secondaires */
            --accent: #FD8916;                /* Orange uniquement pour les boutons */
            
            /* Couleurs neutres dominantes */
            --white: #ffffff;
            --gray-50: #f8f9fa;
            --gray-100: #f1f3f4;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            
            /* Couleurs fonctionnelles */
            --success: #10b981;
            --error: #F0050F;
            
            /* Ombres simples */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            
            /* Rayons standards */
            --radius-sm: 6px;
            --radius: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.6;
        }

        /* Container principal */
        .auth-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 600px;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Barre latérale simple */
        .auth-sidebar {
            flex: 1;
            background: var(--primary);
            color: var(--white);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sidebar-content {
            text-align: center;
        }

        .logo-section {
            margin-bottom: 6rem;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
         /*    background: rgba(255, 255, 255, 0.1); */
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        /* CSS pour le logo principal */
        .logo-img {
            width: 300px;
            height: 300px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* Blanc sur fond bleu */
        }

        /* Si votre logo est déjà blanc/clair, ajoutez la classe "colored" à l'img */
        .logo-img.colored {
            filter: none;
        }

        .brand-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .brand-tagline {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .features-list {
            list-style: none;
            margin-top: 2rem;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .features-list li i {
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.7rem;
        }

        /* Formulaire */
        .auth-form {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            font-size: 0.95rem;
            color: var(--gray-600);
            font-weight: 400;
        }

        /* Inputs simples et propres */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 0.875rem 0.875rem 3rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            background: var(--white);
            color: var(--gray-900);
            font-size: 0.95rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(23, 59, 97, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(240, 5, 15, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.9rem;
            z-index: 2;
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        /* Checkbox simple */
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .form-check-input {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-sm);
            margin-right: 0.75rem;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--white);
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:checked::after {
            content: '\2713';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: bold;
        }

        .form-check-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Bouton principal simple */
        .btn-primary {
            width: 100%;
            padding: 0.875rem 2rem;
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #e07a14;
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Liens simples */
        .auth-links {
            text-align: center;
            margin-top: 2rem;
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .auth-link:hover {
            color: var(--primary-light);
        }

        .divider {
            margin: 1.5rem 0;
            text-align: center;
            position: relative;
            color: var(--gray-400);
            font-size: 0.85rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gray-200);
            z-index: 1;
        }

        .divider span {
            background: var(--white);
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }

        /* Alerts simples */
        .alert {
            padding: 0.875rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            border: none;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: #fef2f2;
            color: var(--error);
            border-left: 3px solid var(--error);
        }

        .alert-success {
            background: #f0fdf4;
            color: var(--success);
            border-left: 3px solid var(--success);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                margin: 1rem;
                max-width: none;
                min-height: auto;
            }

            .auth-sidebar {
                padding: 2rem;
            }

            .auth-form {
                padding: 2rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .brand-name {
                font-size: 1.4rem;
            }

            .features-list {
                display: none;
            }

            .logo-img {
                width: 200px;
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }

            .auth-sidebar,
            .auth-form {
                padding: 1.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <!-- Barre latérale descriptive -->
        <div class="auth-sidebar">
            <div class="sidebar-content">
                <div class="logo-section">
                    <div class="logo-icon">
                        <img src="{{ asset('images/logo.png') }}" alt="SmartSyndic" class="logo-img colored">
                    </div>
                  <!--  <h1 class="brand-name">SmartSyndic</h1>
                    <p class="brand-tagline">Gestion intelligente des syndics</p> -->
                </div>

                <ul class="features-list">
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>Sécurité et confidentialité garanties</span>
                    </li>
                    <li>
                        <i class="fas fa-chart-line"></i>
                        <span>Tableaux de bord intuitifs</span>
                    </li>
                    <li>
                        <i class="fas fa-mobile-alt"></i>
                        <span>Accessible sur tous vos appareils</span>
                    </li>
                    <li>
                        <i class="fas fa-users"></i>
                        <span>Collaboration simplifiée</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="auth-form">
            <div class="form-header">
                <h2 class="form-title">@yield('form-title', 'Connexion')</h2>
                <p class="form-subtitle">@yield('form-subtitle', 'Accédez à votre espace personnel')</p>
            </div>

            <!-- Messages Flash -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreur :</strong>
                    @if($errors->count() == 1)
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

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contenu du formulaire -->
            @yield('form-content')

            <!-- Liens d'authentification -->
            @hasSection('auth-links')
                <div class="auth-links">
                    @yield('auth-links')
                </div>
            @endif

            <!-- Lien administrateur -->
            <div class="divider">
                <span>Accès spécialisé</span>
            </div>
            <div class="text-center">
                <a href="/admin/login" class="auth-link">
                    <i class="fas fa-shield-alt"></i>
                    Espace administrateur
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>