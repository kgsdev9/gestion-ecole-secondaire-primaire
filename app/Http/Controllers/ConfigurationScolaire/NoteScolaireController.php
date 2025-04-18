<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\TypeNote;
use Illuminate\Http\Request;

class NoteScolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        $classes = AffectionAcademique::with(['classe', 'niveau', 'salle'])
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->get();

        return view('configurations.notes.gestionote', compact('classes'));
    }

    public function gestionNote($classe)
    {
        $classe = AffectionAcademique::find($classe);

        $students  = $classe->classe->students;
        $matieres = Matiere::all();
        $typenotes = TypeNote::all();
        $notes = Note::whereIn('eleve_id', $students->pluck('id'))->with(['matiere', 'typenote'])->get();

        return view('configurations.notes.note', compact('classe', 'students', 'matieres', 'typenotes', 'notes'));
    }



    public function addNote(Request $request)
    {
        // Création de la note directement
        $note = Note::create([
            'eleve_id' => $request->eleve_id,
            'matiere_id' => $request->matiere_id,
            'typenote_id' => $request->typenote_id,
            'note' => $request->note,
            'semestre_id' => 11,
        ]);

        // Charger les relations utiles pour l'affichage
        $note->load('matiere', 'typenote');

        // Retour en JSON pour Alpine.js
        return response()->json([
            'status' => 'success',
            'note' => $note,
        ]);
    }


    public function destroy($id)
    {
        try {
            $note = Note::findOrFail($id);
            $note->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression'], 500);
        }
    }
}
