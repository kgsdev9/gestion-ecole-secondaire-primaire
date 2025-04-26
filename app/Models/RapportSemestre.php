<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportSemestre extends Model
{

    use HasFactory;

    protected $fillable = [
        'anneeacademique_id',
        'niveau_id',
        'classe_id',
        'semestre_id',
        'nombre_eleves',
        'taux_reussite',
        'moyenne_generale',
        'observations',
    ];


}
