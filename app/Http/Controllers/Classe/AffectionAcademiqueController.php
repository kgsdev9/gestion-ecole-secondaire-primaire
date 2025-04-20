<?php

namespace App\Http\Controllers\Classe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Niveau;
use App\Models\Salle;

class AffectionAcademiqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // Vérification si une affection académique existe déjà


    public function index()
    {
        $niveaux = Niveau::all();
        $anneesAcademiques  = AnneeAcademique::all();
        $salles = Salle::all();
        $classe = Classe::all();
        $classes = AffectionAcademique::with(['classe', 'niveau', 'anneeAcademique', 'salle'])->get();

        return view('classes.affectionsacademiques.index', compact('niveaux', 'anneesAcademiques', 'classes', 'salles', 'classe'));
    }

    public function store(Request $request)
    {
        // Vérifier si l'ID de l'affection académique existe dans la requête
        $affectionAcademiqueId = $request->input('affectionacademique_id');

        if ($affectionAcademiqueId) {
            // Si l'ID de l'affection académique existe, on modifie l'affection académique
            $affectionAcademique = AffectionAcademique::find($affectionAcademiqueId);

            // Si l'affection académique n'existe pas, on la crée
            if (!$affectionAcademique) {
                return $this->createAffectionAcademique($request);
            }

            // Si l'affection académique existe, on la met à jour
            return $this->updateAffectionAcademique($affectionAcademique, $request);
        } else {
            // Si l'ID de l'affection académique est absent, on crée une nouvelle affection académique
            return $this->createAffectionAcademique($request);
        }
    }

    // Mise à jour de l'affection académique
    private function updateAffectionAcademique(AffectionAcademique $affectionAcademique, Request $request)
    {

        // Vérifier si une affection académique similaire existe, autre que celle que l'on met à jour
        $exists = AffectionAcademique::where('classe_id', $request->classe_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->where('id', '!=', $affectionAcademique->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Cette classe est déjà affectée à ce niveau pour cette année académique.',
            ], 400);
        }

        // Mise à jour des informations de l'affection académique
        $affectionAcademique->update([
            'classe_id' => $request->classe_id,
            'niveau_id' => $request->niveau_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'salle_id' => $request->salle_id,
        ]);

        // Charger les relations associées
        $affectionAcademique->load(['classe', 'niveau', 'anneeAcademique', 'salle']);

        return response()->json([
            'message' => 'Affection académique mise à jour avec succès',
            'classe' => $affectionAcademique
        ], 200);
    }



    // Création d'une nouvelle affection académique
    private function createAffectionAcademique(Request $request)
    {
      
        $exists = AffectionAcademique::where('classe_id', $request->classe_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->exists();



        if ($exists) {
            return response()->json([
                'message' => 'Cette classe est déjà affectée à ce niveau pour cette année académique.',
            ], 400); // Erreur côté client
        }

        // Création d'une nouvelle affection académique
        $affectionAcademique = AffectionAcademique::create([
            'classe_id' => $request->classe_id,
            'niveau_id' => $request->niveau_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'salle_id' => $request->salle_id,
        ]);

        // Charger les relations associées
        $affectionAcademique->load(['classe', 'niveau', 'anneeAcademique', 'salle']);

        return response()->json([
            'message' => 'Affection académique créée avec succès',
            'classe' => $affectionAcademique
        ], 201);
    }


    public function destroy($id)
    {
        $affection = AffectionAcademique::find($id);
        if (!$affection) {
            return response()->json([
                'message' => 'Affection académique introuvable.'
            ], 404);
        }

        $affection->delete();

        return response()->json([
            'message' => 'Classe supprimée avec succès'
        ]);
    }
}
