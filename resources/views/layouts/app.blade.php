<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - Plateforme Syndic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --text-light: #ecf0f1;
            --border-color: #e9ecef;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar avec extension automatique */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-collapsed-width);
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #34495e 100%);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 1000;
            overflow: hidden;
        }

        .sidebar:hover {
            width: var(--sidebar-width);
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem;
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header .logo {
            color: var(--text-light);
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.3s ease;
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
            margin-top: 1rem;
            color: var(--text-light);
        }

        .sidebar-header .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .sidebar-header .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Menu Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-light);
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
        }

        .sidebar:hover .nav-link .nav-text {
            opacity: 1;
            transition: opacity 0.3s ease 0.1s;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff;
            padding-left: 2rem;
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #fff;
            border-radius: 0 25px 25px 0;
            margin-right: 1rem;
        }

        .nav-link i {
            width: 20px;
            margin-right: 1rem;
            text-align: center;
        }

        .nav-badge {
            background: #e74c3c;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            margin-left: auto;
        }

        .nav-badge.warning { background: #f39c12; }
        .nav-badge.info { background: #3498db; }
        .nav-badge.primary { background: var(--primary-color); }

        /* Submenu */
        .submenu {
            display: none;
            background: rgba(0,0,0,0.2);
            border-left: 3px solid var(--primary-color);
        }

        .submenu.show {
            display: block;
        }

        .submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
        }

        /* Main Content - ajusté pour sidebar réduite par défaut */
        .main-content {
            margin-left: var(--sidebar-collapsed-width);
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Suppression des classes expanded et collapsed */

        /* Top Bar */
        .top-bar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6c757d;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-bell {
            position: relative;
            color: #6c757d;
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .notification-bell:hover {
            color: var(--primary-color);
        }

        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
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
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6c757d;
            cursor: pointer;
        }

        /* Animations */
        .nav-link, .submenu {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Divider */
        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 1rem 1.5rem;
        }

        /* Suppression du CSS du toggle button - plus nécessaire */
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo">
                <i class="fas fa-building"></i>
                <span class="ms-2">SyndicPro</span>
            </a>
            
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ App\Services\MenuService::getRoleDisplayName(auth()->user()->role_id) }}</div>
            </div>
        </div>

        <!-- Toggle Button supprimé -->

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
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar ?? '/images/default-avatar.png' }}" 
                             class="rounded-circle" width="32" height="32" alt="Avatar">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Mon Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">Paramètres</a></li>
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
        // Supprimer les fonctions de toggle - menu auto-extensible
        
        // Toggle submenu (gardé)
        function toggleSubmenu(element) {
            event.preventDefault();
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-arrow');
            
            if (submenu) {
                submenu.classList.toggle('show');
                arrow.style.transform = submenu.classList.contains('show') 
                    ? 'rotate(180deg)' 
                    : 'rotate(0deg)';
            }
        }

        // Show notifications (placeholder)
        function showNotifications() {
            alert('Fonctionnalité notifications à implémenter');
        }

        // Responsive : menu overlay sur mobile
        if (window.innerWidth <= 768) {
            document.querySelector('.sidebar').addEventListener('mouseenter', function() {
                this.style.position = 'fixed';
                this.style.zIndex = '1050';
            });
            
            document.querySelector('.sidebar').addEventListener('mouseleave', function() {
                this.style.position = 'fixed';
                this.style.zIndex = '1000';
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>