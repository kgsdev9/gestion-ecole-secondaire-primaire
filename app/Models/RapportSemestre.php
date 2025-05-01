<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportSemestre extends Model
{
    use HasFactory;

    protected $fillable = [
        'anneeacademique_id',
        'niveau_id',
        'classe_id',
        'semestre_id',
        'nombre_eleves',
        'taux_reussite',
        'moyenne_generale',
        'observations',
    ];


    public function itemsRapport() {
        return $this->hasMany(RapportSemestreLigne::class);
    }

    // Relation avec l'année académique
    public function anneeacademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    // Relation avec le niveau
    public function niveau()
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    // Relation avec la classe
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    // Relation avec le semestre
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'semestre_id');
    }
}
