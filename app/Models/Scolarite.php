<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'niveau_id',
        'classe_id',
        'annee_academique_id',
        'montant_scolarite',
    ];
}


