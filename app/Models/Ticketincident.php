<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Ticketincident extends Model
{
    public $timestamps = false;
    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }

    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function syndic()
    {
        return $this->belongsTo(User::class, 'syndic_id');
    }
}
