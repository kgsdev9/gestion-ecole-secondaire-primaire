<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoyenneExamen extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'examen_id',
        'anneeacademique_id',
    ];
    public $timestamps = false;

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }
}
