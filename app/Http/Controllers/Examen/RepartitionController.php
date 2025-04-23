<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Repartition;
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

        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        $repartitions = Repartition::where('anneeacademique_id', $anneeScolaireActuelle->id)
                                    ->with(['examen', 'anneeAcademique'])
                                    ->get();


        return view('examens.repartition.index', compact('listeexamens', 'classe', 'anneAcademique', 'typexamen'));
    }
}
