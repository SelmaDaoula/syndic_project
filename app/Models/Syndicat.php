<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Syndicat extends Model
{
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
