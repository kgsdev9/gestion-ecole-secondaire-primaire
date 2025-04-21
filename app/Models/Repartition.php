w<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartition extends Model
{
    use HasFactory;

    protected $fillable = [
        'examen_id',
        'anneeacademique_id',
    ];


    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
