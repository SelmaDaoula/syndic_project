<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckSuspension
{
    public function handle(Request $request, Closure $next)
    {
        // Exclure les routes admin/Voyager avant toute vérification
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Exclure l'admin
        if ($user->role_id === 1) {
            return $next($request);
        }

        $profile = null;

        // Votre logique existante
        switch ($user->role_id) {
            case 6: // Promoteur
                $profile = DB::table('promoteurs')->where('user_id', $user->id)->first();
                break;
            case 7: // Syndic
                $profile = DB::table('syndicats')->where('user_id', $user->id)->first();
                break;
            case 3: // Propriétaire
                $profile = DB::table('proprietaires')->where('user_id', $user->id)->first();
                break;
            case 4: // Locataire
                $profile = DB::table('locataires')->where('user_id', $user->id)->first();
                break;
            case 5: // Technicien
                $profile = DB::table('techniciens')->where('user_id', $user->id)->first();
                break;
            default:
                return $next($request);
        }

        // Si le profil existe et est suspendu
        if ($profile && $profile->is_suspended) {
            Auth::logout();
            
            return redirect()->route('user.login')
                ->withErrors(['subscription' => 'Votre compte a été suspendu. L\'abonnement de votre immeuble a expiré. Veuillez contacter votre promoteur.']);
        }

        return $next($request);
    }
}