<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;

class AnneAcademiqueController extends Controller
{
    protected $anneAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneAcademiqueService = $anneAcademiqueService;
    }

    public function index()
    {
        $listeanneeacademique = AnneeAcademique::with('inscriptions')->orderByDesc('created_at')->get();
        return view('anneacademique.index', compact('listeanneeacademique'));
    }

    public function active(Request $request)
    {
        $annee = AnneeAcademique::find($request->id);

        if (!$annee) {
            return response()->json([
                'success' => false,
                'message' => 'Année introuvable.'
            ], 404);
        }

        // Utilisation du service pour activer l'année et désactiver les autres
        $this->anneAcademiqueService->activer($annee->id);

        return response()->json([
            'success' => true,
            'message' => "L'année {$annee->libelle} a été activée avec succès.",
            'id_active' => $annee->id
        ]);
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
        $anneeAcademique->update([
            'name' => $request->name,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'active' => 1
        ]);

        $anneeAcademique->load('inscriptions');

        return response()->json([
            'message' => 'Année académique mise à jour avec succès.',
            'anneeAcademique' => $anneeAcademique
        ], 200);
    }


    // Méthode pour la création d'une nouvelle année académique
    private function createAnneeAcademique(Request $request)
    {
        $anneeAcademique = AnneeAcademique::create([
            'name' => $request->name,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        $anneeAcademique->load('inscriptions');
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
                'success' => false,
                'message' => 'Année académique introuvable.'
            ], 404);
        }

        // Supprimer l'année académique
        $anneeAcademique->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'success' => true,
            'message' => 'Année académique supprimée avec succès.'
        ], 200);
    }

}
