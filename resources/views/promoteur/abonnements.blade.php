<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir votre Abonnement - Syndic Pro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-solid: #667eea;
            --secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark: #1a1a2e;
            --light: #fafbfc;
            --border: rgba(255,255,255,0.1);
            --shadow: 0 20px 60px rgba(0,0,0,0.1);
            --shadow-hover: 0 30px 80px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0px, 0px) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        /* Navigation */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            padding: 4rem 0;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .hero-badges {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .hero-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .hero-badge:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* Subscription Cards */
        .subscription-container {
            padding: 4rem 0;
            position: relative;
        }

        .subscription-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .subscription-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .subscription-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-hover);
        }

        .subscription-card.popular {
            transform: scale(1.05);
            border: 2px solid transparent;
            background: linear-gradient(white, white) padding-box, var(--warning) border-box;
        }

        .subscription-card.popular:hover {
            transform: scale(1.08) translateY(-10px);
        }

        .card-badge {
            position: absolute;
            top: -1px;
            right: -1px;
            background: var(--warning);
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 0 24px 0 24px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        .card-header {
            padding: 2.5rem 2rem 1rem;
            text-align: center;
            position: relative;
        }

        .plan-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .plan-icon.starter { background: var(--primary); }
        .plan-icon.pro { background: var(--secondary); }
        .plan-icon.business { background: var(--warning); }
        .plan-icon.enterprise { background: var(--danger); }

        .plan-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .subscription-card:hover .plan-icon::before {
            opacity: 1;
            animation: shine 1.5s ease-in-out;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .plan-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .plan-duration {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 500;
        }

        .price-section {
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .price {
            font-size: 4rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0.5rem;
        }

        .price-currency {
            font-size: 1.5rem;
            color: var(--primary-solid);
            margin-top: 0.5rem;
        }

        .price-period {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .savings-badge {
            background: var(--success);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 1rem;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .features-list {
            padding: 0 2rem 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #555;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            color: var(--primary-solid);
            transform: translateX(5px);
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--success);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        .btn-select {
            width: calc(100% - 4rem);
            margin: 0 2rem 2rem;
            padding: 1.2rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-select.starter {
            background: var(--primary);
            color: white;
        }

        .btn-select.pro {
            background: var(--secondary);
            color: white;
        }

        .btn-select.business {
            background: var(--warning);
            color: white;
        }

        .btn-select.enterprise {
            background: var(--danger);
            color: white;
        }

        .btn-select:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .btn-select::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: all 0.6s ease;
        }

        .btn-select:hover::before {
            left: 100%;
        }

        /* Trust Section */
        .trust-section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            margin: 4rem 2rem 2rem;
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 3rem 2rem;
            box-shadow: var(--shadow);
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .trust-item {
            text-align: center;
            padding: 1.5rem;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .trust-item:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-5px);
        }

        .trust-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 20px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .trust-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .trust-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .subscription-grid {
                grid-template-columns: 1fr;
                padding: 0 1rem;
            }
            
            .subscription-card.popular {
                transform: none;
            }
            
            .price {
                font-size: 3rem;
            }
            
            .trust-section {
                margin: 2rem 1rem;
            }
        }

        /* Loading Animation */
        .loading {
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-building me-2"></i>
                SyndicPro
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-muted me-2">Connecté en tant que:</span>
                <strong>{{ Auth::user()->nom ?? 'Promoteur' }}</strong>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title loading" style="animation-delay: 0.2s">
                Choisissez votre Plan
            </h1>
            <p class="hero-subtitle loading" style="animation-delay: 0.4s">
                Découvrez nos solutions adaptées à tous vos besoins de gestion immobilière
            </p>
            
            <div class="hero-badges loading" style="animation-delay: 0.6s">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sécurisé</span>
                </div>
                <div class="hero-badge">
                    <i class="fas fa-bolt"></i>
                    <span>Activation Instantanée</span>
                </div>
                <div class="hero-badge">
                    <i class="fas fa-headset"></i>
                    <span>Support 24/7</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Subscription Plans -->
    <section class="subscription-container">
        <div class="subscription-grid">
            <!-- Plan 1 Mois -->
            <div class="subscription-card loading" style="animation-delay: 0.8s">
                <div class="card-header">
                    <div class="plan-icon starter">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="plan-name">Starter</h3>
                    <p class="plan-duration">1 Mois</p>
                </div>
                <div class="price-section">
                    <div class="price">
                        <span class="price-currency">€</span>
                        <span>59</span>
                    </div>
                    <p class="price-period">pour 1 mois</p>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Gestion de base complète</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Support par email</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Rapports mensuels</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>1 immeuble inclus</span>
                    </div>
                </div>
                <button class="btn-select starter" onclick="selectPlan('1_mois', 59)">
                    <i class="fas fa-arrow-right me-2"></i>
                    Choisir Starter
                </button>
            </div>

            <!-- Plan 3 Mois -->
            <div class="subscription-card loading" style="animation-delay: 1s">
                <div class="card-header">
                    <div class="plan-icon pro">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="plan-name">Professionnel</h3>
                    <p class="plan-duration">3 Mois</p>
                </div>
                <div class="price-section">
                    <div class="price">
                        <span class="price-currency">€</span>
                        <span>149</span>
                    </div>
                    <p class="price-period">pour 3 mois</p>
                    <div class="savings-badge">Économisez 15%</div>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Gestion avancée</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Support prioritaire</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Rapports détaillés</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>3 immeubles inclus</span>
                    </div>
                </div>
                <button class="btn-select pro" onclick="selectPlan('3_mois', 149)">
                    <i class="fas fa-arrow-right me-2"></i>
                    Choisir Pro
                </button>
            </div>

            <!-- Plan 6 Mois - Populaire -->
            <div class="subscription-card popular loading" style="animation-delay: 1.2s">
                <div class="card-badge">
                    <i class="fas fa-crown me-1"></i>
                    Populaire
                </div>
                <div class="card-header">
                    <div class="plan-icon business">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3 class="plan-name">Business</h3>
                    <p class="plan-duration">6 Mois</p>
                </div>
                <div class="price-section">
                    <div class="price">
                        <span class="price-currency">€</span>
                        <span>269</span>
                    </div>
                    <p class="price-period">pour 6 mois</p>
                    <div class="savings-badge">Économisez 25%</div>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Gestion complète</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Support 24/7</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Analytics avancés</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>5 immeubles inclus</span>
                    </div>
                </div>
                <button class="btn-select business" onclick="selectPlan('6_mois', 269)">
                    <i class="fas fa-crown me-2"></i>
                    Choisir Business
                </button>
            </div>

            <!-- Plan 12 Mois -->
            <div class="subscription-card loading" style="animation-delay: 1.4s">
                <div class="card-header">
                    <div class="plan-icon enterprise">
                        <i class="fas fa-infinity"></i>
                    </div>
                    <h3 class="plan-name">Enterprise</h3>
                    <p class="plan-duration">12 Mois</p>
                </div>
                <div class="price-section">
                    <div class="price">
                        <span class="price-currency">€</span>
                        <span>459</span>
                    </div>
                    <p class="price-period">pour 12 mois</p>
                    <div class="savings-badge">Économisez 35%</div>
                </div>
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Gestion illimitée</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Manager dédié</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Rapports personnalisés</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Immeubles illimités</span>
                    </div>
                </div>
                <button class="btn-select enterprise" onclick="selectPlan('12_mois', 459)">
                    <i class="fas fa-infinity me-2"></i>
                    Choisir Enterprise
                </button>
            </div>
        </div>
    </section>

    <!-- Trust Indicators -->
    <section class="trust-section loading" style="animation-delay: 1.6s">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="trust-title">Sécurité Maximale</h5>
                    <p class="trust-description">Vos données sont protégées avec un chiffrement SSL 256 bits et une conformité RGPD complète</p>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="trust-title">Activation Instantanée</h5>
                    <p class="trust-description">Accès immédiat à toutes les fonctionnalités dès validation de votre paiement</p>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="trust-title">Support Expert</h5>
                    <p class="trust-description">Notre équipe d'experts est disponible 24/7 pour vous accompagner dans votre réussite</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Hidden Form -->
    <form id="subscriptionForm" action="{{ route('promoteur.abonnements.process') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="type_abonnement" id="selectedPlan">
        <input type="hidden" name="montant" id="selectedAmount">
        <input type="hidden" name="promoteur_id" value="{{ Auth::id() }}">
        <input type="hidden" name="immeuble_id" value="{{ $immeuble_id ?? '' }}">
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPlan(planType, amount) {
            // Animation de sélection
            event.target.style.transform = 'scale(0.95)';
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sélection...';
            
            setTimeout(() => {
                document.getElementById('selectedPlan').value = planType;
                document.getElementById('selectedAmount').value = amount;
                document.getElementById('subscriptionForm').submit();
            }, 500);
        }

        // Animation d'entrée au chargement
        window.addEventListener('load', function() {
            const loadingElements = document.querySelectorAll('.loading');
            loadingElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.animation = `fadeInUp 0.8s ease forwards`;
                }, index * 200);
            });
        });

        // Effet parallax subtil
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('body::before');
            if (parallax) {
                const speed = scrolled * 0.5;
                parallax.style.transform = `translate3d(0, ${speed}px, 0)`;
            }
        });

        // Interaction hover avancée pour les cartes
        document.querySelectorAll('.subscription-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
    </script>
</body>
</html>