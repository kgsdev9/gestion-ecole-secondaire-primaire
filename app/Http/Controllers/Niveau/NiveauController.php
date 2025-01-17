<?php

namespace App\Http\Controllers\Niveau;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{


    public function index()
    {   
        $niveaux = Niveau::all();
        return view('niveaux.index', compact('niveaux'));
    }

    public function store(Request $request)
    {
        // Vérifier si niveau_id existe dans la requête
        $niveauId = $request->input('niveau_id');

        if ($niveauId) {
            // Si niveau_id existe, on modifie le niveau
            $niveau = Niveau::find($niveauId);

            // Si le niveau n'existe pas, le créer
            if (!$niveau) {
                return $this->createNiveau($request);
            }

            // Si le niveau existe, procéder à la mise à jour
            return $this->updateNiveau($niveau, $request);
        } else {
            // Si niveau_id est absent, on crée un nouveau niveau
            return $this->createNiveau($request);
        }
    }

    private function updateNiveau(Niveau $niveau, Request $request)
    {
        // Met à jour le niveau
        $niveau->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Niveau mis à jour avec succès', 'niveau' => $niveau], 200);
    }

    private function createNiveau(Request $request)
    {
        // Création d'un nouveau niveau
        $niveau = Niveau::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Niveau créé avec succès', 'niveau' => $niveau], 201);
    }

    public function destroy($id)
    {
        $niveau = Niveau::find($id);

        if ($niveau) {
            $niveau->delete();
            return response()->json(['success' => true, 'message' => 'Niveau supprimé avec succès']);
        }

        return response()->json(['success' => false, 'message' => 'Niveau non trouvé']);
    }
}
