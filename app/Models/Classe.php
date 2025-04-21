<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'niveau_id',
        'anneeacademique_id',
        'salle_id',
        'cloture',
        'examen'
    ];

    public function students()
    {
        return $this->hasMany(Eleve::class);
    }
    /**
     * Relation avec le modèle Niveau.
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    /**
     * Relation avec le modèle AnneeAcademique.
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    /**
     * Relation avec le modèle Salle.
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
