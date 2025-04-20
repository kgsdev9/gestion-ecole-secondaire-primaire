<?php

namespace App\Http\Controllers\GestionScolaire\Examen;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Inscription;
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
        dd($request->all());
    }
}
