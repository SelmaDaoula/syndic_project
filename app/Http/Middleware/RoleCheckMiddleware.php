<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();

        // Vérifier si l'utilisateur a un rôle valide
        if (!$user->role_id) {
            return redirect()->route('login')
                ->with('error', 'Aucun rôle assigné. Contactez l\'administrateur.');
        }

        // Convertir les noms de rôles en IDs
        $allowedRoleIds = $this->convertRoleNamesToIds($roles);

        // Vérifier si l'utilisateur a l'un des rôles autorisés
        if (!in_array($user->role_id, $allowedRoleIds)) {
            return $this->handleUnauthorizedAccess($request, $user);
        }

        return $next($request);
    }

    /**
     * Convertir les noms de rôles en IDs
     */
    private function convertRoleNamesToIds(array $roles): array
    {
        $roleMap = [
            'admin' => 1,
            'user' => 2,
            'proprietaire' => 3,
            'locataire' => 4,
            'technicien' => 5,
            'promoteur' => 6,
            'syndic' => 7,
        ];

        $roleIds = [];
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $roleIds[] = (int) $role;
            } elseif (isset($roleMap[strtolower($role)])) {
                $roleIds[] = $roleMap[strtolower($role)];
            }
        }

        return $roleIds;
    }

    /**
     * Gérer l'accès non autorisé
     */
    private function handleUnauthorizedAccess(Request $request, $user)
    {
        // Si c'est une requête AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => 'Accès non autorisé.',
                'message' => 'Vous n\'avez pas les permissions nécessaires.'
            ], 403);
        }

        // Rediriger vers le dashboard de l'utilisateur avec un message d'erreur
        $dashboardRoute = $this->getUserDashboardRoute($user->role_id);
        
        return redirect()->route($dashboardRoute)
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }

    /**
     * Obtenir la route du dashboard de l'utilisateur
     */
    private function getUserDashboardRoute(int $roleId): string
    {
        return match($roleId) {
            1 => 'admin.dashboard',
            2 => 'user.dashboard',
            3 => 'proprietaire.dashboard',
            4 => 'locataire.dashboard',
            5 => 'technicien.dashboard',
            6 => 'promoteur.dashboard',
            7 => 'syndic.dashboard',
            default => 'login'
        };
    }
}