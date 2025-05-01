<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Versement;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
class SuiviVersementController extends Controller
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
        $anneeScolaireActuelle = $this->anneeAcademiqueService->getAnneeActive();

        $versements = Versement::with(['eleve.classe', 'typeVersement', 'scolarite'])
        ->where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->get();


        $classes = Classe::with(['niveau', 'salle'])
        ->where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->get();
        $allAnneesAcademique  = AnneeAcademique::all();
        return view('versemens.suivi.index', compact('versements', 'classes', 'allAnneesAcademique'));
    }
}
