<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - SmartSyndic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Palette simplifiée et professionnelle */
            --primary: #173B61;               /* Bleu principal pour sidebar */
            --primary-light: #7697A0;         /* Bleu-gris pour textes secondaires */
            --accent: #FD8916;                /* Orange pour actions importantes */
            
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
            --danger: #F0050F;
            --warning: var(--accent);
            --info: var(--primary-light);
            
            /* Variables sidebar */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            
            /* Ombres simples */
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            
            /* Rayons */
            --border-radius: 0.375rem;
            --border-radius-lg: 0.5rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--gray-50);
            overflow-x: hidden;
        }

        /* Sidebar simple */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-collapsed-width);
            background: var(--primary);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .sidebar:hover {
            width: var(--sidebar-width);
            overflow-y: auto;
        }

        /* Header sidebar */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
        }

        .sidebar-header .logo {
            color: var(--white);
            font-size: 1.4rem;
            font-weight: 700;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* CSS pour le logo dans la sidebar */
        .sidebar .logo-img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* Logo blanc pour contraster avec le fond bleu */
            transition: all 0.3s ease;
        }

        /* Si votre logo est déjà blanc/clair, ajoutez la classe "colored" à l'img */
        .sidebar .logo-img.colored {
            filter: none;
        }

        /* Animation au hover de la sidebar */
        .sidebar:hover .logo-img {
            transform: scale(1.05);
        }

        .sidebar:not(:hover) .sidebar-header .logo span,
        .sidebar:not(:hover) .sidebar-header .user-info {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .sidebar-header .logo span,
        .sidebar:hover .sidebar-header .user-info {
            opacity: 1;
            transition: opacity 0.3s ease 0.1s;
        }

        .sidebar-header .user-info {
            margin-top: 0.2rem;
            color: var(--white);
            opacity: 0.9;
        }

        .sidebar-header .user-name {
            font-weight: 800;
            font-size: 0.9rem;
            color: var(--accent);
        }

        .sidebar-header .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-link .nav-text {
            opacity: 0;
            transition: opacity 0.2s ease;
            font-weight: 500;
        }

        .sidebar:hover .nav-link .nav-text {
            opacity: 1;
            transition: opacity 0.3s ease 0.1s;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            padding-left: 2rem;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: var(--white);
            border-radius: 0 25px 25px 0;
            margin-right: 1rem;
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--white);
        }

        .nav-link i {
            width: 20px;
            margin-right: 1rem;
            text-align: center;
            font-size: 1rem;
        }

        /* Badges simples */
        .nav-badge {
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.4rem;
            border-radius: 8px;
            margin-left: auto;
        }

        .nav-badge.danger { background-color: var(--danger); }
        .nav-badge.warning { background-color: var(--warning); color: var(--white); }
        .nav-badge.info { background-color: var(--info); color: var(--white); }
        .nav-badge.success { background-color: var(--success); }
        .nav-badge.primary { background-color: var(--white); color: var(--primary); }

        /* Submenu */
        .submenu {
            display: none;
            background: rgba(0, 0, 0, 0.15);
            border-left: 2px solid rgba(255, 255, 255, 0.2);
        }

        .submenu.show {
            display: block;
        }

        .submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-collapsed-width);
            min-height: 100vh;
            transition: all 0.3s ease;
            background-color: var(--gray-50);
        }

        /* Top bar simple */
        .top-bar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Notification simple */
        .notification-bell {
            position: relative;
            color: var(--gray-600);
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 0.5rem;
            border-radius: var(--border-radius);
        }

        .notification-bell:hover {
            color: var(--primary);
            background-color: var(--gray-100);
        }

        .notification-count {
            position: absolute;
            top: 0.3rem;
            right: 0.3rem;
            background: var(--danger);
            color: var(--white);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 0.65rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dropdown simple */
        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-toggle {
            border: 1px solid var(--gray-300);
            border-radius: 50%;
            padding: 0;
            transition: all 0.2s ease;
        }

        .dropdown-toggle:hover {
            border-color: var(--primary);
        }

        .dropdown-menu {
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: var(--border-radius);
            padding: 0.7rem 1rem;
            transition: all 0.15s ease-in-out;
            font-weight: 500;
            color: var(--gray-700);
        }

        .dropdown-item:hover {
            background-color: var(--gray-100);
            color: var(--gray-900);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
        }

        /* Content area */
        .content-area {
            padding: 2rem;
        }

        /* Divider */
        .nav-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.15);
            margin: 1rem 1.5rem;
        }

        /* Mobile toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--gray-600);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: all 0.2s ease;
        }

        .mobile-toggle:hover {
            background-color: var(--gray-100);
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block !important;
            }

            .top-bar {
                padding: 1rem;
            }

            .content-area {
                padding: 1rem;
            }

            .sidebar .logo-img {
                width: 80px;
                height: 80px;
            }
        }

        /* Scrollbar simple */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Animations simples */
        .nav-link, 
        .submenu,
        .notification-bell,
        .dropdown-toggle {
            transition: all 0.2s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="SmartSyndic" class="logo-img colored">
              <!--  <span class="ms-2">SmartSyndic</span> -->
            </a>
            
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ App\Services\MenuService::getRoleDisplayName(auth()->user()->role_id) }}</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            @foreach(App\Services\MenuService::getMenuItems() as $item)
                @if(isset($item['type']) && $item['type'] === 'divider')
                    <div class="nav-divider"></div>
                @else
                    <div class="nav-item">
                        <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
                           class="nav-link {{ $item['active'] ? 'active' : '' }}"
                           @if(isset($item['submenu'])) onclick="toggleSubmenu(this)" @endif>
                            <i class="{{ $item['icon'] }}"></i>
                            <span class="nav-text">{{ $item['title'] }}</span>
                            
                            @if(isset($item['badge']) && $item['badge'])
                                <span class="nav-badge {{ $item['badge']['color'] }}">
                                    {{ $item['badge']['text'] }}
                                </span>
                            @endif
                            
                            @if(isset($item['submenu']))
                                <i class="fas fa-chevron-down ms-auto submenu-arrow"></i>
                            @endif
                        </a>
                        
                        @if(isset($item['submenu']))
                            <div class="submenu {{ $item['active'] ? 'show' : '' }}">
                                @foreach($item['submenu'] as $subitem)
                                    <a href="{{ route($subitem['route']) }}" class="nav-link">
                                        <i class="{{ $subitem['icon'] }}"></i>
                                        <span class="nav-text">{{ $subitem['title'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="breadcrumb-nav">
                    @yield('breadcrumb', ucfirst(request()->segment(1)) . ' / ' . ucfirst(request()->segment(2)))
                </div>
            </div>
            
            <div class="top-actions">
                <!-- Notifications -->
                <div class="notification-bell" onclick="showNotifications()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-count">5</span>
                </div>
                
                <!-- Profil dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" 
                             class="rounded-circle" width="36" height="36" alt="Avatar">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i>Mon Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="fas fa-cog me-2"></i>Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Messages Flash -->
            @include('partials.flash-messages')
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle submenu
        function toggleSubmenu(element) {
            event.preventDefault();
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-arrow');
            
            if (submenu) {
                submenu.classList.toggle('show');
                if (arrow) {
                    arrow.style.transform = submenu.classList.contains('show') 
                        ? 'rotate(180deg)' 
                        : 'rotate(0deg)';
                }
            }
        }

        // Toggle sidebar mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Show notifications
        function showNotifications() {
            console.log('Notifications à implémenter');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const mobileToggle = document.querySelector('.mobile-toggle');
                
                if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>