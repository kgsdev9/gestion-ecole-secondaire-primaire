<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartition extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'anneeacademique_id',
        'examen_id',
    ];

    /**
     * Relation avec l'examen
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }

    /**
     * Relation avec l'année académique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
