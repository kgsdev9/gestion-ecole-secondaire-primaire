<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'photo',
        'matricule',
        'classe_id',
        'anneeacademique_id ',
        'statuseleve_id',
        'genre_id ',
        'niveau_id',
        'nationalite',
        'date_naissance',
        'adresse',
        'telephone_parent',
        'anneeacademique_id'
    ];


    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    // Define the relationship to Classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    // Define the relationship to AnneeAcademique
    public function anneeacademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    // Define the relationship to Niveau
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
