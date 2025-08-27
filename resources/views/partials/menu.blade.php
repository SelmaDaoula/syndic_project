{{-- resources/views/partials/menu.blade.php --}}

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-building"></i>
            SyndicPro
        </a>

        {{-- Toggle button for mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                {{-- Dashboard - commun à tous --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Tableau de bord
                    </a>
                </li>

                {{-- Menu selon le type d'utilisateur --}}
                @if(Auth::user()->user_type === 'locataire')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('locataire.factures*') ? 'active' : '' }}" 
                           href="{{ route('locataire.factures.index') }}">
                            <i class="fas fa-file-invoice"></i>
                            Mes Factures
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('locataire.tickets*') ? 'active' : '' }}" 
                           href="{{ route('locataire.tickets.index') }}">
                            <i class="fas fa-tools"></i>
                            Demandes d'intervention
                        </a>
                    </li>

                @elseif(Auth::user()->user_type === 'proprietaire')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('proprietaire.biens*') ? 'active' : '' }}" 
                           href="{{ route('proprietaire.biens.index') }}">
                            <i class="fas fa-home"></i>
                            Mes Biens
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('proprietaire.locataires*') ? 'active' : '' }}" 
                           href="{{ route('proprietaire.locataires.index') }}">
                            <i class="fas fa-users"></i>
                            Mes Locataires
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('proprietaire.finances*') ? 'active' : '' }}" 
                           href="{{ route('proprietaire.finances.index') }}">
                            <i class="fas fa-euro-sign"></i>
                            Finances
                        </a>
                    </li>

                @elseif(Auth::user()->user_type === 'technicien')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('technicien.interventions*') ? 'active' : '' }}" 
                           href="{{ route('technicien.interventions.index') }}">
                            <i class="fas fa-wrench"></i>
                            Interventions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('technicien.planning*') ? 'active' : '' }}" 
                           href="{{ route('technicien.planning.index') }}">
                            <i class="fas fa-calendar"></i>
                            Planning
                        </a>
                    </li>

                @elseif(Auth::user()->user_type === 'syndic')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Request::routeIs('syndic.immeubles*') || Request::routeIs('syndic.coproprietes*') ? 'active' : '' }}" 
                           href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-building"></i>
                            Gestion Immobilière
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('syndic.immeubles.index') }}">
                                <i class="fas fa-building"></i> Immeubles
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('syndic.coproprietes.index') }}">
                                <i class="fas fa-users"></i> Copropriétés
                            </a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('syndic.rapports*') ? 'active' : '' }}" 
                           href="{{ route('syndic.rapports.index') }}">
                            <i class="fas fa-chart-bar"></i>
                            Rapports
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('syndic.finances*') ? 'active' : '' }}" 
                           href="{{ route('syndic.finances.index') }}">
                            <i class="fas fa-calculator"></i>
                            Comptabilité
                        </a>
                    </li>

                @elseif(Auth::user()->user_type === 'promoteur')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('promoteur.projets*') ? 'active' : '' }}" 
                           href="{{ route('promoteur.projets.index') }}">
                            <i class="fas fa-project-diagram"></i>
                            Mes Projets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('promoteur.ventes*') ? 'active' : '' }}" 
                           href="{{ route('promoteur.ventes.index') }}">
                            <i class="fas fa-handshake"></i>
                            Ventes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('promoteur.suivi*') ? 'active' : '' }}" 
                           href="{{ route('promoteur.suivi.index') }}">
                            <i class="fas fa-tasks"></i>
                            Suivi Travaux
                        </a>
                    </li>
                @endif

                {{-- Notifications - commun à tous --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('notifications*') ? 'active' : '' }}" 
                       href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell"></i>
                        Notifications
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger rounded-pill">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>

            {{-- Menu utilisateur à droite --}}
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::user()->name }}
                        <span class="badge bg-secondary">
                            {{ ucfirst(Auth::user()->user_type) }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit"></i>
                                Mon Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('settings.index') }}">
                                <i class="fas fa-cog"></i>
                                Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Fil d'Ariane --}}
<nav class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    Accueil
                </a>
            </li>
            @yield('breadcrumb')
        </ol>
    </div>
</nav>

{{-- CSS personnalisé --}}
<style>
    .navbar-nav .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
    }
    
    .badge {
        font-size: 0.7em;
        margin-left: 5px;
    }
    
    .dropdown-menu {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
    }
</style>