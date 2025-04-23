<?php

namespace App\Services;
use App\Models\AnneeAcademique;
class GenerateTitleService
{
    /**
     * Génère un titre formaté et tronqué à 20 caractères max.
     *
     * @param string $examename       Titre de l’examen (ex: "examenblanc")
     * @param string $moduleencours   Titre du module en cours (ex: "repartition")
     * @param string $annee           Année académique (ex: "2025")
     * @return string
     */

    public function generateTitle(string $examename, string $moduleencours, int $anneeId): string
    {
        // Récupérer l’année académique depuis la base
        $annee = AnneeAcademique::find($anneeId);

        // Tronquer le nom de l'examen à 6 caractères
        $truncatedName = mb_substr(trim($examename), 0, 6);

        // Construire et retourner le titre final en minuscules
        $title = $truncatedName . '-' . $moduleencours . '-' . $annee->name;

        return mb_strtolower($title);
    }

}
