<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    public $timestamps = false;

    // Un appartement appartient à un bloc
    public function bloc()
    {
        return $this->belongsTo(Bloc::class, 'bloc_id');
    }

    // Un appartement peut avoir plusieurs propriétaires (cas héritage)
    public function proprietaires()
    {
        return $this->belongsToMany(Proprietaire::class, 'appartement_proprietaire', 'appartement_id', 'proprietaire_id')
                    ->withTimestamps();
    }

    // Un appartement a un seul locataire
    public function locataire()
    {
        return $this->hasOne(Locataire::class, 'appartementid');
    }
}
