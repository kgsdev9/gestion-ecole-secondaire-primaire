<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Semestre;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    // Affiche la liste des enseignants
    public function index()
    {
        $matieres = Matiere::all();
        $enseignants = Enseignant::with('matiere')->get();

         
        return view('enseignants.index', compact('matieres', 'enseignants'));
    }

    // Créer ou mettre à jour un enseignant
    public function store(Request $request)
    {
        // Vérification de l'ID de l'enseignant
        $enseignantId = $request->input('enseignant_id');

        if ($enseignantId) {
            // Si l'enseignant existe, procéder à la mise à jour
            $enseignant = Enseignant::find($enseignantId);

            if (!$enseignant) {
                // Si l'enseignant n'existe pas, on crée un nouveau
                return $this->createEnseignant($request);
            }

            // Mise à jour de l'enseignant
            return $this->updateEnseignant($enseignant, $request);
        } else {
            // Si aucun ID n'est fourni, on crée un nouveau enseignant
            return $this->createEnseignant($request);
        }
    }

    // Fonction pour créer un enseignant
    private function createEnseignant(Request $request)
    {
        // Création d'un nouvel enseignant
        $enseignant = Enseignant::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'matiere_id' => $request->matiere_id,
        ]);

        $enseignant->load('matiere');

        return response()->json(['message' => 'Enseignant créé avec succès', 'enseignant' => $enseignant], 201);
    }

    // Fonction pour mettre à jour un enseignant
    private function updateEnseignant($enseignant, Request $request)
    {

        // Mise à jour de l'enseignant
        $enseignant->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $request->matricule,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'matiere_id' => $request->matiere_id,
        ]);

        $enseignant->load('matiere');

        return response()->json(['message' => 'Enseignant mis à jour avec succès', 'enseignant' => $enseignant], 200);
    }

    // Supprimer un enseignant
    public function destroy($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        $enseignant->delete();

        return response()->json(['message' => 'Enseignant supprimé avec succès'], 200);
    }
}
