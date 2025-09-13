<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserAuthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Afficher le formulaire de connexion personnalisé
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validation personnalisée des données de connexion
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);
    }

    /**
     * Tentative de connexion avec vérifications supplémentaires
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return false;
        }

        // Vérifier que l'utilisateur n'est pas admin
        if ($user->role_id === 1) {
            \Log::warning("Tentative de connexion admin sur interface utilisateur: {$user->email}");
            return false;
        }

        // Vérifier que l'utilisateur a un rôle valide
        if (!in_array($user->role_id, [3, 4, 5, 6, 7])) {
            \Log::warning("Tentative de connexion avec rôle invalide: {$user->email} (rôle: {$user->role_id})");
            return false;
        }

        return $this->guard()->attempt($credentials, $request->boolean('remember'));
    }

    /**
     * Actions après connexion réussie
     */
    protected function authenticated(Request $request, $user)
    {
        \Log::info("Connexion utilisateur réussie: {$user->name} (ID: {$user->id}, Rôle: {$user->role_id})", [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $roleNames = [
            3 => 'Propriétaire',
            4 => 'Locataire', 
            5 => 'Technicien',
            6 => 'Promoteur',
            7 => 'Syndic'
        ];
        
        $roleName = $roleNames[$user->role_id] ?? 'Utilisateur';
        
        return redirect()->intended('/dashboard')
            ->with('success', "Bienvenue {$user->name} ! Connecté en tant que {$roleName}.");
    }

    /**
     * Message d'erreur personnalisé pour échec de connexion
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()
            ->withErrors([
                'email' => 'Ces identifiants ne correspondent à aucun compte actif. Vérifiez votre email et mot de passe.',
            ])
            ->withInput($request->only('email', 'remember'));
    }

    /**
     * Déconnexion personnalisée
     */
    public function logout(Request $request)
    {
        $userName = auth()->user()->name ?? '';
        $userId = auth()->user()->id ?? '';
        
        \Log::info("Déconnexion utilisateur: {$userName} (ID: {$userId})");
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription avec création dans table spécialisée
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        DB::beginTransaction();
        try {
            // 1. Créer dans users (authentification)
            $user = $this->createUser($request->all());

            // 2. Créer dans la table spécialisée selon le rôle
            $this->createSpecializedProfile($user, $request->all());

            DB::commit();

            // 3. Connecter automatiquement
            Auth::login($user);

            \Log::info("Inscription réussie: {$user->name} (Rôle: {$user->role_id})");

            return $this->authenticated($request, $user);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erreur inscription: " . $e->getMessage());
            
            return back()
                ->withErrors(['general' => 'Erreur lors de la création du compte. Veuillez réessayer.'])
                ->withInput();
        }
    }

    /**
     * Créer l'utilisateur dans la table users
     */
    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);
    }

    /**
     * Créer le profil spécialisé selon le rôle
     */
    protected function createSpecializedProfile(User $user, array $data): void
    {
        switch ($user->role_id) {
            case 6: // Promoteur
                DB::table('promoteurs')->insert([
                    'nom' => $data['name'],
                    'prenom' => $data['prenom'] ?? '',
                    'email' => $data['email'],
                    'telephone' => $data['telephone'] ?? null,
                    'user_id' => $user->id,
                    'is_suspended' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                break;

            case 7: // Syndic
                DB::table('syndicats')->insert([
                    'nom' => $data['name'],
                    'prenom' => $data['prenom'] ?? '',
                    'email' => $data['email'],
                    'telephone' => $data['telephone'] ?? null,
                    'user_id' => $user->id,
                    'licence_numero' => $data['licence_numero'] ?? null,
                    'is_suspended' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                break;

            case 3: // Propriétaire
                DB::table('proprietaires')->insert([
                    'nom' => $data['name'],
                    'prenom' => $data['prenom'] ?? '',
                    'email' => $data['email'],
                    'telephone' => $data['telephone'] ?? null,
                    'user_id' => $user->id,
                    'appartement_id' => null, // À assigner plus tard par l'admin
                    'date_acquisition' => null,
                    'is_suspended' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                break;

            case 4: // Locataire
                DB::table('locataires')->insert([
                    'nom' => $data['name'],
                    'prenom' => $data['prenom'] ?? '',
                    'email' => $data['email'],
                    'telephone' => $data['telephone'] ?? null,
                    'user_id' => $user->id,
                    'appartement_id' => null, // À assigner plus tard
                    'proprietaire_id' => null, // À assigner plus tard
                    'date_debut_bail' => null,
                    'date_fin_bail' => null,
                    'loyer_mensuel' => null,
                    'is_suspended' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                break;

           case 5: // Technicien
    DB::table('techniciens')->insert([
        'nom' => $data['name'],
        'prenom' => $data['prenom'] ?? '',
        'email' => $data['email'],
        'telephone' => $data['telephone'] ?? null,
        'user_id' => $user->id,
        'specialites' => json_encode(['general']), // Format JSON requis
        'immeuble_id' => null,
        'is_external' => true,
        'tarif_horaire' => $data['tarif_horaire'] ?? null,
        'is_suspended' => false,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    break;

            default:
                throw new \Exception("Rôle non pris en charge pour la création de profil");
        }
    }

    /**
     * Validation étendue pour les champs supplémentaires
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', 'in:3,4,5,6,7'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ];

        // Règles spécifiques selon le rôle
        if (isset($data['role_id'])) {
            switch ($data['role_id']) {
                case 5: // Technicien
                    $rules['specialites'] = ['nullable', 'string', 'max:255'];
                    $rules['tarif_horaire'] = ['nullable', 'numeric', 'min:0'];
                    break;
                case 7: // Syndic
                    $rules['licence_numero'] = ['nullable', 'string', 'max:100'];
                    break;
            }
        }

        return Validator::make($data, $rules, [
            'name.required' => 'Le nom complet est requis.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role_id.required' => 'Veuillez sélectionner votre rôle.',
            'role_id.in' => 'Le rôle sélectionné n\'est pas valide.',
            'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 20 caractères.',
            'tarif_horaire.numeric' => 'Le tarif horaire doit être un nombre.',
            'tarif_horaire.min' => 'Le tarif horaire ne peut pas être négatif.',
        ]);
    }

    /**
     * Obtenir les credentials pour la connexion
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Guard à utiliser pour l'authentification
     */
    protected function guard()
    {
        return Auth::guard();
    }
}