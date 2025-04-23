<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Examen;
use App\Models\ProgrammeExamen;
use App\Models\Repartition;
use App\Models\TypeExamen;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
use App\Services\GenerateCodeService;
use App\Services\GenerateTitleService;

class ExamenController extends Controller
{
    protected $anneeAcademiqueService;
    protected $generateCodeService;
    protected $generateTitleService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService, GenerateCodeService $generateCodeService, GenerateTitleService $generateTitleService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
        $this->generateCodeService = $generateCodeService;
        $this->generateTitleService = $generateTitleService;

    }

    public function index()
    {

        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        $classe = Classe::all();
        $anneAcademique = AnneeAcademique::all();
        $typexamen = TypeExamen::all();
        $listeexamens = Examen::with('anneeAcademique', 'typeExamen', 'classe')
                                ->where('anneeacademique_id', $anneeScolaireActuelle->id)
                                ->get();

        return view('examens.listeexamens.index', compact('listeexamens', 'classe', 'anneAcademique', 'typexamen'));
    }





    public function store(Request $request)
    {
        // Vérifier si l'ID de l'examen est fourni
        $examenId = $request->input('examen_id');

        if ($examenId) {
            // Si l'ID existe, on récupère l'examen et on le met à jour
            $examen = Examen::find($examenId);

            // Si l'examen n'existe pas, retourner une erreur
            if (!$examen) {
                return $this->createExamen($request);
            }

            // Si l'examen existe, on le met à jour
            return $this->updateExamen($examen, $request);
        } else {
            // Si l'ID est absent, on crée un nouvel examen
            return $this->createExamen($request);
        }
    }

    // Méthode pour créer un nouvel examen
    private function createExamen(Request $request)
    {

        $cloture = ($request->cloture === true || $request->cloture == 'true') ? 1 : 0;

        $examen =  Examen::create([
            'code'=> $this->generateCodeService->generateUniqueCode('examens', 'code'),
            'name' => $request->nom,
            'description' => $request->description,
            'typeexamen_id' => $request->typeexamen_id,
            'anneeacademique_id' => $request->anneeacademique_id,
            'classe_id' => $request->classe_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'cloture' => $cloture,
        ]);

        $programmeexamen = ProgrammeExamen::create([
            'code' => $examen->code,
            'title' =>  $this->generateTitleService->generateTitle($request->nom, 'programme-examen', $request->anneeacademique_id),
            'examen_id' => $examen->id,
            'anneeacademique_id' => $request->anneeacademique_id,
        ]);

        $repartitionexamen = Repartition::create([
            'code' => $examen->code,
            'title' =>  $this->generateTitleService->generateTitle($request->nom, 'programme-examen', $request->anneeacademique_id),
            'examen_id' => $examen->id,
            'anneeacademique_id' => $request->anneeacademique_id,
        ]);

        $examen->load('typeExamen', 'anneeAcademique', 'classe');

        return response()->json([
            'message' => 'Examen créé avec succès.',
            'examen' => $examen
        ], 201);
    }

    // Méthode pour mettre à jour un examen existant
    private function updateExamen(Examen $examen, Request $request)
    {
        $cloture = ($request->cloture === true || $request->cloture == 'true') ? 1 : 0;

        $examen->update([
            'name' => $request->nom,
            'description' => $request->description,
            'typeexamen_id' => $request->typeexamen_id,
            'anneeacademique_id' => $request->anneeacademique_id,
            'classe_id' => $request->classe_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'cloture' => $cloture,
        ]);

        $programmeexamen = ProgrammeExamen::where('examen_id', $examen->id)->update([
            'title' =>  $this->generateTitleService->generateTitle($request->nom, 'programme-examen', $request->anneeacademique_id),
            'examen_id' => $examen->id,
            'anneeacademique_id' => $request->anneeacademique_id,
        ]);

        $repartitionexamen = Repartition::where('examen_id', $examen->id)->update([
            'title' =>  $this->generateTitleService->generateTitle($request->nom, 'programme-examen', $request->anneeacademique_id),
            'examen_id' => $examen->id,
            'anneeacademique_id' => $request->anneeacademique_id,
        ]);

        $examen->load('typeExamen', 'anneeAcademique', 'classe');
        return response()->json([
            'message' => 'Examen mis à jour avec succès.',
            'examen' => $examen
        ], 200);
    }

    // Méthode pour supprimer un examen
    public function destroy($id)
    {
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json([
                'message' => 'Examen non trouvé.',
            ], 404);
        }

        $examen->delete();

        return response()->json([
            'message' => 'Examen supprimé avec succès.',
        ], 200);
    }
}
