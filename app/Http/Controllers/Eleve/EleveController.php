<?php

namespace App\Http\Controllers\Eleve;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Genre;
use App\Models\Inscription;
use App\Models\Niveau;
use App\Models\StatusEleve;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EleveController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $eleves = Eleve::with(['classe', 'anneeacademique', 'niveau', 'genre', 'statuseleve'])->get();
        $listeannee  = AnneeAcademique::all();
        $listeniveaux  = Niveau::all();
        $listeclasse  = Classe::all();
        $genres = Genre::all();
        $statuseleves = StatusEleve::all();

        return view('eleves.index', compact('eleves', 'listeannee', 'listeclasse', 'listeniveaux', 'genres', 'statuseleves'));
    }


    public function store(Request $request)
    {
        // Vérifier si eleve_id existe dans la requête
        $eleveId = $request->input('eleve_id');

        if ($eleveId) {
            // Si eleve_id existe, on modifie l'élève
            $eleve = Eleve::find($eleveId);

            // Si l'élève n'existe pas, créer un nouvel élève
            if (!$eleve) {
                // Créer un nouvel élève
                return $this->createEleve($request);
            }

            // Si l'élève existe, procéder à la mise à jour
            return $this->updateEleve($eleve, $request);
        } else {
            // Si eleve_id est absent, on crée un nouvel élève
            return $this->createEleve($request);
        }
    }

    private function updateEleve($eleve, Request $request)
    {
        // Vérification de doublon : même élève, même config, mais une autre inscription
        $doublon = Inscription::where('eleve_id', $eleve->id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->where('niveau_id', $request->niveau_id)
            ->where('classe_id', $request->classe_id)
            ->where('id', '!=', optional($eleve->inscriptions()->first())->id)
            ->exists();

        if ($doublon) {
            return response()->json([
                'message' => 'Échec : cet élève est déjà inscrit avec ces informations.',
                'type' => 'duplicate'
            ], 409);
        }



        // Mise à jour des infos élève
        $eleve->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'classe_id' => $request->classe_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'niveau_id' => $request->niveau_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone_parent' => $request->telephone_parant,
            'statuseleve_id' => $request->statuseleve_id,
            'nationalite' => $request->nationalite,
            'genre_id' => $request->genre_id,
        ]);

        // Mise à jour ou création de l'inscription
        $this->updateInscription($eleve, $request);

        $eleve->load('classe', 'anneeacademique', 'niveau', 'genre', 'statuseleve');

        return response()->json([
            'message' => 'Élève mis à jour avec succès',
            'eleve' => $eleve
        ], 200);
    }


    private function generateMatricule()
    {
        do {
            // Générer un matricule aléatoire ou selon votre logique
            $matricule = 'MAT-' . strtoupper(Str::random(8));
        } while (Eleve::where('matricule', $matricule)->exists()); // Vérifier si le matricule existe déjà

        return $matricule;
    }

    private function createEleve(Request $request)
    {
        // Vérification de l'existence d'une inscription identique pour l'élève
        $existe = Inscription::where('eleve_id', $request->eleve_id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->where('classe_id', $request->classe_id)
            ->where('niveau_id', $request->niveau_id)
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Cet élève est déjà inscrit dans cette classe pour cette année académique.',
                'type' => 'duplicate'
            ], 409); // 409 = Conflict
        }


      

        // Création d'un nouvel élève
        $eleve = Eleve::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $this->generateMatricule(),
            'classe_id' => $request->classe_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'niveau_id' => $request->niveau_id,
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone_parent' => $request->telephone_parant,
            'statuseleve_id' => $request->statuseleve_id,
            'nationalite' => $request->nationalite,
            'genre_id' => $request->genre_id,
        ]);


        // Création de l'inscription
        $this->createInscription($eleve, $request);

        $eleve->load('classe', 'anneeacademique', 'niveau', 'genre', 'statuseleve');

        return response()->json([
            'message' => 'Élève créé avec succès',
            'eleve' => $eleve
        ], 201);
    }


    // Méthode pour créer une inscription
    private function createInscription($eleve, Request $request)
    {
        $inscription = Inscription::create([
            'eleve_id' => $eleve->id,
            'niveau_id' => $request->niveau_id,
            'anneeacademique_id' => $request->annee_academique_id,
            'classe_id' => $request->classe_id,
            'date_inscription' => now(), // Date de l'inscription actuelle
        ]);
    }

    // Méthode pour mettre à jour l'inscription
    private function updateInscription($eleve, Request $request)
    {
        $inscription = Inscription::where('eleve_id', $eleve->id)
            ->where('anneeacademique_id', $request->annee_academique_id)
            ->first();

        if ($inscription) {
            $inscription->update([
                'niveau_id' => $request->niveau_id,
                'classe_id' => $request->classe_id,
                'date_inscription' => now(), // Date de l'inscription actuelle
            ]);
        } else {
            // Si aucune inscription n'est trouvée, créer une nouvelle inscription
            $this->createInscription($eleve, $request);
        }
    }
}
