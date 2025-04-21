<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
   


    protected $fillable = [
        'classe_id',
        'niveau_id',
        'anneeacademique_id',
        'salle_id',
        'cloture',
        'examen'
    ];

    public function students()
    {
        return $this->hasMany(Eleve::class);
    }
}
