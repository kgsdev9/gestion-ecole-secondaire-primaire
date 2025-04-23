<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\TypeVersement;
use App\Models\Versement;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;

class VersementController extends Controller
{


    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index()
    {
        $anneeScolaireActuelle = $this->anneeAcademiqueService->getAnneeActive();
        $listeleves = Eleve::whereHas('inscriptions', function ($query) use ($anneeScolaireActuelle) {
            $query->where('anneeacademique_id', $anneeScolaireActuelle->id);
        })->get();

        // Versements de l'année en cours
        $versements = Versement::with(['eleve', 'typeVersement'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        $listescolarite = Scolarite::with(['niveau', 'classe', 'anneeAcademique'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();
        $typeversement  = TypeVersement::all();
        return view('versements.index', compact('listeleves', 'versements', 'listescolarite', 'typeversement'));
    }


    public function generateCodeVersement() {
        
    }



    public function store(Request $request)
    {

        // Création du versement sans validation (en supposant que les données sont valides)
        $versement = Versement::create([
            'montant_verse' => $request->montant_verse,
            'montant_restant' => $request->montant_reliquat,
            'typeversement_id' => $request->typeversement_id,
            'date_versement' => now(),
            'eleve_id' => $request->eleve_id,
            'anneeacademique_id' => $this->anneeAcademiqueService->getAnneeActive()->id,
            'reference' => rand(1000, 34445),
        ]);

        $versement->load(['typeVersement', 'eleve']);
        // Réponse JSON pour indiquer que le versement a été créé
        return response()->json([
            'message' => 'Versement créé avec succès.',
            'versement' => $versement
        ], 201);
    }
}
