<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'semestre_id',
        'eleve_id',
        'matiere_id',
        'typenote_id',
        'note',
    ];


    // Définir les relations

    // Relation avec le modèle Semestre
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

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

    // Relation avec le modèle TypeNote
    public function typeNote()
    {

        return $this->belongsTo(TypeNote::class, 'typenote_id');
    }

    // Ajouter des méthodes supplémentaires si nécessaire
}
