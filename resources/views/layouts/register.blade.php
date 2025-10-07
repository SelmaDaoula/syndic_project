<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Inscription') - SmartSyndic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Palette simplifiée et professionnelle */
            --primary: #173B61;               /* Bleu principal */
            --primary-light: #7697A0;         /* Bleu-gris pour textes */
            --accent: #FD8916;                /* Orange pour boutons */
            
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
            padding: 1rem 0.5rem;
            line-height: 1.4;
        }

        /* Container compact */
        .register-container {
            width: 100%;
            max-width: 500px;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            animation: slideInUp 0.4s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header simple */
        .register-header {
            background: var(--primary);
            color: var(--white);
            padding: 1rem;
            text-align: center;
        }

        .brand-logo-mini {
            width: 36px;
            height: 36px;
         /*   background: rgba(255, 255, 255, 0.15);*/
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
        }

        /* CSS pour le logo mini dans register */
        .register-header .logo-img-mini {
            width: 70px;
            height: 70px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* Logo blanc pour contraster avec le fond bleu */
        }

        /* Si votre logo est déjà blanc/clair, ajoutez la classe "colored" à l'img */
        .register-header .logo-img-mini.colored {
            filter: none;
        }

        /* Animation douce */
        .register-header:hover .logo-img-mini {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        .header-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .header-subtitle {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* Corps compact */
        .register-body {
            padding: 1rem;
        }

        /* Formulaire en grille */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .form-section {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 0.75rem;
            transition: border-color 0.2s ease;
        }

        .form-section:focus-within {
            border-color: var(--primary);
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .section-icon {
            width: 14px;
            height: 14px;
            background: var(--primary);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
        }

        /* Inputs simples */
        .form-group {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.6rem 0.6rem 1.8rem;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-sm);
            background: var(--white);
            color: var(--gray-900);
            font-size: 0.8rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(23, 59, 97, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--error);
        }

        .input-icon {
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.7rem;
            z-index: 2;
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        /* Select simple */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            font-size: 0.7rem;
        }

        .form-control.select {
            appearance: none;
            padding-right: 1.8rem;
        }

        /* Sections pleine largeur */
        .full-width {
            grid-column: 1 / -1;
        }

        /* Spécialité conditionnelle */
        .specialite-section {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .specialite-section.show {
            opacity: 1;
            max-height: 80px;
        }

        /* Mots de passe */
        .password-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Indicateur de force simple */
        .password-strength {
            margin-top: 0.25rem;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .password-strength.show {
            opacity: 1;
        }

        .strength-bar {
            width: 100%;
            height: 2px;
            background: var(--gray-200);
            border-radius: 1px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            background: var(--error);
            transition: all 0.2s ease;
            border-radius: 1px;
        }

        .strength-fill.weak { background: var(--error); width: 25%; }
        .strength-fill.medium { background: var(--accent); width: 50%; }
        .strength-fill.good { background: var(--primary-light); width: 75%; }
        .strength-fill.strong { background: var(--success); width: 100%; }

        .strength-text {
            font-size: 0.7rem;
            font-weight: 500;
        }

        /* Conditions simples */
        .terms-section {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 0.75rem;
            margin: 0.75rem 0 0.5rem 0;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .form-check-input {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-sm);
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--white);
            flex-shrink: 0;
            margin-top: 0.125rem;
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
            font-size: 0.75rem;
            cursor: pointer;
            line-height: 1.2;
        }

        /* Bouton simple */
        .btn-register {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--accent);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            background: #e07a14;
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        /* Liens simples */
        .auth-links {
            text-align: center;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--gray-200);
        }

        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.75rem;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .auth-link:hover {
            color: var(--primary-light);
        }

        /* Alerts simples */
        .alert {
            padding: 0.5rem;
            border-radius: var(--radius-sm);
            margin-bottom: 0.75rem;
            border: none;
            font-size: 0.75rem;
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
        @media (max-width: 576px) {
            body {
                padding: 0.5rem 0.25rem;
            }

            .register-container {
                max-width: none;
            }

            .register-header,
            .register-body {
                padding: 0.75rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .password-row {
                grid-template-columns: 1fr;
            }

            .form-section {
                padding: 0.5rem;
            }

            .header-title {
                font-size: 0.95rem;
            }

            .register-header .logo-img-mini {
                width: 65px;
                height: 65px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="register-container">
        <!-- Header simple -->
        <div class="register-header">
            <div class="brand-logo-mini">
                <img src="{{ asset('images/logo.png') }}" alt="SmartSyndic" class="logo-img-mini colored">
            </div>
            <h1 class="header-title">@yield('header-title', 'Rejoignez SmartSyndic')</h1>
            <p class="header-subtitle">@yield('header-subtitle', 'Création de compte')</p>
        </div>

        <!-- Corps compact -->
        <div class="register-body">
            <!-- Messages Flash -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    @if($errors->count() == 1)
                        {{ $errors->first() }}
                    @else
                        <ul class="mb-0">
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
            @yield('content')

            <!-- Liens d'authentification -->
            @hasSection('auth-links')
                <div class="auth-links">
                    @yield('auth-links')
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>