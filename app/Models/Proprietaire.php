<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Proprietaire extends Model
{

    public function appartements()
{
    return $this->belongsToMany(Appartement::class, 'appartement_proprietaire', 'proprietaire_id', 'appartement_id')
                ->withTimestamps();
}

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
