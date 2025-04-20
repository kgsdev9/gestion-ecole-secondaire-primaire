<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeExamen extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'examen_id',
        'matiere_id',
        'heure_debut',
        'heure_fin',
        'duree',
        'jour'
    ];

    // Définir les relations

    // Relation avec le modèle Examen
    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    // Relation avec le modèle Matiere
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Calculer la durée de l'examen en minutes si ce n'est pas déjà renseigné.
     *
     * @return int
     */
    public function calculerDuree()
    {
        if (!$this->duree) {
            $start = \Carbon\Carbon::parse($this->heure_debut);
            $end = \Carbon\Carbon::parse($this->heure_fin);
            $this->duree = $start->diffInMinutes($end);
        }
        return $this->duree;
    }
}
