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
        'date_inscription',
    ];

    // Relation avec le modèle Eleve
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }


    

    // Relation avec le modèle Niveau
    public function niveau()
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    // Relation avec le modèle AnneeAcademique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    // Relation avec le modèle Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
}
