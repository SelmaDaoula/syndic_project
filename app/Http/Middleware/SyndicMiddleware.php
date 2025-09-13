<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyndicMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role_id !== 7) {
            return redirect()->route('user.login') // ✅ CORRECTION
                ->with('error', 'Accès réservé aux syndics.');
        }

        return $next($request);
    }
}