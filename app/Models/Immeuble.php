<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Immeuble extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'surface_totale',
        'nombre_blocs',
        'annee_construction',
        'statut',
        'promoteur_id',
        'abonnement_id',
        'syndic_id'
    ];

    protected $casts = [
        'surface_totale' => 'decimal:2',
        'nombre_blocs' => 'integer',
        'annee_construction' => 'integer',
    ];

    // ========== RELATIONS ==========

    public function promoteur()
    {
        return $this->belongsTo(Promoteur::class);
    }

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class);
    }

    // Dans app/Models/Immeuble.php
    public function syndic()
    {
        return $this->belongsTo(\App\Models\Syndicat::class, 'syndic_id');
    }
    public function blocs()
    {
        return $this->hasMany(Bloc::class, 'Immeuble_id'); // IMPORTANT: utiliser 'Immeuble_id' et non 'immeuble_id'
    }

    // ========== RELATIONS COMPLEXES ==========

    // Tous les appartements de l'immeuble
    public function appartements()
    {
        return $this->hasManyThrough(Appartement::class, Bloc::class);
    }

    // Tous les propriétaires de l'immeuble
    public function proprietaires()
    {
        return $this->hasManyThrough(Proprietaire::class, Appartement::class, 'id', 'appartement_id', 'id', 'id')
            ->through('blocs');
    }

    // Tous les locataires de l'immeuble
    public function locataires()
    {
        return $this->hasManyThrough(Locataire::class, Appartement::class, 'id', 'appartement_id', 'id', 'id')
            ->through('blocs');
    }

    // ========== MÉTHODES MÉTIER ==========

    public function hasValidSubscription()
    {
        return $this->abonnement && $this->abonnement->isActive();
    }

    // Suspendre tous les utilisateurs de cet immeuble
    public function suspendAllUsers()
    {
        // Suspendre le syndic
        if ($this->syndicat) {
            $this->syndicat->update(['is_suspended' => true]);
        }

        // Suspendre tous les propriétaires
        foreach ($this->proprietaires as $proprietaire) {
            $proprietaire->update(['is_suspended' => true]);
        }

        // Suspendre tous les locataires
        foreach ($this->locataires as $locataire) {
            $locataire->update(['is_suspended' => true]);
        }
    }

    // Réactiver tous les utilisateurs
    public function reactivateAllUsers()
    {
        if ($this->syndicat) {
            $this->syndicat->update(['is_suspended' => false]);
        }

        foreach ($this->proprietaires as $proprietaire) {
            $proprietaire->update(['is_suspended' => false]);
        }

        foreach ($this->locataires as $locataire) {
            $locataire->update(['is_suspended' => false]);
        }
    }
}