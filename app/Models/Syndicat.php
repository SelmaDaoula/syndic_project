<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Syndicat extends Model
{
    // Ajoutez ces propriétés à votre modèle Syndicat
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'user_id',
        'is_suspended'
    ];

    protected $casts = [
        'is_suspended' => 'boolean',
    ];
    public $timestamps = false;
    public function ticketsSupervises()
    {
        return $this->hasMany(TicketIncident::class, 'syndic_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
