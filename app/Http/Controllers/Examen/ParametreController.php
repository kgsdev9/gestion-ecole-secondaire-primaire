<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\MoyenneExamen;
use App\Models\ResultatExamen;
use App\Models\ResultatExamenLigne;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('examens.parametres.index');
    }


    public function executeExamAction(Request $request)
    {
        // Récupérer l'examen
        $examen = Examen::where('code', $request->code)->first();

        if (!$examen) {
            return response()->json(['error' => 'Examen non trouvé'], 404);
        }

        // Supprimer les anciens résultats s'ils existent
        $ancienResultat = ResultatExamen::where('examen_id', $examen->id)->first();
        if ($ancienResultat) {
            ResultatExamenLigne::where('resultat_examen_id', $ancienResultat->id)->delete();
            $ancienResultat->delete();
        }

        // Récupérer toutes les moyennes (lignes matières)
        $moyenneExamens = $examen->moyenneExamens;

        if ($moyenneExamens->isEmpty()) {
            return response()->json(['error' => 'Aucun résultat de moyenne pour cet examen.'], 404);
        }

        // Regrouper par élève
        $parEleves = [];
        foreach ($moyenneExamens as $ligne) {
            $eleveId = $ligne->eleve_id;

            if (!isset($parEleves[$eleveId])) {
                $parEleves[$eleveId] = [
                    'total_points' => 0,
                    'nombre_matières' => 0,
                ];
            }

            $parEleves[$eleveId]['total_points'] += $ligne->moyenne;
            $parEleves[$eleveId]['nombre_matières'] += 1;
        }

        // Calcul des moyennes générales
        $elevesAvecMoyenne = [];
        foreach ($parEleves as $eleveId => $data) {
            $moyenneGenerale = $data['total_points'] / $data['nombre_matières'];

            $elevesAvecMoyenne[] = [
                'eleve_id' => $eleveId,
                'moyenne' => $moyenneGenerale,
                'total_points' => $data['total_points'],
            ];
        }

        // Trier par moyenne décroissante
        usort($elevesAvecMoyenne, function ($a, $b) {
            return $b['moyenne'] <=> $a['moyenne'];
        });

        $nbTotalParticipants = count($elevesAvecMoyenne);
        $nbAdmis = 0;
        $totalMoyenne = 0;

        // Création du ResultatExamen d'abord
        $resultatExamen = ResultatExamen::create([
            'code' => $request->code,
            'examen_id' => $examen->id,
            'anneeacademique_id' => $examen->anneeacademique_id,
            'taux_reussite' => 0, // sera mis à jour après
            'moyenne_examen' => 0,
            'nb_admis' => 0,
            'nb_total_participant' => $nbTotalParticipants,
            'statut_publication' => 1,
        ]);

        $rang = 1;
        $precedenteMoyenne = null;
        $compteur = 0;

        foreach ($elevesAvecMoyenne as $eleve) {
            $compteur++;

            if ($precedenteMoyenne !== null && $eleve['moyenne'] < $precedenteMoyenne) {
                $rang = $compteur;
            }

            if ($eleve['moyenne'] >= 10) {
                $nbAdmis++;
            }

            $totalMoyenne += $eleve['moyenne'];

            ResultatExamenLigne::create([
                'code' => $request->code,
                'resultat_examen_id' => $resultatExamen->examen_id,
                'eleve_id' => $eleve['eleve_id'],
                'nombre_total_points' => $eleve['total_points'],
                'moyenne' => $eleve['moyenne'],
                'admis' => $eleve['moyenne'] >= 10,
                'mention' => $this->determineMention($eleve['moyenne']),
                'rang' => $rang,
                'anneeacademique_id' => $examen->anneeacademique_id,
            ]);

            $precedenteMoyenne = $eleve['moyenne'];
        }

        // Mettre à jour les stats du ResultatExamen
        $moyenneExamen = $totalMoyenne / $nbTotalParticipants;
        $tauxReussite = ($nbTotalParticipants > 0) ? ($nbAdmis / $nbTotalParticipants) * 100 : 0;

        $resultatExamen->update([
            'taux_reussite' => $tauxReussite,
            'moyenne_examen' => $moyenneExamen,
            'nb_admis' => $nbAdmis,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résultats d\'examen générés avec succès.',
        ]);
    }


    // Déterminer la mention
    private function determineMention($moyenne)
    {
        if ($moyenne >= 16) {
            return 'Très bien';
        } elseif ($moyenne >= 14) {
            return 'Bien';
        } elseif ($moyenne >= 12) {
            return 'Assez bien';
        } elseif ($moyenne >= 10) {
            return 'Passable';
        } else {
            return 'Non admis';
        }
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function closeExamen()
    {
        //
    }

    public function openExamen() {}
}
