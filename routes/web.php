<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\PromoteurController;
use App\Http\Controllers\ImmeubleController;
use App\Http\Controllers\BlocController;
use App\Http\Controllers\AppartementController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\SyndicController;
use App\Http\Controllers\TicketIncidentController;

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
| Routes communes pour tous les utilisateurs connectés
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.suspension'])->group(function () {

    // PROFIL - Routes unifiées pour tous les rôles
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update.password');
        Route::delete('/delete-avatar', [ProfileController::class, 'deleteAvatar'])->name('delete.avatar');
    });

    // NOTIFICATIONS - Communes à tous
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/mark-read/{notification}', [NotificationController::class, 'markAsRead'])->name('mark.read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark.all.read');
        Route::delete('/delete/{notification}', [NotificationController::class, 'destroy'])->name('delete');
    });

    // ÉVÉNEMENTS - Communs à tous
    Route::prefix('evenements')->name('evenements.')->group(function () {
        Route::get('/', [EvenementController::class, 'index'])->name('index');
        Route::get('/calendar', [EvenementController::class, 'calendar'])->name('calendar');
        Route::post('/create', [EvenementController::class, 'store'])->name('store');
    });

    // PARAMÈTRES - Communs à tous
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings.index');
    Route::put('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');

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

// Routes Promoteur
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

    // Routes abonnements
    Route::prefix('abonnements')->name('abonnements.')->group(function () {
        Route::get('/', [AbonnementController::class, 'index'])->name('index');
        Route::post('/process', [AbonnementController::class, 'process'])->name('process');
        Route::get('/check-status/{paymentRef}', [AbonnementController::class, 'checkStatus'])->name('check.status');
    });

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
    Route::get('/syndics/assign', [PromoteurController::class, 'showAssignSyndic'])->name('syndics.assign.form');
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




// Routes Syndic - À ajouter dans web.php à la place de la section existante
// Routes Syndic - Section corrigée
Route::prefix('syndic')->name('syndic.')->middleware(['auth', 'syndic'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [SyndicController::class, 'dashboard'])->name('dashboard');

    // Immeuble
    Route::get('/immeuble', [SyndicController::class, 'showImmeuble'])->name('immeubles.show');

    // Appartements
    Route::get('/appartements', [SyndicController::class, 'appartements'])->name('appartements.index');
    Route::get('/appartements/{appartement}', [SyndicController::class, 'showAppartement'])->name('appartements.show');

    // Résidents
    Route::get('/residents', [SyndicController::class, 'residents'])->name('residents.index');
    Route::get('/proprietaires', [SyndicController::class, 'proprietaires'])->name('proprietaires.index');
    Route::get('/locataires', [SyndicController::class, 'locataires'])->name('locataires.index');

    // Tickets - Utilise TicketIncidentController
    Route::controller(TicketIncidentController::class)->prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/', 'index')->name('index');
        Route::get('/{ticket}', 'show')->name('show');
        Route::put('/{ticket}/status', 'updateStatus')->name('status');
        Route::post('/{ticket}/assign', 'assign')->name('assign');
    });

    // Paiements
    Route::get('/paiements', [SyndicController::class, 'paiements'])->name('paiements.index');

    // Dépenses
    Route::get('/depenses/create', [SyndicController::class, 'createDepense'])->name('depenses.create');
    Route::post('/depenses', [SyndicController::class, 'storeDepense'])->name('depenses.store');
    Route::get('/depenses', [SyndicController::class, 'depenses'])->name('depenses.index');

    // Techniciens
    Route::get('/techniciens', [SyndicController::class, 'techniciens'])->name('techniciens.index');

    // Rapports
    Route::get('/rapports', [SyndicController::class, 'rapports'])->name('rapports.index');
});

// N'oubliez pas d'ajouter l'import du contrôleur en haut du fichier web.php
// use App\Http\Controllers\SyndicController;

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

// WEBHOOK KONNECT - En dehors du groupe (sans auth)
Route::post('/webhook/konnect', [AbonnementController::class, 'webhook'])->name('webhook.konnect');

/*
|--------------------------------------------------------------------------
| Redirections de compatibilité
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('user.login');
});

// Route de test
Route::get('/test', function () {
    $user = auth()->user();
    return $user ? "Connecté: {$user->name} (Rôle: {$user->role_id})" : "Non connecté";
})->middleware('auth');

?>