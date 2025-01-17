<?php

namespace App\Http\Controllers\Matiere;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::get();
      
        return view('matieres.index', compact('matieres'));
    }



    // Méthode pour créer ou modifier une matière
    public function store(Request $request)
    {


        // Vérifier si l'ID de la matière est fourni
        $matiereId = $request->input('matiere_id');

        if ($matiereId) {
            // Si l'ID existe, on récupère la matière et on la met à jour
            $matiere = Matiere::find($matiereId);

            // Si la matière n'existe pas, on la crée
            if (!$matiere) {
                return $this->createMatiere($request);
            }

            // Si la matière existe, on la met à jour
            return $this->updateMatiere($matiere, $request);
        } else {
            // Si l'ID est absent, on crée une nouvelle matière
            return $this->createMatiere($request);
        }
    }

    // Méthode pour créer une nouvelle matière
    private function createMatiere(Request $request)
    {
        $matiere = Matiere::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Matière créée avec succès.',
            'matiere' => $matiere
        ], 201);
    }

    // Méthode pour mettre à jour une matière existante
    private function updateMatiere($matiere, Request $request)
    {
        $matiere->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Matière mise à jour avec succès.',
            'matiere' => $matiere
        ], 200);
    }

    // Méthode pour supprimer une matière
    public function destroy($id)
    {
        $matiere = Matiere::find($id);

        if (!$matiere) {
            return response()->json(['message' => 'Matière non trouvée.'], 404);
        }

        $matiere->delete();

        return response()->json(['message' => 'Matière supprimée avec succès.'], 200);
    }
}
