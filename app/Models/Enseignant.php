<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'matricule',
        'photo',
        'email',
        'adresse',
        'telephone',
        'matricule',
        'matiere_id'
    ];


    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
