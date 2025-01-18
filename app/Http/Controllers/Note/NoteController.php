<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Note;
use App\Models\TypeNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        $semestresEnCours = [];
        if ($anneeAcademiqueEnCours) {
            $semestresEnCours = $anneeAcademiqueEnCours->semestres;
        }

        $niveaux = Niveau::all();
        $classes  = Classe::all();
        $listeAffectionAcademique = AffectionAcademique::all();
        $eleves = Inscription::with(['eleve.notes.typenote'])->get();
        $matieres = Matiere::all();
        $typenotes = TypeNote::all();
        return view('notes.index', compact('listeAffectionAcademique', 'classes', 'niveaux', 'eleves', 'matieres', 'typenotes', 'semestresEnCours'));
    }


    public function store(Request $request)
    {

        // Enregistrer la note directement
        $note = Note::create([
            'semestre_id' => $request->semestre_id ?? 2,
            'eleve_id'    =>  2,
            'matiere_id'  => $request->matiere_id,
            'typenote_id' => $request->typenote_id,
            'note'        => $request->note,
        ]);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Note enregistrée avec succès.',
            'note'    => $note,
        ], 201);
    }
}
