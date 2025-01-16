<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'photo',
        'matricule',
        'classe_id',
        'annee_academique_id',
        'niveau_id',
        'date_naissance',
        'adresse',
        'telephone_parant',
        'anneeacademique_id'
    ];

}
