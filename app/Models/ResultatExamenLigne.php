<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultatExamenLigne extends Model
{
    use HasFactory;

    // Champs remplissables
    protected $fillable = [
        'code',
        'resultat_examen_id',
        'eleve_id',
        'nombre_total_points',
        'moyenne',
        'admis',
        'mention',
        'rang',
        'anneeacademique_id'
    ];

    /**
     * Relation avec le résultat d'examen
     */
    public function resultatExamen()
    {
        return $this->belongsTo(ResultatExamen::class, 'resultat_examen_id');
    }

    /**
     * Relation avec l'élève
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'eleve_id'); // Assurez-vous d'avoir un modèle `Eleve` ou `User` pour les élèves
    }

    /**
     * Relation avec l'année académique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    /**
     * Calculer la moyenne de l'examen pour cet élève
     */
    public function calculateMoyenne()
    {
        // Si tu as des critères spécifiques pour calculer la moyenne
        // Par exemple, si tu as des notes pour chaque matière, tu peux ajouter un calcul ici.
        // Pour l'instant, si la moyenne est déjà calculée manuellement, tu peux définir cette méthode pour plus tard.
        return $this->moyenne;
    }

    /**
     * Vérifier si l'élève est admis
     */
    public function checkAdmission()
    {
        // Par exemple, si la moyenne est supérieure à 10, l'élève est admis
        return $this->moyenne >= 10;
    }
}
