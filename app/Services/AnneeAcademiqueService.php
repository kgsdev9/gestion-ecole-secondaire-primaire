<?php

namespace App\Services;

use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\DB;

class AnneeAcademiqueService
{
    /**
     * Crée une nouvelle année scolaire et désactive les autres.
     */
    public function creerAnnee($debut, $fin)
    {
        DB::transaction(function () use ($debut, $fin) {

            AnneeAcademique::query()->update(['active' => false]);

            // Créer la nouvelle année active
            AnneeAcademique::create([
                'debut' => $debut,
                'fin' => $fin,
                'libelle' => $debut . '-' . $fin,
                'active' => true,
            ]);
        });
    }

    /**
     * Récupère l'année scolaire actuellement active.
     */
    public function getAnneeActive()
    {
        return AnneeAcademique::where('active', true)->first();
    }

    /**
     * Active une année scolaire spécifique.
     */
    public function activer($anneeId)
    {
        DB::transaction(function () use ($anneeId) {
            AnneeAcademique::query()->update(['active' => false]);

            AnneeAcademique::where('id', $anneeId)->update(['active' => true]);
        });
    }

    /**
     * Clôture l’année scolaire active.
     */
    public function cloturerAnneeActive()
    {
        $active = $this->getAnneeActive();
        if ($active) {
            $active->update(['active' => false]);
        }
    }

    /**
     * Liste toutes les années scolaires.
     */
    public function toutesLesAnnees()
    {
        return AnneeAcademique::orderByDesc('debut')->get();
    }
}
