<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'niveau_id',
        'anneeacademique_id',
        'classe_id',
        'date_inscription'
    ];

}


