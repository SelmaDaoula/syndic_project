<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Promoteur;
use App\Models\Syndicat;
use App\Models\Proprietaire;
use App\Models\Locataire;
use App\Models\Technicien;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->getProfile(); // Utilise la méthode du modèle User
        
        return view('profile.show', compact('user', 'profile'));
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->getProfile();
        
        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Mettre à jour le profil (informations communes + spécifiques)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->getProfile();

        // Validation de base pour tous les utilisateurs
        $baseRules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ];

        // Validation spécifique selon le rôle
        $specificRules = $this->getSpecificValidationRules($user->role_id);
        $rules = array_merge($baseRules, $specificRules);

        $validatedData = $request->validate($rules);

        try {
            // Mise à jour des informations communes (table users)
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Mise à jour des informations spécifiques selon le rôle
            if ($profile) {
                $this->updateSpecificProfile($user->role_id, $profile, $validatedData);
            }

            return redirect()
                ->route('profile.show')
                ->with('success', 'Profil mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour l'avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        try {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Sauvegarder le nouvel avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            $user->update(['avatar' => $avatarPath]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar mis à jour avec succès !',
                'avatar_url' => Storage::url($avatarPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'avatar : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        try {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Avatar supprimé avec succès !'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ]);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()
                ->route('profile.show')
                ->with('success', 'Mot de passe mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du mot de passe.');
        }
    }

    /**
     * Afficher la page des paramètres
     */
    public function settings()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateSettings(Request $request)
    {
        // À implémenter selon vos besoins (notifications, préférences, etc.)
        return redirect()
            ->route('settings.index')
            ->with('success', 'Paramètres mis à jour avec succès !');
    }

    // ========== MÉTHODES PRIVÉES ==========

    /**
     * Obtenir les règles de validation spécifiques selon le rôle
     */
    private function getSpecificValidationRules($roleId)
    {
        switch ($roleId) {
            case 6: // Promoteur
                return [
                    'nom' => 'required|string|max:255',
                    'prenom' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                ];

            case 7: // Syndic
                return [
                    'nom' => 'required|string|max:255',
                    'prenom' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                ];

            case 3: // Propriétaire
                return [
                    'nom' => 'required|string|max:255',
                    'prenom' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                    'date_acquisition' => 'nullable|date',
                ];

            case 4: // Locataire
                return [
                    'nom' => 'required|string|max:255',
                    'prenom' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                ];

            case 5: // Technicien
                return [
                    'nom' => 'required|string|max:255',
                    'prenom' => 'required|string|max:255',
                    'telephone' => 'required|string|max:20',
                    'specialite' => 'nullable|string|max:255',
                ];

            default:
                return [];
        }
    }

    /**
     * Mettre à jour le profil spécifique selon le rôle
     */
    private function updateSpecificProfile($roleId, $profile, $validatedData)
    {
        $commonFields = ['nom', 'prenom', 'telephone'];
        $updateData = array_intersect_key($validatedData, array_flip($commonFields));

        switch ($roleId) {
            case 6: // Promoteur
                $profile->update($updateData);
                break;

            case 7: // Syndic
                $profile->update($updateData);
                break;

            case 3: // Propriétaire
                if (isset($validatedData['date_acquisition'])) {
                    $updateData['date_acquisition'] = $validatedData['date_acquisition'];
                }
                $profile->update($updateData);
                break;

            case 4: // Locataire
                $profile->update($updateData);
                break;

            case 5: // Technicien
                if (isset($validatedData['specialite'])) {
                    $updateData['specialite'] = $validatedData['specialite'];
                }
                $profile->update($updateData);
                break;
        }
    }

    /**
     * Obtenir les informations de rôle pour l'affichage
     */
    public function getRoleDisplayInfo($user)
    {
        $roleInfo = [
            'name' => $user->getRoleName(),
            'color' => $this->getRoleColor($user->role_id),
            'icon' => $this->getRoleIcon($user->role_id),
        ];

        return $roleInfo;
    }

    /**
     * Couleur selon le rôle
     */
    private function getRoleColor($roleId)
    {
        return match($roleId) {
            1 => 'red',      // Admin
            3 => 'blue',     // Propriétaire
            4 => 'green',    // Locataire
            5 => 'orange',   // Technicien
            6 => 'purple',   // Promoteur
            7 => 'indigo',   // Syndic
            default => 'gray'
        };
    }

    /**
     * Icône selon le rôle
     */
    private function getRoleIcon($roleId)
    {
        return match($roleId) {
            1 => 'shield-check',    // Admin
            3 => 'home',           // Propriétaire
            4 => 'key',            // Locataire
            5 => 'wrench',         // Technicien
            6 => 'building-office', // Promoteur
            7 => 'briefcase',      // Syndic
            default => 'user'
        };
    }
}