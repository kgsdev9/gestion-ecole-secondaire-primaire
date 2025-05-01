<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;

    // Définir les attributs qui peuvent être assignés en masse
    protected $fillable = [
        'reference',
        'typeversement_id',
        'eleve_id',
        'anneeacademique_id',
        'date_versement',
        'montant_verse',
        'montant_restant',
        'scolarite_id'
    ];  

    public function scolarite()
    {
        return $this->belongsTo(Scolarite::class);
    }



    // Relation avec le type de versement
    public function typeVersement()
    {
        return $this->belongsTo(TypeVersement::class, 'typeversement_id');
    }

    // Relation avec l'élève
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id');
    }

    /**
     * Vérifie si le versement est payé.
     *
     * @return bool
     */
    public function estPaye()
    {
        return $this->statut === 'paye';
    }

    /**
     * Met à jour le statut du versement à "payé".
     */
    public function marquerCommePaye()
    {
        $this->statut = 'paye';
        $this->save();
    }

    /**
     * Met à jour le statut du versement à "en attente".
     */
    public function marquerCommeEnAttente()
    {
        $this->statut = 'en_attente';
        $this->save();
    }
}
