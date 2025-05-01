<?php

namespace App\Services;

use App\Models\AnneeAcademique;
use App\Models\Semestre;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnneeAcademiqueService
{


    public function checkAndCreateAnneeAcademique()
    {
        // Récupérer l'année académique en cours (année actuelle)
        $currentYear = Carbon::now()->year;
        $anneeAcademique = AnneeAcademique::where('name', (string)$currentYear)->first();

        // Si l'année académique n'existe pas, on la crée
        if (!$anneeAcademique) {
            $anneeAcademique = AnneeAcademique::create([
                'name' => (string)$currentYear,
                'date_debut' => Carbon::now()->startOfYear(),
                'date_fin' => Carbon::now()->endOfYear(),
                'active' => 1,
            ]);

            Semestre::create([
                'anneeacademique_id' => $anneeAcademique->id,
                'name' => 'Semestre 1',
                'active' => 1,
            ]);

            Semestre::create([
                'anneeacademique_id' => $anneeAcademique->id,
                'name' => 'Semestre 2',
                'active' => false,
            ]);
        }

    }


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
                'date_debut' => $debut . '-' . $fin,
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
        return AnneeAcademique::orderByDesc('date_debut')->get();
    }
}
