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
        'anneeacademique_id ',
        'niveau_id',
        'date_naissance',
        'adresse',
        'telephone_parent',
        'anneeacademique_id'
    ];

    // Define the relationship to Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    // Define the relationship to AnneeAcademique
    public function anneeacademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    // Define the relationship to Niveau
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
