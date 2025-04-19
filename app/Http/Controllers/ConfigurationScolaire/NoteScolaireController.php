<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Matiere;
use App\Models\Moyenne;
use App\Models\Note;
use App\Models\TypeNote;
use Illuminate\Http\Request;

class NoteScolaireController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
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
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        $notes = Note::where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->whereIn('eleve_id', $students->pluck('id'))
            ->with(['matiere', 'typenote'])
            ->get();


        return view('configurations.notes.note', compact('classe', 'students', 'matieres', 'typenotes', 'notes'));
    }



    public function addNote(Request $request)
    {

        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        // Création de la note directement
        $note = Note::create([
            'eleve_id' => $request->eleve_id,
            'matiere_id' => $request->matiere_id,
            'typenote_id' => $request->typenote_id,
            'note' => $request->note,
            'anneeacademique_id' => $anneeAcademiqueEnCours->id,
            'semestre_id' => 1,
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


    public function validerMoyenne($matiereId, Request $request)
    {

        $annee = AnneeAcademique::anneeAcademiqueEnCours();
        $semestre = 1;

        // Récupération des notes à valider
        $notes = Note::where('matiere_id', $matiereId)
            ->where('eleve_id', $request->eleve_id)
            ->where('anneeacademique_id', $annee->id)
            ->where('semestre_id', $semestre)
            ->get();

        if ($notes->isEmpty()) {
            return response()->json(['message' => 'Aucune note trouvée.'], 404);
        }

        // Calcul de la moyenne
        $moyenne = round($notes->avg('note'), 2);

        // Mise à jour des status des notes
        $notes->each(function ($note) {
            $note->status = true;
            $note->save();
        });

        // Enregistrement ou mise à jour de la moyenne
        Moyenne::updateOrCreate(
            [
                'eleve_id' =>$request->eleve_id,
                'matiere_id' => $matiereId,
                'semestre_id' => $semestre,
                'annee_academique_id' => $annee->id,
            ],
            ['moyenne' => $moyenne]
        );

        return response()->json(['message' => 'Moyenne validée avec succès.', 'moyenne' => $moyenne]);
    }



    
}
