<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeExamenLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'examen_id',
        'matiere_id',
        'heure_debut',
        'heure_fin',
        'duree',
        'jour',
        'anneeacademique_id'
    ];

    /**
     * Relation avec l'examen
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }

    /**
     * Relation avec la matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }

    /**
     * Relation avec l'année académique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
