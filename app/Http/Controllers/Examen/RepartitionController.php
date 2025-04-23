<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Examen;
use App\Models\Repartition;
use App\Models\TypeExamen;
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

        $repartitions  = Repartition::where('anneeacademique_id', $anneeScolaireActuelle->id)->get();


        return view('examens.repartition.index', compact('listeexamens', 'classe', 'anneAcademique', 'typexamen'));
    }
}
