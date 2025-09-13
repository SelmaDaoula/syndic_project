<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    // AJOUT DE LA PROPRIÉTÉ FILLABLE
    protected $fillable = [
        'type_appartement',
        'surface',
        'bloc_id',
        'proprietaire_id',
        'nombre_pieces',
        'statut',
        'numero'
    ];

    // CORRECTION : Activer les timestamps
    public $timestamps = true;

    // Un appartement appartient à un bloc
    public function bloc()
    {
        return $this->belongsTo(Bloc::class, 'bloc_id');
    }

    // AJOUT : Relation proprietaire (un seul propriétaire principal)
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'proprietaire_id');
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