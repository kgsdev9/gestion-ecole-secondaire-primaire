<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Matiere;
use App\Models\ProgrammeExamen;
use App\Models\ProgrammeExamenLigne;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
class ProgrammeExamenController extends Controller
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
        $programmesexamens =  ProgrammeExamen::where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->with(['examen.classe', 'anneeAcademique', 'examen.typeExamen'])
        ->get();

        return view('examens.programmes.index', compact('programmesexamens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProgrammeExamen($examenId)
    {
        $examen = Examen::findOrFail($examenId);
        $matieres = Matiere::all() ?? [];
        $programmeexamens = $examen->examenProgrammes()->with('matiere')->get();
        return view('examens.programmes.create', compact('examen', 'programmeexamens', 'matieres', 'examen'));
    }


    public function store(Request $request)
    {
        $examenId = $request->input('examen_id');
        $code = $request->input('code');
        $programmes = json_decode($request->input('programmeexamen'), true);

        // Supprimer les anciennes lignes ayant le même code
        ProgrammeExamenLigne::where('code', $code)
                                ->where('examen_id', $examenId)
                                ->delete();

        // Créer les nouveaux programmes
        foreach ($programmes as $programme) {
            ProgrammeExamenLigne::create([
                'examen_id' => $examenId,
                'code' => $code,
                'matiere_id' => $programme['matiere_id'] ?? null,
                'heure_debut' => $programme['heure_debut'],
                'heure_fin' => $programme['heure_fin'],
                'jour' => $programme['jour'],
                'duree' => $programme['duree'] ?? null,
                'anneeacademique_id' => $this->anneeAcademiqueService->getAnneeActive()->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Programme d\'examen enregistré avec succès.'
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($examenId)
    {
        $examen = Examen::findOrFail($examenId);
        $matieres = Matiere::all() ?? [];
        $programmeexamens = $examen->examenProgrammes()->with('matiere')->get();
        return view('examens.programmes.show', compact('examen', 'programmeexamens', 'matieres', 'examen'));
    }

}
