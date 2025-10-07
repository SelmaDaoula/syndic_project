<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Locataire extends Model
{
    // Ajoutez ces propriétés à votre modèle Locataire
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'user_id',
        'appartementid',
        'date_debut_bail',
        'date_fin_bail',
        'is_suspended'
    ];

    protected $casts = [
        'date_debut_bail' => 'date',
        'date_fin_bail' => 'date',
        'is_suspended' => 'boolean',
    ];

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'appartementid');
    }

    public function ticketsOuverts()
    {
        return $this->hasMany(TicketIncident::class, 'locataire_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
