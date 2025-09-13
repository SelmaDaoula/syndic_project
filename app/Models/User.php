<?php

namespace App\Models;

use TCG\Voyager\Models\User as VoyagerUser;
use TCG\Voyager\Models\Role;

class User extends VoyagerUser
{
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relations avec le modèle Role de Voyager
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function promoteur()
    {
        return $this->hasOne(Promoteur::class);
    }

    public function syndicat()
    {
        return $this->hasOne(Syndicat::class);
    }

    public function proprietaire()
    {
        return $this->hasOne(Proprietaire::class);
    }

    public function locataire()
    {
        return $this->hasOne(Locataire::class);
    }

    public function technicien()
    {
        return $this->hasOne(Technicien::class);
    }

    // Vos méthodes utiles
    public function getRoleName()
    {
        return $this->role ? $this->role->display_name : 'Aucun rôle';
    }

    public function isRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function isPromoteur()
    {
        return $this->role_id === 6;
    }

    public function isSyndic()
    {
        return $this->role_id === 7;
    }

    public function isProprietaire()
    {
        return $this->role_id === 3;
    }

    public function isLocataire()
    {
        return $this->role_id === 4;
    }

    public function isTechnicien()
    {
        return $this->role_id === 5;
    }

    public function getProfile()
    {
        switch ($this->role_id) {
            case 6: return $this->promoteur;
            case 7: return $this->syndicat;
            case 3: return $this->proprietaire;
            case 4: return $this->locataire;
            case 5: return $this->technicien;
            default: return null;
        }
    }
}