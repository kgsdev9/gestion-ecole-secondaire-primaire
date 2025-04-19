<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'typeexamen_id',
        'anneeacademique_id',
        'nom',
        'description',
        'classe_id',
        'date_debut',
        'date_fin',
        'cloture',
    ];



    public function examenProgrammes()
    {
        return $this->hasMany(ProgrammeExamen::class);
    }

    // Définir les relations

    // Relation avec le modèle AnneeAcademique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    // Relation avec le modèle TypeExamen
    public function typeExamen()
    {
        return $this->belongsTo(TypeExamen::class, 'typeexamen_id');
    }

    // Relation avec le modèle Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    // Ajouter d'autres relations si nécessaire (par exemple, les notes des examens)
}
