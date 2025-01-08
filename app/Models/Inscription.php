<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_eleve',
        'anneeacademique_id',
        'id_classe',
        'date_inscription'
    ];

}
