<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Inscription;
use App\Models\MoyenneExamen;
use App\Models\MoyenneExamenLigne;
use App\Models\ProgrammeExamenLigne;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
class MoyenneExamenController extends Controller
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

        $moyennes = MoyenneExamen::where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->with(['examen.classe', 'anneeAcademique', 'examen.typeExamen'])
        ->get();

        return view('examens.moyennes.index', compact('moyennes'));
    }

    public function createMoyenne($examneid)
    {
        $examen = Examen::find($examneid);

        $eleves = Inscription::with('eleve')
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->where('classe_id', $examen->classe_id)
            ->get()
            ->pluck('eleve');

        $programmexamens = ProgrammeExamenLigne::with('matiere')
            ->where('examen_id', $examen->id)
            ->get();

        return view('examens.moyennes.create', compact('examen', 'eleves', 'programmexamens'));
    }



    public function store(Request $request)
    {
        $examen = Examen::find($request->examen_id);
        $anneeAcademiqueId = $examen->anneeacademique_id;

        MoyenneExamenLigne::where('examen_id', $examen->id)
            ->where('anneeacademique_id', $anneeAcademiqueId)
            ->delete();

        foreach ($request->notes as $eleve_id => $matieres) {

            foreach ($matieres as $matiere_id => $note) {

                if (!is_null($note)) {
                    MoyenneExamenLigne::updateOrCreate(
                        [
                            'eleve_id' => $eleve_id,
                            'matiere_id' => $matiere_id,
                            'examen_id' => $examen->id,
                            'anneeacademique_id' => $anneeAcademiqueId,
                        ],
                        [
                            'moyenne' => $note,
                        ]
                    );
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function show($examenid)
    {
        $examen = Examen::find($examenid);

        // Récupérer les moyennes des examens
        $moyenneexamen = MoyenneExamenLigne::with(['eleve', 'matiere'])
            ->where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->get();

        // Récupérer les élèves (groupe par eleve_id)
        $eleves = $moyenneexamen->groupBy('eleve_id')->map(function ($group) {
            return $group->first()->eleve;
        })->values();

        // Récupérer les matières (groupe par matiere_id)
        $matieres = $moyenneexamen->groupBy('matiere_id')->map(function ($group) {
            return $group->first()->matiere;
        })->values();

        // Préparer les notes au format [eleve_id][matiere_id] => moyenne
        $notes = [];
        foreach ($moyenneexamen as $moyenne) {
            $notes[$moyenne->eleve_id][$moyenne->matiere_id] = $moyenne->moyenne;
        }

        // Calculer le premier de chaque matière
        $premiers = [];
        foreach ($matieres as $matiere) {
            $notesParMatiere = $moyenneexamen->where('matiere_id', $matiere->id);
            $meilleureNote = $notesParMatiere->max('moyenne');
            $premiers[$matiere->id] = $meilleureNote;
        }

        // Calculer la moyenne générale pour chaque élève et les moyennes max et min
        $moyennesGenerales = [];
        $moyenneMax = 0;
        $moyenneMin = 20;

        foreach ($eleves as $eleve) {
            $total = 0;
            $count = 0;
            foreach ($matieres as $matiere) {
                $note = $notes[$eleve->id][$matiere->id] ?? null;
                if ($note !== null && !is_nan($note)) {
                    $total += $note;
                    $count++;
                }
            }
            if ($count > 0) {
                $moyenne = $total / $count;
                $moyennesGenerales[$eleve->id] = $moyenne;
                $moyenneMax = max($moyenneMax, $moyenne);
                $moyenneMin = min($moyenneMin, $moyenne);
            }
        }

        // Passer les données nécessaires à la vue
        return view('examens.moyennes.show', compact('examen', 'eleves', 'matieres', 'notes', 'premiers', 'moyennesGenerales', 'moyenneMax', 'moyenneMin'));
    }


}
