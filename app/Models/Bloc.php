<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloc extends Model
{
    public $timestamps = false;

    public function appartements()
{
    return $this->hasMany(Appartement::class, 'bloc_id');
}


    /**
     * Relation Bloc → Immeuble
     */
    public function immeuble()
    {
         return $this->belongsTo(Immeuble::class, 'ImmeubleId');
    }

    /**
     * Mettre à jour nombreBlocs automatiquement
     */
   protected static function booted()
{
    static::created(function ($bloc) {
        $bloc->refresh(); // recharge le bloc pour avoir l'immeuble_id
        if ($bloc->immeuble) {
            $bloc->immeuble->nombreBlocs = $bloc->immeuble->blocs()->count();
            $bloc->immeuble->save();
        }
    });

    static::deleted(function ($bloc) {
        if ($bloc->immeuble) {
            $bloc->immeuble->nombreBlocs = $bloc->immeuble->blocs()->count();
            $bloc->immeuble->save();
        }
    });
}

}
