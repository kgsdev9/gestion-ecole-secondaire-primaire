<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Repartition;
use App\Models\RepartitionDetail;
use App\Models\Salle;
use App\Services\AnneeAcademiqueService;
class RepartitionController extends Controller
{

    protected $anneeAcademiqueService;

    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index()
    {

        $this->anneeAcademiqueService->checkAndCreateAnneeAcademique();
        
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        $repartitions = Repartition::where('anneeacademique_id', $anneeScolaireActuelle->id)
                                    ->with(['examen.classe', 'anneeAcademique', 'examen.typeExamen'])
                                    ->get();
        return view('examens.repartition.index', compact('repartitions'));
    }


    public function createRepartition($id)
    {

        $examen = Examen::with('classe.students')->findOrFail($id);

        $salles = Salle::all();
        $eleves = $examen->classe->students()->orderBy('nom')->get();

        $index = 0;
        $totalEleves = $eleves->count();

        $repartitionexiste = RepartitionDetail::where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->delete();

            foreach ($salles as $salle) {
                $capacite = $salle->capacite;

                for ($i = 0; $i < $capacite && $index < $totalEleves; $i++) {
                    RepartitionDetail::create([
                        'examen_id' => $examen->id,
                        'code' => $examen->code,
                        'eleve_id' => $eleves[$index]->id,
                        'salle_id' => $salle->id,
                        'anneeacademique_id' => $examen->anneeacademique_id,
                    ]);

                    $index++;
                }
                if ($index >= $totalEleves) {
                    break;
                }
            }

            if ($index < $totalEleves) {

                return back()->with('error', 'Pas assez de salles pour tous les élèves.');
            }

        $repartitions = RepartitionDetail::with(['examen', 'eleve', 'salle', 'anneeAcademique'])
            ->where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->get();

        return view('examens.repartition.create', compact('repartitions', 'examen'));
    }


    public function show($id)
    {
        $examen = Examen::with('classe.students')->findOrFail($id);

        $repartitions = RepartitionDetail::with(['examen', 'eleve', 'salle', 'anneeAcademique'])
        ->where('examen_id', $examen->id)
        ->where('anneeacademique_id', $examen->anneeacademique_id)
        ->get();

        return view('examens.repartition.show',compact('repartitions', 'examen'));
    }


}
