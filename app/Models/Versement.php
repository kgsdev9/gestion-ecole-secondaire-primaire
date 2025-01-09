<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'eleve_id',
        'montant',
        'date_versement',
        'type_versement', // Frais d'inscription", "Frais de scolarité", "Examen", "Autres").
        'statut_versement', // "Payé", "Non payé", "En reta
    ];
}
