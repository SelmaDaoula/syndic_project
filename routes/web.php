<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;

// Page d'accueil
Route::get('/', fn() => view('welcome'));

// Dashboards par rôle protégés par auth
Route::middleware(['auth'])->group(function () {
    Route::get('/proprietaire/dashboard', fn() => view('dashboards.proprietaire'))
        ->name('proprietaire.dashboard');

    Route::get('/locataire/dashboard', fn() => view('dashboards.locataire'))
        ->name('locataire.dashboard');

    Route::get('/technicien/dashboard', fn() => view('dashboards.technicien'))
        ->name('technicien.dashboard');

    Route::get('/syndic/dashboard', fn() => view('dashboards.syndic'))
        ->name('syndic.dashboard');

    // Page de redirection après login
    Route::get('/home', fn() => "Redirection...")
        ->middleware('role.redirect')
        ->name('home');
});

// Voyager admin routes
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

// Déconnexion
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/home'); // redirection après logout
})->name('logout');
