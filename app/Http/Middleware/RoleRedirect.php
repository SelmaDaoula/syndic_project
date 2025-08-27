<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Redirection selon rôle
        switch ($user->role_id) {
            case 1: // admin
                return redirect('/admin');
            case 3: // proprietaire
                return redirect()->route('proprietaire.dashboard');
            case 4: // locataire
                return redirect()->route('locataire.dashboard');
            case 5: // technicien
                return redirect()->route('technicien.dashboard');
            default:
                return $next($request);
        }
    }
}
