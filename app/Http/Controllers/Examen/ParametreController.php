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
        // Vérifier si l'examen existe
        $examen = Examen::where('code', $request->code)->first();

        if (!$examen) {
            // Si l'examen n'est pas trouvé, retourner un message d'erreur
            return response()->json([
                'success' => true,
                'message' => 'Examen non trouvé.',
            ], 404);
        }

        $message = "";

        // Vérifier si une action est sélectionnée
        if ($request->rapport) {
            $message = "Résultats d'examen générés avec succès.";
            $this->generateRapportExamen($request->code, $message);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } elseif ($request->cloturer) {
            $message = "Examen clôturé avec succès";
            $this->closeExamen($request->code, $message);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } elseif ($request->decloturer) {
            $message = "Examen declôturé avec succès";
            $this->openExamen($request->code, $message);
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        // Si aucune action valide n'est choisie, retourner une erreur
        return response()->json([
            'error' => true,
            'message' => "Action invalide.",
        ], 400);
    }



    public function generateRapportExamen($code, $message)
    {

        // Récupérer l'examen
        $examen = Examen::where('code', $code)->first();

        if (!$examen) {
            $message = "Examen non trouvé";
            return response()->json([
                'error' => true,
                'message' => $message
            ], 404);
        }

        // Supprimer les anciens résultats s'ils existent
        $ancienResultat = ResultatExamen::where('code', $code)->first();
        if ($ancienResultat) {
            ResultatExamenLigne::where('code', $code)->delete();
            $ancienResultat->delete();
        }

        // Récupérer toutes les moyennes (lignes matières)
        $moyenneExamens = $examen->moyenneExamens;

        if ($moyenneExamens->isEmpty())
        {
            $message = "Aucun résultat de moyenne pour cet examen";
            return response()->json([
                'error' => true,
                'message' => $message
            ], 404);
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
            'code' => $code,
            'examen_id' => $examen->id,
            'anneeacademique_id' => $examen->anneeacademique_id,
            'taux_reussite' => 0,
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
                'code' => $code,
                'resultat_examen_id' => $resultatExamen->id,
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
     * fermer l'examen.
     *
     * @return \Illuminate\Http\Response
     */
    public function openExamen($code, $message)
    {
        $examen = Examen::where('code', $code)->first();

        if (!$examen) {
            $message = "Examen introuvable";
            return response()->json([
                'error' => true,
                'message' => $message
            ], 404);
        }

        $examen->cloture = 0;
        $examen->save();


    }


    public function closeExamen($code, $message)
    {
        $examen = Examen::where('code', $code)->first();


        if (!$examen) {
            $message = "Examen introuvable";
            return response()->json([
                'error' => true,
                'message' => $message
            ], 404);
        }

        $examen->cloture = 1;
        $examen->save();

    }
}
