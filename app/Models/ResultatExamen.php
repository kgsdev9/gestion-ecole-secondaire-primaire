<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultatExamen extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'examen_id',
        'anneeacademique_id',
        'taux_reussite',
        'moyenne_examen',
        'nb_admis',
        'nb_total_participant',
        'statut_publication',
    ];

    /**
     * Relation avec l'examen
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }

    public function resultatExamensLignes() {
        return $this->hasMany(ResultatExamenLigne::class, 'code', 'code');
    }

    /**
     * Relation avec l'année académique
     */
    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class, 'anneeacademique_id');
    }

    /**
     * Récupérer les résultats de l'examen en fonction de l'année académique et de l'examen
     */
    public static function getResultatsByExamenAndAnnee($examenId, $anneeAcademiqueId)
    {
        return self::where('examen_id', $examenId)
                    ->where('anneeacademique_id', $anneeAcademiqueId)
                    ->get();
    }

    /**
     * Méthode pour calculer le taux de réussite
     */
    public function calculateTauxReussite()
    {
        if ($this->nb_total_participant > 0) {
            return ($this->nb_admis / $this->nb_total_participant) * 100;
        }
        return 0;
    }

    /**
     * Récupérer les résultats publiés
     */
    public static function getPublishedResults()
    {
        return self::where('statut_publication', true)->get();
    }
}
