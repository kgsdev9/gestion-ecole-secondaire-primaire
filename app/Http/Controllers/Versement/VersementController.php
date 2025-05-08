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

        $this->anneeAcademiqueService->checkAndCreateAnneeAcademique();

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


    public function generateCodeVersement() {}



    public function store(Request $request)
    {


        // Création du versement sans validation (en supposant que les données sont valides)
        $versement = Versement::create([
            'montant_verse' => $request->montant_verse,
            'montant_restant' => $request->montant_reliquat,
            'typeversement_id' => $request->typeversement_id,
            'scolarite_id' => $request->scolarite_id,
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



    public function destroy($id)
    {
        $versement = Versement::find($id);

        if (!$versement) {
            return response()->json(['message' => 'Versement non trouvé.'], 404);
        }

        $eleveId = $versement->eleve_id;
        $anneeId = $versement->anneeacademique_id;
        $scolariteId = $versement->scolarite_id;

        // Sauvegarde du montant total de la scolarité AVANT suppression
        $montantTotal = $versement->scolarite->montant_scolarite;

        // Supprimer le versement
        $versement->delete();

        // Recalculer les versements restants après suppression du versement
        $versements = Versement::where('eleve_id', $eleveId)
            ->where('anneeacademique_id', $anneeId)
            ->where('scolarite_id', $scolariteId)
            ->orderBy('created_at')
            ->get();

        $montantCumul = 0;

        // Recalculer le montant restant pour chaque versement
        foreach ($versements as $v) {
            $montantCumul += $v->montant_verse;
            $v->montant_restant = $montantTotal - $montantCumul; // Calculer le montant restant
            $v->save(); // Sauvegarder la mise à jour
        }

        return response()->json([
            'message' => 'Versement supprimé et montants recalculés avec succès.',
        ]);
    }

}
