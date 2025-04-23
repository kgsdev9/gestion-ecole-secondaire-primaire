<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepartitionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'examen_id',
        'eleve_id',
        'salle_id',
        'anneeacademique_id',
    ];


    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    // 🔁 Relation vers Élève
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    // 🔁 Relation vers Salle
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    // 🔁 Relation vers Année Académique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
