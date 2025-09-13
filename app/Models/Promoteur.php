<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promoteur extends Model
{
   protected $fillable = [
    'nom', 'prenom', 'email', 'telephone', 'user_id', 'is_suspended'
   
];

    protected $casts = [
        'is_suspended' => 'boolean',
    ];

    // ========== RELATIONS DE BASE ==========
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========== RELATIONS MÉTIER ==========
    
    // Un promoteur peut avoir plusieurs abonnements
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    // Un promoteur peut avoir plusieurs immeubles
    public function immeubles()
    {
        return $this->hasMany(Immeuble::class);
    }

    // ========== MÉTHODES UTILES ==========
    
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function isActive()
    {
        return !$this->is_suspended;
    }

    // Récupérer l'abonnement actif
    public function getAbonnementActif()
    {
        return $this->abonnements()
                   ->where('statut', 'actif')
                   ->where('date_fin', '>=', now())
                   ->first();
    }

    // Vérifier si l'abonnement est valide
    public function hasValidSubscription()
    {
        return $this->getAbonnementActif() !== null;
    }

    // Suspendre le promoteur et tous ses utilisateurs
    public function suspendAll()
    {
        $this->update(['is_suspended' => true]);
        
        // Suspendre tous les utilisateurs de ses immeubles
        foreach ($this->immeubles as $immeuble) {
            $immeuble->suspendAllUsers();
        }
    }
}