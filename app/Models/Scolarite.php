<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_niveau',
        'id_classe',
        'id_annee_academique',
        'montant_scolarite',
    ];
}


