<?php

namespace App\Http\Controllers\Classe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Salle;
use App\Services\AnneeAcademiqueService;

class ClasseController extends Controller
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

        $niveaux = Niveau::all();
        $anneesAcademiques  = $anneeScolaireActuelle;
        $salles = Salle::all();
        $classes = Classe::with(['niveau', 'anneeAcademique', 'salle'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        return view('classes.classes.index', compact('niveaux', 'anneesAcademiques', 'classes', 'salles'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Vérifier si l'ID de l'affection académique existe dans la requête
        $classeId = $request->input('classe_id');

        if ($classeId) {
            // Si l'ID de l'affection académique existe, on modifie l'affection académique
            $classe = Classe::find($classeId);

            // Si l'affection académique n'existe pas, on la crée
            if (!$classe) {
                return $this->createAffectionAcademique($request);
            }

            // Si l'affection académique existe, on la met à jour
            return $this->updateAffectionAcademique($classe, $request);
        } else {
            // Si l'ID de l'affection académique est absent, on crée une nouvelle affection académique
            return $this->createAffectionAcademique($request);
        }
    }


    private function createAffectionAcademique(Request $request)
    {

        $exists = Classe::where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->exists();


        if ($exists) {
            return response()->json([
                'message' => 'Cette classe est déjà affectée à ce niveau pour cette année académique.',
            ], 400); // Erreur côté client
        }

        // Création d'une nouvelle affection académique
        $classe = Classe::create([
            'name' => $request->name,
            'niveau_id' => $request->niveau_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'salle_id' => $request->salle_id,
        ]);

        // Charger les relations associées
        $classe->load(['niveau', 'anneeAcademique', 'salle']);

        return response()->json([
            'message' => 'Affection académique créée avec succès',
            'classe' => $classe
        ], 201);
    }

    // Mise à jour de l'affection académique
    private function updateAffectionAcademique(Classe $classe, Request $request)
    {

        // Vérifier si une affection académique similaire existe, autre que celle que l'on met à jour
        $exists = Classe::where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->where('id', '!=', $classe->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Cette classe est déjà affectée à ce niveau pour cette année académique.',
            ], 400);
        }

        // Mise à jour des informations de l'affection académique
        $classe->update([
            'name' => $request->name,
            'niveau_id' => $request->niveau_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'salle_id' => $request->salle_id,
        ]);

        // Charger les relations associées
        $classe->load(['niveau', 'anneeAcademique', 'salle']);

        return response()->json([
            'message' => 'Affection académique mise à jour avec succès',
            'classe' => $classe
        ], 200);
    }



    // Création d'une nouvelle affection académique



    public function destroy($id)
    {
        $affection = Classe::find($id);
        if (!$affection) {
            return response()->json([
                'message' => 'classe académique introuvable.'
            ], 404);
        }

        $affection->delete();

        return response()->json([
            'message' => 'Classe supprimée avec succès'
        ]);
    }
}
