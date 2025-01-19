<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Scolarite;
use App\Models\TypeVersement;
use App\Models\Versement;
use Illuminate\Http\Request;

class VersementController extends Controller
{

    public function index()
    {
        $listeleves = Eleve::all();
        $versements  = Versement::with(['eleve', 'typeVersement'])->get();
        $listescolarite = Scolarite::all();
        $typeversement  = TypeVersement::all();
        return view('versements.index', compact('listeleves', 'versements', 'listescolarite', 'typeversement'));
    }



    public function store(Request $request)
    {
        // Création du versement sans validation (en supposant que les données sont valides)
        $versement = Versement::create([
            'montant_verse' => $request->montant_verse,
            'montant_restant' => $request->montant_reliquat,
            'typeversement_id' => $request->typeversement_id,
            'date_versement' => $request->date_versement,
            'eleve_id' => $request->eleve_id,
            'reference' => rand(1000, 34445),
        ]);

        $versement->load(['typeVersement', 'eleve']);
        // Réponse JSON pour indiquer que le versement a été créé
        return response()->json([
            'message' => 'Versement créé avec succès.',
            'versement' => $versement
        ], 201);
    }
}
