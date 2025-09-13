<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'email', 'telephone', 'user_id',
        'appartement_id', 'date_acquisition', 'is_suspended'
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'is_suspended' => 'boolean',
    ];

    // ========== RELATIONS ==========
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    // Relations dérivées
    public function locataires()
    {
        return $this->hasMany(Locataire::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // ========== MÉTHODES MÉTIER ==========
    
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getImmeuble()
    {
        return $this->appartement ? $this->appartement->bloc->immeuble : null;
    }

    public function getLocataireActuel()
    {
        return $this->locataires()
                   ->where('is_suspended', false)
                   ->where('date_debut_bail', '<=', now())
                   ->where(function($q) {
                       $q->whereNull('date_fin_bail')
                         ->orWhere('date_fin_bail', '>=', now());
                   })
                   ->first();
    }

    // Vérifier si l'abonnement de l'immeuble est valide
    public function hasValidSubscription()
    {
        $immeuble = $this->getImmeuble();
        return $immeuble && $immeuble->hasValidSubscription();
    }
}