<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date_debut',
        'date_fin',
        'cloture',
        'active',
    ];


    public function  inscriptions()
    {
        return $this->hasMany(Inscription::class, 'anneeacademique_id');
    }


    public function semestres()
    {
        return $this->hasMany(Semestre::class, 'anneeacademique_id');
    }

    public function scopeActuelle($query)
    {
        return $query->where('active', false)->latest()->first();
    }
    // $annee = AnneeAcademique::actuelle();
    public function anneeAcademiqueActuelle()
    {
        $now = now();
        $anneeDebut = $now->month >= 9 ? $now->year : $now->year - 1;
        return $anneeDebut . '-' . ($anneeDebut + 1);
    }
}
