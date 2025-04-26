<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\RapportSemestre;
use App\Models\RapportSemestreLigne;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
use Illuminate\Support\Facades\DB;

class RapportSemestreTrimestreController extends Controller
{

    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        $semestres = $anneeScolaireActuelle->semestres;

        $classes = Classe::with(['niveau', 'anneeAcademique', 'salle'])
        ->where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->get();

        return view('configurations.parametres.semestres.index', compact('semestres', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function actionSurSemestre(Request $request)
     {
         $code = $request->code; // id du semestre
         $classe_id = $request->classe_id;

         // Vérifier la classe
         $classe = Classe::with('students')->find($classe_id);
         if (!$classe) {
             return response()->json([
                 'error' => true,
                 'message' => "Classe non trouvée"
             ], 404);
         }

         // Supprimer les anciens rapports s'ils existent
         $ancienRapport = RapportSemestre::where('classe_id', $classe_id)
                                         ->where('semestre_id', $code)
                                         ->first();

         if ($ancienRapport) {
             RapportSemestreLigne::where('rapport_semestre_id', $ancienRapport->id)->delete();
             $ancienRapport->delete();
         }

         // Récupérer les élèves de la classe
         $eleves = $classe->students;

         if ($eleves->isEmpty()) {
             return response()->json([
                 'error' => true,
                 'message' => "Aucun élève trouvé dans cette classe."
             ], 404);
         }

         $notes = collect();

         foreach ($eleves as $eleve) {
             $moyenne = $this->calculerMoyenneSemestre($eleve->id, $code);
             $notes->push([
                 'eleve_id' => $eleve->id,
                 'moyenne' => $moyenne ?? 0,
             ]);
         }




         // Trier par moyenne décroissante
         $notes = $notes->sortByDesc('moyenne')->values();
       


         $nbTotalEleves = $notes->count();
         $nbAdmis = 0;
         $totalMoyenne = 0;

         // Création du rapport d'abord
         $rapport = RapportSemestre::create([
             'anneeacademique_id' => $classe->anneeacademique_id,
             'niveau_id' => $classe->niveau_id,
             'classe_id' => $classe->id,
             'semestre_id' => $code,
             'nombre_eleves' => $nbTotalEleves,
             'taux_reussite' => 0, // provisoire
             'moyenne_generale' => 0, // provisoire
             'observations' => null,
         ]);


         $rang = 1;
         $precedenteMoyenne = null;
         $compteur = 0;

         foreach ($notes as $note) {
             $compteur++;

             if ($precedenteMoyenne !== null && $note['moyenne'] < $precedenteMoyenne) {
                 $rang = $compteur;
             }

             if ($note['moyenne'] >= 10) {
                 $nbAdmis++;
             }

             $totalMoyenne += $note['moyenne'];

             RapportSemestreLigne::create([
                 'rapport_semestre_id' => $rapport->id,
                 'eleve_id' => $note['eleve_id'],
                 'moyenne' => round($note['moyenne'], 2),
                 'rang' => $rang,
                 'mention' => $this->getMention($note['moyenne']),
                 'admis' => $note['moyenne'] >= 10,
                 'observation' => null,
             ]);

             $precedenteMoyenne = $note['moyenne'];
         }

         // Mise à jour du rapport
         $moyenneGenerale = $nbTotalEleves > 0 ? $totalMoyenne / $nbTotalEleves : 0;
         $tauxReussite = $nbTotalEleves > 0 ? ($nbAdmis / $nbTotalEleves) * 100 : 0;

         $rapport->update([
             'taux_reussite' => round($tauxReussite, 2),
             'moyenne_generale' => round($moyenneGenerale, 2),
         ]);

         return response()->json([
             'error' => false,
             'message' => 'Rapport de semestre généré avec succès.',
             'rapport' => $rapport,
         ]);
     }


     private function calculerMoyenneSemestre($eleveId, $semestreId)
     {
         $moyennes = DB::table('moyennes')
             ->where('eleve_id', $eleveId)
             ->where('semestre_id', $semestreId)
             ->pluck('moyenne');

         return $moyennes->avg();
     }

    private function getMention($moyenne)
    {
        if ($moyenne >= 16) {
            return 'Très Bien';
        } elseif ($moyenne >= 14) {
            return 'Bien';
        } elseif ($moyenne >= 12) {
            return 'Assez Bien';
        } elseif ($moyenne >= 10) {
            return 'Passable';
        } else {
            return 'Échec';
        }
    }

}
