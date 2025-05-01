<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportSemestreLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'rapport_semestre_id',
        'eleve_id',
        'moyenne',
        'rang',
        'mention',
        'admis',
        'observation',
    ];


    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }
}
