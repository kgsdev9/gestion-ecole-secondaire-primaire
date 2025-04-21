<?php

namespace App\Http\Controllers\Scolarite;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Scolarite;
use App\Models\Niveau;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;

class ScolariteController extends Controller
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

        $scolarites = Scolarite::with(['niveau', 'classe', 'anneeAcademique'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        $niveaux = Niveau::all();
        $classes = Classe::with(['niveau', 'anneeAcademique', 'salle'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();
        $anneesAcademiques =  $this->anneeAcademiqueService->getAnneeActive();

        return view('scolarites.index', compact('scolarites', 'niveaux', 'classes', 'anneesAcademiques'));
    }


    public function store(Request $request)
    {
        // Vérifier si l'ID de la scolarité existe dans la requête
        $scolariteId = $request->input('scolarite_id');

        if ($scolariteId) {
            // Si l'ID de la scolarité existe, on modifie la scolarité
            $scolarite = Scolarite::find($scolariteId);

            // Si la scolarité n'existe pas, on crée une nouvelle scolarité
            if (!$scolarite) {
                return $this->createScolarite($request);
            }

            // Si la scolarité existe, on procède à la mise à jour
            return $this->updateScolarite($scolarite, $request);
        } else {
            // Si l'ID de la scolarité est absent, on crée une nouvelle scolarité
            return $this->createScolarite($request);
        }
    }

    private function updateScolarite($scolarite, Request $request)
    {
        // Vérifie s'il existe déjà une autre scolarité avec les mêmes données
        $exists = Scolarite::where('classe_id', $request->classe_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->where('id', '!=', $scolarite->id) // Sauf celle qu'on est en train de modifier
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Une scolarité existe déjà pour cette classe, ce niveau et cette année académique.',
            ], 400);
        }

        // Mise à jour des informations de la scolarité
        $scolarite->update([
            'niveau_id' => $request->niveau_id,
            'classe_id' => $request->classe_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'montant_scolarite' => $request->montant_scolarite,
        ]);

        $scolarite->load(['niveau', 'classe', 'anneeAcademique']);

        return response()->json([
            'message' => 'Scolarité mise à jour avec succès',
            'scolarite' => $scolarite
        ], 200);
    }


    private function createScolarite(Request $request)
    {
        // Vérification si une scolarité existe déjà pour cette combinaison
        $exists = Scolarite::where('classe_id', $request->classe_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Une scolarité existe déjà pour cette classe, ce niveau et cette année académique.',
            ], 400); // Erreur côté client
        }

        // Création d'une nouvelle scolarité
        $scolarite = Scolarite::create([
            'niveau_id' => $request->niveau_id,
            'classe_id' => $request->classe_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'montant_scolarite' => $request->montant_scolarite,
        ]);

        $scolarite->load(['niveau', 'classe', 'anneeAcademique']);

        return response()->json([
            'message' => 'Scolarité créée avec succès',
            'scolarite' => $scolarite
        ], 201);
    }

    public function destroy($id)
    {
        $scolarite = Scolarite::find($id);

        if (!$scolarite) {
            return response()->json([
                'message' => 'Scolarité non trouvée.',
            ], 404);
        }

        $scolarite->delete();

        return response()->json([
            'message' => 'Scolarité supprimée avec succès.',
        ], 200);
    }
}
