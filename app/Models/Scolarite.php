<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    use HasFactory;

    protected $fillable = [
        'niveau_id',
        'affectationacademique_id',
        'anneeacademique_id',
        'montant_scolarite',
    ];



    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function classe()
    {
        return $this->belongsTo(AffectionAcademique::class, 'affectationacademique_id');
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
