<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Abonnement extends Model
{
    protected $fillable = [
        'promoteur_id',
        'type_abonnement',
        'montant',
        'date_debut',
        'date_fin',
        'statut',
        'nombre_immeubles_max',
        'immeuble_id',
        'payment_ref',
        'payment_url',
        'payment_completed_at'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant' => 'decimal:2',
        'payment_completed_at' => 'datetime'
    ];

    // Relations
    public function promoteur()
    {
        return $this->belongsTo(Promoteur::class);
    }

    public function prixAbonnement()
    {
        return $this->belongsTo(PrixAbonnement::class, 'type_abonnement', 'type_abonnement');
    }

    // Méthodes utiles
    public function isActive()
    {
        return $this->statut === 'actif' && $this->date_fin >= now();
    }

    public function isPaid()
    {
        return $this->statut === 'actif' && $this->payment_completed_at !== null;
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->statut) {
            case 'actif':
                return '<span class="badge bg-success">Actif</span>';
            case 'en_attente':
                return '<span class="badge bg-warning">En attente</span>';
            case 'echec':
                return '<span class="badge bg-danger">Échec</span>';
            case 'expire':
                return '<span class="badge bg-secondary">Expiré</span>';
            default:
                return '<span class="badge bg-light">Inconnu</span>';
        }
    }
}