<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Immeuble extends Model
{
    public $timestamps = false;

    /**
     * Un immeuble a plusieurs blocs
     */
    public function blocs()
    {
        return $this->hasMany(Bloc::class, 'ImmeubleId'); // 'ImmeubleId' = colonne dans blocs qui référence l'immeuble
    }
    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'immeubleId'); // un immeuble peut avoir plusieurs événements
    }

}
