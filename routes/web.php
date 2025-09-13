<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\PromoteurController;
use App\Http\Controllers\ImmeubleController;
use App\Http\Controllers\BlocController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\AbonnementController;


/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Authentification utilisateurs
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('user.')->group(function () {
    Route::get('/connexion', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [UserAuthController::class, 'login'])->name('login.submit');
    Route::get('/inscription', [UserAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [UserAuthController::class, 'register'])->name('register.submit');
    Route::post('/deconnexion', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth');
});

/*
|--------------------------------------------------------------------------
| Admin Voyager
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

/*
|--------------------------------------------------------------------------
| Route de compatibilité pour Voyager
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->role_id === 1) {
        return redirect('/admin');
    }
    return redirect('/dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard principal avec redirection
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('user.login');
    }

    $user = auth()->user();

    return match ($user->role_id) {
        1 => redirect('/admin'),
        3 => redirect()->route('proprietaire.dashboard'),
        4 => redirect()->route('locataire.dashboard'),
        5 => redirect()->route('technicien.dashboard'),
        6 => redirect()->route('promoteur.dashboard'),
        7 => redirect()->route('syndic.dashboard'),
        default => redirect()->route('user.login')->with('error', 'Rôle non reconnu')
    };
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes pour admin et autres rôles (immeubles génériques)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.suspension'])->group(function () {
    // Routes immeubles pour admin/syndic SEULEMENT
    Route::resource('immeubles', ImmeubleController::class)->except(['index', 'create', 'store']);

    // Routes blocs génériques
    Route::resource('blocs', BlocController::class);

    // Routes supplémentaires pour les blocs d'un immeuble
    Route::get('/immeubles/{immeuble}/blocs', [BlocController::class, 'index'])->name('immeubles.blocs.index');
    Route::get('/immeubles/{immeuble}/blocs/create', [BlocController::class, 'createForPromoteur'])->name('immeubles.blocs.create');
    Route::post('/blocs', [BlocController::class, 'store'])->name('blocs.store');
});

/*
|--------------------------------------------------------------------------
| Routes spécifiques pour chaque rôle
|--------------------------------------------------------------------------
*/

// Routes Promoteur - TOUTES centralisées ici

// Remplacez TOUTE la section promoteur dans votre web.php par ceci :

// Remplacez votre section promoteur par ceci :

Route::prefix('promoteur')->name('promoteur.')->middleware(['auth', 'promoteur'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PromoteurController::class, 'dashboard'])->name('dashboard');

    // Immeubles
    Route::get('/immeubles', [ImmeubleController::class, 'index'])->name('immeubles.index');
    Route::get('/immeubles/create', [ImmeubleController::class, 'create'])->name('immeubles.create');
    Route::post('/immeubles', [ImmeubleController::class, 'store'])->name('immeubles.store');
    Route::get('/immeubles/export-pdf', [ImmeubleController::class, 'exportPdf'])->name('immeubles.export-pdf');

    // Routes blocs
    Route::get('/blocs/create', [BlocController::class, 'createForPromoteur'])->name('blocs.create');
    Route::post('/blocs', [BlocController::class, 'store'])->name('blocs.store');
    Route::post('/blocs/{bloc}/generate-apartments', [BlocController::class, 'generateApartments'])->name('blocs.generate');
    Route::post('/blocs/{bloc}/regenerate-apartments', [BlocController::class, 'regenerateApartments'])->name('blocs.regenerate');

    // ABONNEMENTS
    Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements.index');
    Route::post('/abonnements/process', [AbonnementController::class, 'process'])->name('abonnements.process');
    Route::get('/abonnements/historique', [AbonnementController::class, 'historique'])->name('abonnements.historique');

    // Autres pages
    Route::get('/appartements', function () {
        return view('promoteur.appartements');
    })->name('appartements.index');

    Route::get('/blocs', function () {
        return view('promoteur.blocs');
    })->name('blocs.index');

    // Syndics
    Route::get('/syndics', function () {
        return view('promoteur.syndics.index');
    })->name('syndics.index');
    Route::get('/syndics/assign', [PromoteurController::class, 'showAssignSyndic'])->name('syndics.assign');
    Route::post('/syndics/assign', [PromoteurController::class, 'assignSyndic'])->name('syndics.assign');
    Route::delete('/syndics/unassign', [PromoteurController::class, 'unassignSyndic'])->name('syndics.unassign');

    // Rapports
    Route::get('/rapports', function () {
        return view('promoteur.rapports');
    })->name('rapports.index');
    Route::get('/rapports/financier', function () {
        return view('promoteur.rapports-financier');
    })->name('rapports.financier');
    Route::get('/rapports/activite', function () {
        return view('promoteur.rapports-activite');
    })->name('rapports.activite');

    // Appartements resource
    Route::resource('appartements', AppartementController::class);
});

// WEBHOOK KONNECT - IMPORTANT : En dehors du groupe (sans auth)
Route::get('/promoteur/abonnements/webhook', [AbonnementController::class, 'webhook'])->name('promoteur.abonnements.webhook');



// Routes Syndic
Route::prefix('syndic')->name('syndic.')->middleware(['auth', 'syndic'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.syndic');
    })->name('dashboard');
});

// Routes Propriétaire
Route::prefix('proprietaire')->name('proprietaire.')->middleware(['auth', 'proprietaire'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.proprietaire');
    })->name('dashboard');
});

// Routes Locataire
Route::prefix('locataire')->name('locataire.')->middleware(['auth', 'locataire'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.locataire');
    })->name('dashboard');
});

// Routes Technicien
Route::prefix('technicien')->name('technicien.')->middleware(['auth', 'technicien'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboards.technicien');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Redirections de compatibilité
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('user.login');
});

// Routes communes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications.index');

    Route::get('/evenements', function () {
        return view('evenements.index');
    })->name('evenements.index');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
});

// Route de test
Route::get('/test', function () {
    $user = auth()->user();
    return $user ? "Connecté: {$user->name} (Rôle: {$user->role_id})" : "Non connecté";
})->middleware('auth');
?>