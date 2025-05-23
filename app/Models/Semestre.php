<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'anneeacademique_id',
        'name',
        'active',
        'cloture',
        'date_debut',
        'date_fin',
    ];

    // Relation avec l'année académique
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    /**
     * Vérifie si le semestre est clôturé.
     *
     * @return bool
     */
    public function estCloture()
    {
        return $this->active;
    }

    /**
     * Clôturer un semestre, empêchant l'ajout de nouvelles notes.
     */
    public function closeSemestre()
    {
        $this->active = true;
        $this->save();
    }

    /**
     * Ouvrir un semestre pour permettre l'ajout de nouvelles notes.
     */
    public function openSemestre()
    {
        $this->active = false;
        $this->save();
    }
}
