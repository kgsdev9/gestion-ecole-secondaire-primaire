<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneAcademiqueController extends Controller
{
    public function index()
    {
        $listeanneeacademique = AnneeAcademique::orderByDesc('created_at')->get();
        return view('anneacademique.index', compact('listeanneeacademique'));
    }

    public function store(Request $request)
    {
        // Vérifier si l'ID d'une année académique existe dans la requête
        $anneeAcademiqueId = $request->input('annee_academique_id');

        if ($anneeAcademiqueId) {
            // Si l'ID existe, on tente de récupérer l'année académique
            $anneeAcademique = AnneeAcademique::find($anneeAcademiqueId);

            // Si l'année académique n'existe pas, créer une nouvelle
            if (!$anneeAcademique) {
                return $this->createAnneeAcademique($request);
            }

            // Si l'année académique existe déjà, mettre à jour
            return $this->updateAnneeAcademique($anneeAcademique, $request);
        } else {
            // Si l'ID est absent, créer une nouvelle année académique
            return $this->createAnneeAcademique($request);
        }
    }

    // Méthode pour la mise à jour d'une année académique existante
    private function updateAnneeAcademique($anneeAcademique, Request $request)
    {
        // Mettre à jour l'année académique avec les données de la requête
        $anneeAcademique->update([
            'name' => $request->name,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        // Retourner une réponse JSON avec le message de succès et l'année académique mise à jour
        return response()->json([
            'message' => 'Année académique mise à jour avec succès.',
            'anneeAcademique' => $anneeAcademique
        ], 200);
    }

    // Méthode pour la création d'une nouvelle année académique
    private function createAnneeAcademique(Request $request)
    {
        // Créer une nouvelle année académique
        $anneeAcademique = AnneeAcademique::create([
            'name' => $request->name,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        // Retourner une réponse JSON avec le message de succès et l'année académique créée
        return response()->json([
            'message' => 'Année académique créée avec succès.',
            'anneeAcademique' => $anneeAcademique
        ], 201);
    }

    // Méthode pour la suppression d'une année académique
    public function destroy($id)
    {
        // Chercher l'année académique par ID
        $anneeAcademique = AnneeAcademique::find($id);

        // Si l'année académique n'existe pas, retourner une erreur
        if (!$anneeAcademique) {
            return response()->json([
                'message' => 'Année académique introuvable.'
            ], 404);
        }

        // Supprimer l'année académique
        $anneeAcademique->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'Année académique supprimée avec succès.'
        ], 200);
    }
}
