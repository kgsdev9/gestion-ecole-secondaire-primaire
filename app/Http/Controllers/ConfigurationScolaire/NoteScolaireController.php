<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Moyenne;
use App\Models\Note;
use App\Models\Semestre;
use App\Models\TypeNote;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;

class NoteScolaireController extends Controller
{
    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $anneeAcademiqueEnCours  = $this->anneeAcademiqueService->getAnneeActive();

        $classes = Classe::with(['niveau', 'salle'])
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->get();

        return view('configurations.notes.gestionote', compact('classes'));
    }

    public function gestionNote($classe)
    {
        $classe = Classe::find($classe);

        $students  = $classe->students;
        $matieres = Matiere::all();
        $typenotes = TypeNote::all();
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        $notes = Note::where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->whereIn('eleve_id', $students->pluck('id'))
            ->with(['matiere', 'typenote'])
            ->get();
        $semestres = $anneeScolaireActuelle->semestres()->where('active', true)->first();

        return view('configurations.notes.note', compact('classe', 'students', 'matieres', 'typenotes', 'notes', 'semestres'));
    }


    public function addNote(Request $request)
    {
        $anneeAcademiqueEnCours  = $this->anneeAcademiqueService->getAnneeActive();

        $semestre = Semestre::find($request->semestre_id);


        if (!$semestre) {
            return response()->json([
                'status' => 'error',
                'message' => 'Semestre non trouvé.',
            ], 404);
        }


        // Création de la note
        $note = Note::create([
            'eleve_id' => $request->eleve_id,
            'matiere_id' => $request->matiere_id,
            'typenote_id' => $request->typenote_id,
            'note' => $request->note,
            'anneeacademique_id' => $anneeAcademiqueEnCours->id,
            'semestre_id' => $semestre->id,
        ]);

        // Charger les relations utiles
        $note->load('matiere', 'typenote');

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

        $anneeAcademiqueEnCours  = $this->anneeAcademiqueService->getAnneeActive();
        $semestre = Semestre::find($request->semestre_id);

        // Récupération des notes à valider
        $notes = Note::where('matiere_id', $matiereId)
            ->where('eleve_id', $request->eleve_id)
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->where('semestre_id', $semestre->id)
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
                'eleve_id' => $request->eleve_id,
                'matiere_id' => $matiereId,
                'semestre_id' => $semestre->id,
                'anneeacademique_id' => $anneeAcademiqueEnCours->id,
            ],
            ['moyenne' => $moyenne]
        );

        return response()->json(['message' => 'Moyenne validée avec succès.', 'moyenne' => $moyenne]);
    }
}
