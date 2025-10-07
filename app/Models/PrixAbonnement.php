<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrixAbonnement extends Model
{
    protected $table = 'prix_abonnements';
    
    protected $fillable = [
        'type_abonnement',
        'nom',
        'prix',
        'duree_mois',
        'max_immeubles',
        'is_active'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Calculer le pourcentage d'économie
    public function getPourcentageEconomieAttribute()
    {
        if ($this->duree_mois <= 1) return 0;
        
        $prixBase = 59; // Prix mensuel de base
        $prixSansReduction = $prixBase * $this->duree_mois;
        
        if ($prixSansReduction <= 0) return 0;
        
        return round((($prixSansReduction - $this->prix) / $prixSansReduction) * 100);
    }

    // Relation avec les abonnements
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'type_abonnement', 'type_abonnement');
    }
}