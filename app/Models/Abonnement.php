<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Abonnement extends Model
{
    protected $fillable = [
        'promoteur_id', 'type_abonnement', 'montant', 
        'date_debut', 'date_fin', 'statut'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant' => 'decimal:2',
    ];

    // ========== RELATIONS ==========
    
    public function promoteur()
    {
        return $this->belongsTo(Promoteur::class);
    }

    // Un abonnement peut couvrir plusieurs immeubles
    public function immeubles()
    {
        return $this->hasMany(Immeuble::class);
    }

    // ========== MÉTHODES MÉTIER ==========
    
    public function isActive()
    {
        return $this->statut === 'actif' && 
               $this->date_debut <= now() && 
               $this->date_fin >= now();
    }

    public function isExpired()
    {
        return $this->date_fin < now();
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInDays($this->date_fin);
    }

    // Marquer comme expiré et suspendre tous les utilisateurs
    public function expire()
    {
        $this->update(['statut' => 'expire']);
        
        // Suspendre le promoteur
        $this->promoteur->suspendAll();
    }
}