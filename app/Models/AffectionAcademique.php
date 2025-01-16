<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectionAcademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'niveau_id',
        'annee_academique_id',
        'salle_id',
        'cloture'
    ];
}
