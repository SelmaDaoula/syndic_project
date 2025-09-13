<?php
// app/Http/Middleware/TechnicienMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicienMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role_id !== 5) {
            return redirect()->route('user.login') // ✅ CORRECTION
                ->with('error', 'Accès réservé aux techniciens.');
        }

        return $next($request);
    }
}