<?php

namespace App\Http\Controllers\GestionScolaire\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Inscription;
use App\Models\MoyenneExamen;
use App\Models\ProgrammeExamen;
use Illuminate\Http\Request;

class GestionMoyenneExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('gestionscolaires.examens.moyennes.gestionmoyenneexamen');
    }


    public function saveMoyenneExamen($examneid)
    {
        $examen = Examen::find($examneid);

        $eleves = Inscription::with('eleve')
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->where('classe_id', $examen->classe_id)
            ->get()
            ->pluck('eleve');

        $programmexamens = ProgrammeExamen::with('matiere')
            ->where('examen_id', $examen->id)
            ->get();


        return view('gestionscolaires.examens.moyennes.gestionmoyenneexamen', compact('examen', 'eleves', 'programmexamens'));
    }


    public function store(Request $request)
    {
        $examen = Examen::find($request->examen_id);
        $anneeAcademiqueId = $examen->anneeacademique_id;

        foreach ($request->notes as $eleve_id => $matieres) {


            foreach ($matieres as $matiere_id => $note) {


                if (!is_null($note)) {
                    MoyenneExamen::updateOrCreate(
                        [
                            'eleve_id' => $eleve_id,
                            'matiere_id' => $matiere_id,
                            'examen_id' => $examen->id,
                            'annee_academique_id' => $anneeAcademiqueId,
                        ],
                        [
                            'moyenne' => $note,
                        ]
                    );
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
