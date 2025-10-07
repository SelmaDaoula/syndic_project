<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Technicien extends Model
{
    // Ajoutez ces propriétés à votre modèle Technicien
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'user_id',
        'specialite',
        'is_suspended'
    ];

    protected $casts = [
        'is_suspended' => 'boolean',
    ];
    public function ticketsAssignes()
    {
        return $this->hasMany(TicketIncident::class, 'technicien_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
