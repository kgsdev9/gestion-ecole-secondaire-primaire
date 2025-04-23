<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\AnneeAcademiqueService;

class HomeController extends Controller
{

    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }
    /**
     * Affichage de la page d'accueil.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        
        return view('welcome', compact('anneeScolaireActuelle'));
    }
}
