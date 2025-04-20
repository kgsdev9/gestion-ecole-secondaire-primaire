<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moyenne extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'eleve_id',
        'matiere_id',
        'semestre_id',
        'anneeacademique_id',
        'moyenne',
    ];

    // Définir les relations

    // Relation avec le modèle Eleve
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    // Relation avec le modèle Matiere
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    // Relation avec le modèle Semestre
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    // Relation avec le modèle AnneeAcademique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    // Ajouter des méthodes supplémentaires si nécessaire
}
