<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteExamen extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'examen_id',
        'etudiant_id',
        'matiere_id',
        'note',
    ];

    // Définir les relations

    // Relation avec le modèle Examen
    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    // Relation avec le modèle Etudiant
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    // Relation avec le modèle Matiere
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
