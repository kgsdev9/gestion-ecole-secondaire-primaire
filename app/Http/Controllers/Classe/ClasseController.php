<?php

namespace App\Http\Controllers\Classe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Niveau;
use App\Models\Salle;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $niveaux = Niveau::all();
        $anneesAcademiques  = AnneeAcademique::all();
        $salles = Salle::all();
        $classes = AffectionAcademique::with(['classe', 'niveau', 'anneeAcademique', 'salle'])->get();
        return view('classes.index', compact('niveaux', 'anneesAcademiques', 'classes', 'salles'));
    }

    public function store(Request $request)
    {
        // Vérifier si l'ID de la classe existe dans la requête
        $classeId = $request->input('classe_id');

        if ($classeId) {
            // Si l'ID de la classe existe, on modifie la classe
            $classe = Classe::find($classeId);

            // Si la classe n'existe pas, on la crée
            if (!$classe) {
                return $this->createClasse($request);
            }

            // Si la classe existe, on la met à jour
            return $this->updateClasse($classe, $request);
        } else {
            // Si l'ID de la classe est absent, on crée une nouvelle classe
            return $this->createClasse($request);
        }
    }

    private function updateClasse(Classe $classe, Request $request)
    {
        // Mise à jour de la classe
        $classe->update([
            'name' => $request->name,
        ]);

        // Mettre à jour l'affection académique associée
        $this->updateAffectionAcademique($classe->id, $request);


        $classe->load(['classe', 'niveau', 'anneeAcademique', 'salle']);


        return response()->json([
            'message' => 'Classe mise à jour avec succès',
            'classe' => $classe
        ], 200);
    }

    private function createClasse(Request $request)
    {
        // Création d'une nouvelle classe
        $classecurrent = Classe::create([
            'name' => $request->name,
        ]);

        // Créer l'affection académique pour cette nouvelle classe
        $classe  = $this->createAffectionAcademique($classecurrent->id, $request);

     
        $classe->load(['classe', 'niveau', 'anneeAcademique', 'salle']);


        return response()->json([
            'message' => 'Classe créée avec succès',
            'classe' => $classe
        ], 201);
    }

    private function updateAffectionAcademique($classeId, Request $request)
    {
        // Mettre à jour l'affection académique si nécessaire
        $affection = AffectionAcademique::where('classe_id', $classeId)
            ->where('annee_academique_id', $request->annee_academique_id)
            ->first();

        if ($affection) {
            $affection->update([
                'niveau_id' => $request->niveau_id,
                'annee_academique_id' => $request->annee_academique_id,
                'salle_id' => $request->salle_id,
            ]);
        }
    }

    private function createAffectionAcademique($classeId, Request $request)
    {

        return AffectionAcademique::create([
            'classe_id' => $classeId,
            'niveau_id' => $request->niveau_id,
            'annee_academique_id' => $request->annee_academique_id,
            'salle_id' => $request->salle_id,
        ]);
    }
}
