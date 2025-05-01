<?php

namespace App\Http\Controllers\Inscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\AnneeAcademique;
use App\Services\AnneeAcademiqueService;

class InscriptionController extends Controller
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

        $inscriptions = Inscription::with(['eleve', 'classe', 'niveau', 'anneeAcademique'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();


        $eleves = Eleve::all();
        $niveaux = Niveau::all();
        $classes = Classe::all();
        $anneesAcademiques = AnneeAcademique::all();
        return view('inscriptions.index', compact('inscriptions', 'eleves', 'niveaux', 'classes', 'anneesAcademiques'));
    }
}
