<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    // public $timestamps = false; // ou true si tu veux gérer created_at/updated_at

    // Un événement appartient à un immeuble
    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class, 'immeubleId'); // colonne dans evenements qui référence immeuble
    }
}
