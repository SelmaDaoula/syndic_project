<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloc extends Model 
{
    public $timestamps = false;
    
    // CORRIGÉ avec les vrais noms de colonnes de votre table
    protected $fillable = [
        'nom',
        'nombre_appartement',  // FIXÉ: nombreAppartement → nombre_appartement
        'nombre_etages',       // FIXÉ: nombreEtages → nombre_etages
        'surface_totale',      // FIXÉ: surfaceTotale → surface_totale
        'Immeuble_id',
    ];

    public function appartements() 
    {
        return $this->hasMany(Appartement::class, 'bloc_id'); 
    }

    public function immeuble()
    {
        return $this->belongsTo(Immeuble::class, 'Immeuble_id');
    }

    protected static function booted() 
    {
        static::created(function ($bloc) {
            $bloc->refresh();
            if ($bloc->immeuble) {
                $bloc->immeuble->nombre_blocs = $bloc->immeuble->blocs()->count();
                $bloc->immeuble->save();
            }
        });

        static::deleted(function ($bloc) {
            if ($bloc->immeuble) {
                $bloc->immeuble->nombre_blocs = $bloc->immeuble->blocs()->count();
                $bloc->immeuble->save();
            }
        }); 
    }
}
?>