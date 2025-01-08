<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'id_eleve',
        'id_classe',
        'id_annee_academique',
        'date_inscription',
        'date_sortie',
    ];

}


