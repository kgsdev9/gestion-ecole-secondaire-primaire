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

            $parEleves[$eleveId]['total_points'] += $ligne->moyenne; // additionne les moyennes matières
            $parEleves[$eleveId]['nombre_matières'] += 1;
        }

        // Maintenant on insère
        $nbTotalParticipants = count($parEleves);
        $nbAdmis = 0;
        $totalMoyenne = 0;

        foreach ($parEleves as $eleveId => $data) {
            $moyenneGenerale = $data['total_points'] / $data['nombre_matières'];

            if ($moyenneGenerale >= 10) {
                $nbAdmis++;
            }

            $totalMoyenne += $moyenneGenerale;

            ResultatExamenLigne::create([
                'code' => $request->code,
                'resultat_examen_id' => null,
                'eleve_id' => $eleveId,
                'nombre_total_points' => $data['total_points'],
                'moyenne' => $moyenneGenerale,
                'admis' => $moyenneGenerale >= 10,
                'mention' => $this->determineMention($moyenneGenerale),
                'rang' => 1, // à calculer après
                'anneeacademique_id' => $examen->anneeacademique_id,
            ]);
        }

        $moyenneExamen = $totalMoyenne / $nbTotalParticipants;
        $tauxReussite = ($nbTotalParticipants > 0) ? ($nbAdmis / $nbTotalParticipants) * 100 : 0;

        $resultatExamen = ResultatExamen::create([
            'code' => $request->code,
            'examen_id' => $examen->id,
            'anneeacademique_id' => $examen->anneeacademique_id,
            'taux_reussite' => $tauxReussite,
            'moyenne_examen' => $moyenneExamen,
            'nb_admis' => $nbAdmis,
            'nb_total_participant' => $nbTotalParticipants,
            'statut_publication' => 1,
        ]);

        // Mise à jour resultat_examen_id
        ResultatExamenLigne::where('code', 'like', $request->code . '-%')
            ->update(['resultat_examen_id' => $resultatExamen->id]);

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
