<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir votre Abonnement - SmartSyndic</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    @php
        // Récupération du vrai promoteur_id (sans affichage debug)
        $promoteur = App\Models\Promoteur::where('user_id', Auth::id())->first();
        if (!$promoteur) {
            abort(404, 'Promoteur non trouvé. Contactez l\'administrateur.');
        }
        $vraiPromoteurId = $promoteur->id;
    @endphp
    
    <style>
        /* Configuration ultra-moderne */
        body {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a3e 25%, #2d2d4f 50%, #1a1a3e 75%, #0f0f23 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Fond animé moderne */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 50%, rgba(23, 59, 97, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(253, 137, 22, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(16, 185, 129, 0.2) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* Navigation moderne */
        .navbar-modern {
            background: rgba(23, 59, 97, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(253, 137, 22, 0.3);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .user-info {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* Hero Section ultra-moderne */
        .hero-section {
            padding: 4rem 0;
            text-align: center;
            position: relative;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ffffff 0%, #FD8916 50%, #FF9933 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 3rem;
            font-weight: 400;
        }

        .hero-features {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .hero-feature {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(253, 137, 22, 0.3);
            border-radius: 20px;
            padding: 1.5rem 2.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .hero-feature::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(253, 137, 22, 0.2), transparent);
            transition: left 0.5s;
        }

        .hero-feature:hover::before {
            left: 100%;
        }

        .hero-feature:hover {
            transform: translateY(-5px);
            border-color: rgba(253, 137, 22, 0.6);
            box-shadow: 0 20px 40px rgba(253, 137, 22, 0.2);
        }

        /* Plans Container moderne */
        .plans-container {
            padding: 2rem 0;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Cards des plans ultra-modernes */
        .plan-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--plan-color), transparent);
            transition: all 0.3s ease;
        }

        .plan-card.starter { --plan-color: #173B61; }
        .plan-card.pro { --plan-color: #10b981; }
        .plan-card.business { --plan-color: #FD8916; }
        .plan-card.enterprise { --plan-color: #F0050F; }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            border-color: var(--plan-color);
        }

        .plan-card:hover::before {
            height: 4px;
            background: var(--plan-color);
        }

        .plan-card.popular {
            transform: scale(1.03);
            border: 2px solid #FD8916;
            box-shadow: 0 25px 50px rgba(253, 137, 22, 0.2);
        }

        .plan-card.popular:hover {
            transform: scale(1.03) translateY(-10px);
        }

        .popular-badge {
            position: absolute;
            top: -1px;
            right: -1px;
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 0 20px 0 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 10;
        }

        /* Header des plans */
        .plan-header {
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            color: white;
        }

        .plan-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1.5rem;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            position: relative;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .plan-icon.starter { background: linear-gradient(135deg, #173B61 0%, #17616E 100%); }
        .plan-icon.pro { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
        .plan-icon.business { background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%); }
        .plan-icon.enterprise { background: linear-gradient(135deg, #F0050F 0%, #ef4444 100%); }

        .plan-name {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .plan-duration {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }

        /* Section prix moderne */
        .plan-price {
            padding: 1.5rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .price-display {
            font-size: 3rem;
            font-weight: 900;
            color: white;
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: -2px;
        }

        .price-currency {
            font-size: 1.2rem;
            color: #FD8916;
            vertical-align: top;
        }

        .price-period {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .savings-badge {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-top: 1rem;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        /* Fonctionnalités */
        .plan-features {
            padding: 1.5rem;
            color: white;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            color: #FD8916;
            transform: translateX(5px);
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        }

        .feature-icon i {
            font-size: 0.7rem;
            color: white;
        }

        /* Boutons ultra-modernes */
        .plan-action {
            padding: 0 1.5rem 1.5rem;
        }

        .btn-select {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: none;
            border-radius: 15px;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            color: white;
        }

        .btn-select::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        .btn-select:hover::before {
            left: 100%;
        }

        .btn-select:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .btn-select.starter {
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            box-shadow: 0 10px 25px rgba(23, 59, 97, 0.3);
        }

        .btn-select.pro {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-select.business {
            background: linear-gradient(135deg, #FD8916 0%, #FF9933 100%);
            box-shadow: 0 10px 25px rgba(253, 137, 22, 0.3);
        }

        .btn-select.enterprise {
            background: linear-gradient(135deg, #F0050F 0%, #ef4444 100%);
            box-shadow: 0 10px 25px rgba(240, 5, 15, 0.3);
        }

        /* Section garanties moderne */
        .guarantees-section {
            margin-top: 5rem;
            padding: 4rem 0;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 3rem;
            letter-spacing: -1px;
        }

        .guarantees-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .guarantee-item {
            text-align: center;
            padding: 3rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: all 0.3s ease;
            color: white;
        }

        .guarantee-item:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(253, 137, 22, 0.5);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .guarantee-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border-radius: 20px;
            background: linear-gradient(135deg, #173B61 0%, #17616E 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 15px 35px rgba(23, 59, 97, 0.4);
        }

        .guarantee-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .guarantee-desc {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Bouton retour moderne */
        .btn-return {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 24px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-return:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #FD8916;
            color: white;
            transform: translateY(-2px);
        }

        /* Loading state */
        .btn-loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .plans-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 0 1rem;
            }

            .plan-card.popular {
                transform: none;
            }

            .price-display {
                font-size: 2.5rem;
            }

            .hero-features {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation moderne -->
    <nav class="navbar-modern">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-logo">
                    <i class="fas fa-building me-2"></i>SmartSyndic
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-info">{{ Auth::user()->name ?? 'Promoteur' }}</div>
                    <a href="{{ route('promoteur.dashboard') }}" class="btn-return">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Choisissez votre Plan</h1>
            <p class="hero-subtitle">Des solutions premium pour révolutionner votre gestion immobilière</p>

            <div class="hero-features">
                <div class="hero-feature">
                    <i class="fas fa-shield-alt me-2"></i>
                    <span>Sécurité Premium</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-bolt me-2"></i>
                    <span>Activation Instantanée</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-crown me-2"></i>
                    <span>Support VIP 24/7</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Plans -->
    <section class="plans-container">
        <div class="container">
            <div class="plans-grid">
                @php
                    $planIcons = [
                        '1_mois' => ['icon' => 'fas fa-rocket', 'class' => 'starter'],
                        '3_mois' => ['icon' => 'fas fa-star', 'class' => 'pro'],
                        '6_mois' => ['icon' => 'fas fa-gem', 'class' => 'business', 'popular' => true],
                        '12_mois' => ['icon' => 'fas fa-infinity', 'class' => 'enterprise']
                    ];
                @endphp

                @foreach($prixAbonnements as $prix)
                    @php
                        $planConfig = $planIcons[$prix->type_abonnement] ?? ['icon' => 'fas fa-star', 'class' => 'starter'];
                        $isPopular = isset($planConfig['popular']) && $planConfig['popular'];
                    @endphp

                    <div class="plan-card {{ $planConfig['class'] }} {{ $isPopular ? 'popular' : '' }}">
                        @if($isPopular)
                            <div class="popular-badge">
                                <i class="fas fa-crown me-1"></i>Populaire
                            </div>
                        @endif

                        <div class="plan-header">
                            <div class="plan-icon {{ $planConfig['class'] }}">
                                <i class="{{ $planConfig['icon'] }}"></i>
                            </div>
                            <h3 class="plan-name">{{ $prix->nom }}</h3>
                            <p class="plan-duration">{{ $prix->duree_mois }} {{ $prix->duree_mois > 1 ? 'Mois' : 'Mois' }}</p>
                        </div>

                        <div class="plan-price">
                            <div class="price-display">
                                <span class="price-currency">€</span>{{ number_format($prix->prix, 0, ',', ' ') }}
                            </div>
                            <p class="price-period">pour {{ $prix->duree_mois }} mois</p>
                            @if($prix->pourcentage_economie > 0)
                                <div class="savings-badge">Économisez {{ $prix->pourcentage_economie }}%</div>
                            @endif
                        </div>

                        <div class="plan-features">
                            @php
                                $features = [
                                    '1_mois' => ['Gestion de base complète', 'Support par email', 'Rapports mensuels'],
                                    '3_mois' => ['Gestion avancée', 'Support prioritaire', 'Rapports détaillés'],
                                    '6_mois' => ['Gestion complète', 'Support 24/7', 'Analytics avancés'],
                                    '12_mois' => ['Gestion illimitée', 'Manager dédié', 'Rapports personnalisés']
                                ];
                                $planFeatures = $features[$prix->type_abonnement] ?? $features['1_mois'];
                            @endphp

                            @foreach($planFeatures as $feature)
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach

                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>{{ $prix->max_immeubles == 999 ? 'Immeubles illimités' : $prix->max_immeubles . ' immeuble' . ($prix->max_immeubles > 1 ? 's' : '') . ' inclus' }}</span>
                            </div>
                        </div>

                        <div class="plan-action">
                            <button class="btn-select {{ $planConfig['class'] }}"
                                onclick="selectPlan('{{ $prix->type_abonnement }}', {{ $prix->prix }})">
                                <i class="{{ $planConfig['icon'] }} me-2"></i>Choisir {{ $prix->nom }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section garanties -->
    <section class="guarantees-section">
        <div class="container">
            <h3 class="section-title">Pourquoi SmartSyndic ?</h3>
            <div class="guarantees-grid">
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="guarantee-title">Sécurité Premium</h5>
                    <p class="guarantee-desc">Chiffrement militaire SSL 256 bits et conformité RGPD européenne complète</p>
                </div>
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="guarantee-title">Activation Ultra-Rapide</h5>
                    <p class="guarantee-desc">Accès instantané en moins de 30 secondes après validation du paiement</p>
                </div>
                <div class="guarantee-item">
                    <div class="guarantee-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h5 class="guarantee-title">Support VIP</h5>
                    <p class="guarantee-desc">Équipe d'experts dédiée disponible 24/7 avec temps de réponse garanti</p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPlan(planType, amount) {
            console.log('Sélection plan:', planType, 'Montant:', amount);

            // Empêcher tout comportement par défaut
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const button = event.target.closest('.btn-select');
            if (!button) {
                console.error('Bouton non trouvé');
                return false;
            }

            const originalText = button.innerHTML;

            // Animation loading
            button.disabled = true;
            button.classList.add('btn-loading');
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';

            // Préparer les données avec le BON promoteur_id
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
            formData.append('type_abonnement', planType);
            formData.append('montant', amount);
            formData.append('promoteur_id', '{{ $vraiPromoteurId }}'); // Le vrai promoteur_id
            formData.append('immeuble_id', '{{ $immeuble_id ?? "" }}');

            console.log('Envoi des données:', Object.fromEntries(formData));

            // Envoi avec fetch
            fetch('{{ route("promoteur.abonnements.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Réponse status:', response.status);

                if (response.redirected) {
                    console.log('Redirection détectée vers:', response.url);
                    window.location.href = response.url;
                    return;
                }

                // Vérifier le type de contenu
                const contentType = response.headers.get('content-type');
                console.log('Content-Type:', contentType);

                if (response.ok) {
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text();
                    }
                } else {
                    return response.json().then(errorData => {
                        throw new Error(errorData.error || 'Erreur serveur: ' + response.status);
                    }).catch(() => {
                        throw new Error('Erreur serveur: ' + response.status);
                    });
                }
            })
            .then(data => {
                console.log('Données reçues:', data);

                if (typeof data === 'object' && data.success && data.redirectUrl) {
                    console.log('Redirection vers:', data.redirectUrl);
                    window.location.href = data.redirectUrl;
                } else if (typeof data === 'string') {
                    console.log('Réponse HTML reçue');
                } else {
                    console.log('Réponse inattendue:', data);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);

                // Afficher l'erreur à l'utilisateur
                const errorMsg = error.message || 'Une erreur inconnue est survenue';

                // Créer une alerte moderne
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <strong>Erreur!</strong> ${errorMsg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);

                // Supprimer l'alerte après 5 secondes
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 5000);

                // Restaurer le bouton
                button.disabled = false;
                button.classList.remove('btn-loading');
                button.innerHTML = originalText;
            });

            return false;
        }

        // Test au chargement
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Page chargée - Système abonnement prêt');

            // Vérifier la présence du token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.warn('Token CSRF manquant - ajoutez <meta name="csrf-token" content="{{ csrf_token() }}"> dans le head');
            } else {
                console.log('Token CSRF présent:', csrfToken.getAttribute('content').substring(0, 10) + '...');
            }

            // Vérifier les routes
            console.log('Route process:', '{{ route("promoteur.abonnements.process") }}');
        });
    </script>
</body>

</html>