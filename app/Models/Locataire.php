<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Locataire extends Model
{

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
