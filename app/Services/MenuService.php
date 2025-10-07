<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class MenuService
{
    /**
     * Générer le menu selon le rôle de l'utilisateur
     */
    public static function getMenuItems()
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $menuItems = [];

        // Dashboard principal (pour tous)
        $menuItems[] = [
            'title' => 'Dashboard',
            'route' => self::getDashboardRoute($user->role_id),
            'icon' => 'fas fa-tachometer-alt',
            'active' => request()->routeIs('*.dashboard'),
            'badge' => null
        ];

        // Menu spécifique selon le rôle
        switch ($user->role_id) {
            case 6: // Promoteur
                $menuItems = array_merge($menuItems, self::getPromoteurMenu());
                break;
            case 7: // Syndic
                $menuItems = array_merge($menuItems, self::getSyndicMenu());
                break;
            case 3: // Propriétaire
                $menuItems = array_merge($menuItems, self::getProprietaireMenu());
                break;
            case 4: // Locataire
                $menuItems = array_merge($menuItems, self::getLocataireMenu());
                break;
            case 5: // Technicien
                $menuItems = array_merge($menuItems, self::getTechnicienMenu());
                break;
        }

        // Fonctionnalités communes (pour tous sauf admin)
        if ($user->role_id !== 1) {
            $menuItems = array_merge($menuItems, self::getCommonMenu());
        }

        return $menuItems;
    }

    /**
     * Menu du promoteur
     */
    private static function getPromoteurMenu()
    {
        return [
            [
                'title' => 'Mes Immeubles',
                'route' => 'promoteur.immeubles.index',
                'icon' => 'fas fa-building',
                'active' => request()->routeIs('promoteur.immeubles.*'),
                'badge' => null,
                'submenu' => [
                    [
                        'title' => 'Liste des immeubles',
                        'route' => 'promoteur.immeubles.index',
                        'icon' => 'fas fa-list'
                    ],
                    [
                        'title' => 'Créer un immeuble',
                        'route' => 'promoteur.immeubles.create',
                        'icon' => 'fas fa-plus'
                    ],
                    [
                        'title' => 'Gestion des blocs',
                        'route' => 'promoteur.blocs.index',
                        'icon' => 'fas fa-th-large'
                    ]
                ]
            ],
            [
                'title' => 'Gestion Syndics',
                'route' => 'promoteur.syndics.index',
                'icon' => 'fas fa-users-cog',
                'active' => request()->routeIs('promoteur.syndics.*'),
                'badge' => null,
                'submenu' => [
                    [
                        'title' => 'Liste des syndics',
                        'route' => 'promoteur.syndics.index',
                        'icon' => 'fas fa-list'
                    ],
                    [
                        'title' => 'Assigner syndic',
                        'route' => 'promoteur.syndics.assign',
                        'icon' => 'fas fa-user-plus'
                    ]
                ]
            ],
            [
                'title' => 'Appartements',
                'route' => 'promoteur.appartements.index',
                'icon' => 'fas fa-door-open',
                'active' => request()->routeIs('promoteur.appartements.*'),
                'badge' => null
            ],
            [
                'title' => 'Abonnements',
                'route' => 'promoteur.abonnements.index',
                'icon' => 'fas fa-credit-card',
                'active' => request()->routeIs('promoteur.abonnements.*'),
                'badge' => self::getSubscriptionBadge()
            ],
            [
                'title' => 'Rapports',
                'route' => 'promoteur.rapports.index',
                'icon' => 'fas fa-chart-line',
                'active' => request()->routeIs('promoteur.rapports.*'),
                'badge' => null,
                'submenu' => [
                    [
                        'title' => 'Rapport financier',
                        'route' => 'promoteur.rapports.financier',
                        'icon' => 'fas fa-euro-sign'
                    ],
                    [
                        'title' => 'Rapport d\'activité',
                        'route' => 'promoteur.rapports.activite',
                        'icon' => 'fas fa-tasks'
                    ]
                ]
            ]
        ];
    }

    /**
     * Menu du syndic
     */
    private static function getSyndicMenu()
    {
        return [
            [
                'title' => 'Mon Immeuble',
                'route' => 'syndic.immeubles.show',
                'icon' => 'fas fa-building',
                'active' => request()->routeIs('syndic.immeubles.*'),
                'badge' => null
            ],
            [
                'title' => 'Appartements',
                'route' => 'syndic.appartements.index',
                'icon' => 'fas fa-door-open',
                'active' => request()->routeIs('syndic.appartements.*'),
                'badge' => null
            ],
            [
                'title' => 'Résidents',
                'route' => 'syndic.residents.index',
                'icon' => 'fas fa-users',
                'active' => request()->routeIs('syndic.residents.*') || request()->routeIs('syndic.proprietaires.*') || request()->routeIs('syndic.locataires.*'),
                'badge' => null,
                'submenu' => [
                    [
                        'title' => 'Propriétaires',
                        'route' => 'syndic.proprietaires.index',
                        'icon' => 'fas fa-user-tie'
                    ],
                    [
                        'title' => 'Locataires',
                        'route' => 'syndic.locataires.index',
                        'icon' => 'fas fa-user'
                    ]
                ]
            ],
            [
                'title' => 'Tickets',
                'route' => 'syndic.tickets.index',
                'icon' => 'fas fa-ticket-alt',
                'active' => request()->routeIs('syndic.tickets.*'),
                'badge' => self::getTicketsBadge(),
                'submenu' => [
                    [
                        'title' => 'Tous les tickets',
                        'route' => 'syndic.tickets.index',
                        'icon' => 'fas fa-list'
                    ],
                    [
                        'title' => 'Créer un ticket',
                        'route' => 'syndic.tickets.create',
                        'icon' => 'fas fa-plus'
                    ]
                ]
            ],
            [
                'title' => 'Paiements',
                'route' => 'syndic.paiements.index',
                'icon' => 'fas fa-credit-card',
                'active' => request()->routeIs('syndic.paiements.*'),
                'badge' => null
            ],
            [
                'title' => 'Dépenses',
                'route' => 'syndic.depenses.index',
                'icon' => 'fas fa-receipt',
                'active' => request()->routeIs('syndic.depenses.*'),
                'badge' => null,
                'submenu' => [
                    [
                        'title' => 'Liste des dépenses',
                        'route' => 'syndic.depenses.index',
                        'icon' => 'fas fa-list'
                    ],
                    [
                        'title' => 'Nouvelle dépense',
                        'route' => 'syndic.depenses.create',
                        'icon' => 'fas fa-plus'
                    ]
                ]
            ],
            [
                'title' => 'Techniciens',
                'route' => 'syndic.techniciens.index',
                'icon' => 'fas fa-tools',
                'active' => request()->routeIs('syndic.techniciens.*'),
                'badge' => null
            ],
            [
                'title' => 'Rapports',
                'route' => 'syndic.rapports.index',
                'icon' => 'fas fa-chart-line',
                'active' => request()->routeIs('syndic.rapports.*'),
                'badge' => null
            ]
        ];
    }

    /**
     * Menu du propriétaire
     */
    private static function getProprietaireMenu()
    {
        return [
            [
                'title' => 'Mes Appartements',
                'route' => 'proprietaire.appartements.index',
                'icon' => 'fas fa-home',
                'active' => request()->routeIs('proprietaire.appartements.*'),
                'badge' => null
            ],
            [
                'title' => 'Paiements',
                'route' => 'proprietaire.paiements.index',
                'icon' => 'fas fa-credit-card',
                'active' => request()->routeIs('proprietaire.paiements.*'),
                'badge' => null
            ],
            [
                'title' => 'Mes Tickets',
                'route' => 'proprietaire.tickets.index',
                'icon' => 'fas fa-ticket-alt',
                'active' => request()->routeIs('proprietaire.tickets.*'),
                'badge' => null
            ]
        ];
    }

    /**
     * Menu du locataire
     */
    private static function getLocataireMenu()
    {
        return [
            [
                'title' => 'Tickets',
                'route' => 'locataire.tickets.index',
                'icon' => 'fas fa-ticket-alt',
                'active' => request()->routeIs('locataire.tickets.*'),
                'badge' => null
            ]
        ];
    }

    /**
     * Menu du technicien
     */
    private static function getTechnicienMenu()
    {
        return [
            [
                'title' => 'Mes Tickets',
                'route' => 'technicien.tickets.index',
                'icon' => 'fas fa-wrench',
                'active' => request()->routeIs('technicien.tickets.*'),
                'badge' => self::getTechnicienTicketsBadge()
            ],
            [
                'title' => 'Interventions',
                'route' => 'technicien.interventions.index',
                'icon' => 'fas fa-tools',
                'active' => request()->routeIs('technicien.interventions.*'),
                'badge' => null
            ]
        ];
    }

    /**
     * Menu commun à tous les utilisateurs
     */
    private static function getCommonMenu()
    {
        return [
            [
                'type' => 'divider'
            ],
            [
                'title' => 'Notifications',
                'route' => 'notifications.index',
                'icon' => 'fas fa-bell',
                'active' => request()->routeIs('notifications.*'),
                'badge' => self::getNotificationsBadge()
            ],
            [
                'title' => 'Événements',
                'route' => 'evenements.index',
                'icon' => 'fas fa-calendar',
                'active' => request()->routeIs('evenements.*'),
                'badge' => null
            ],
            [
                'title' => 'Mon Profil',
                'route' => 'profile.show',
                'icon' => 'fas fa-user',
                'active' => request()->routeIs('profile.*'),
                'badge' => null
            ],
            [
                'title' => 'Paramètres',
                'route' => 'settings.index',
                'icon' => 'fas fa-cog',
                'active' => request()->routeIs('settings.*'),
                'badge' => null
            ]
        ];
    }

    /**
     * Obtenir la route du dashboard selon le rôle
     */
    private static function getDashboardRoute($roleId)
    {
        return match ($roleId) {
            6 => 'promoteur.dashboard',
            7 => 'syndic.dashboard',
            3 => 'proprietaire.dashboard',
            4 => 'locataire.dashboard',
            5 => 'technicien.dashboard',
            default => 'dashboard'
        };
    }

    /**
     * Badge pour les abonnements (promoteur)
     */
    private static function getSubscriptionBadge()
    {
        return [
            'text' => 'Expire bientôt',
            'color' => 'warning'
        ];
    }

    /**
     * Badge pour les tickets (syndic/technicien)
     */
    private static function getTicketsBadge()
    {
        return [
            'text' => '3',
            'color' => 'danger'
        ];
    }

    private static function getTechnicienTicketsBadge()
    {
        return [
            'text' => '2',
            'color' => 'primary'
        ];
    }

    /**
     * Badge pour les notifications
     */
    private static function getNotificationsBadge()
    {
        return [
            'text' => '5',
            'color' => 'info'
        ];
    }

    /**
     * Obtenir le nom d'affichage du rôle
     */
    public static function getRoleDisplayName($roleId)
    {
        return match ($roleId) {
            6 => 'Promoteur',
            7 => 'Syndic',
            3 => 'Propriétaire',
            4 => 'Locataire',
            5 => 'Technicien',
            1 => 'Administrateur',
            default => 'Utilisateur'
        };
    }
}