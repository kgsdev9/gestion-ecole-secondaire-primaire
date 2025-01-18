<?php

namespace App\Http\Controllers\Classe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classe::all();
        return view('classes.classes.index', compact('classes'));
    }


    public function store(Request $request)
    {
        // Vérifier si l'id de la classe existe dans la requête
        $classeId = $request->input('classe_id');

        if ($classeId) {
            // Si l'ID existe, on modifie la classe
            $classe = Classe::find($classeId);

            // Si la classe n'existe pas, on crée une nouvelle classe
            if (!$classe) {
                return $this->createClasse($request);
            }

            // Si la classe existe, procéder à la mise à jour
            return $this->updateClasse($classe, $request);
        } else {
            // Si l'ID est absent, on crée une nouvelle classe
            return $this->createClasse($request);
        }
    }

    private function updateClasse(Classe $classe, Request $request)
    {
        // Mettre à jour la classe avec le nom fourni
        $classe->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Classe mise à jour avec succès', 'classe' => $classe], 200);
    }

    private function createClasse(Request $request)
    {
        // Créer une nouvelle classe avec le nom fourni
        $classe = Classe::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Classe créée avec succès', 'classe' => $classe], 201);
    }

    public function destroy($id)
    {
        // Trouver la classe à supprimer
        $classe = Classe::find($id);

        if (!$classe) {
            return response()->json(['message' => 'Classe non trouvée'], 404);
        }

        // Supprimer la classe
        $classe->delete();

        return response()->json(['message' => 'Classe supprimée avec succès'], 200);
    }
}
