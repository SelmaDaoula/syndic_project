<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Technicien extends Model
{
    public function ticketsAssignes()
{
    return $this->hasMany(TicketIncident::class, 'technicien_id');
}

 public function user()
    {
        return $this->belongsTo(User::class);
    }
}
