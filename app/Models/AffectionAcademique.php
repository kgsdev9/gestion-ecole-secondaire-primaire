<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectionAcademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'niveau_id',
        'anneeacademique_id',
        'salle_id',
        'cloture',
        'examen'
    ];



    /**
     * Relation avec le modèle Classe.
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
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
