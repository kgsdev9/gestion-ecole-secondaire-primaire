<?php

namespace App\Http\Controllers\configurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Moyenne;
use App\Models\Niveau;
use App\Models\Semestre;
use Illuminate\Http\Request;

class MoyenneScolaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        // Récupère les affectations de classes pour l'année en cours
        $classes = AffectionAcademique::with(['classe', 'niveau', 'salle'])
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->get();

        // Récupère tous les élèves inscrits cette année
        // Récupère tous les élèves affectés à cette année
        $eleves = Inscription::with('eleve')
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->get()
            ->pluck('eleve');


        // Récupère toutes les matières
        $matieres = Matiere::all();
        $niveaux = Niveau::all();
        $semestres = $anneeAcademiqueEnCours->semestres;
        $moyennes = Moyenne::where('annee_academique_id', $anneeAcademiqueEnCours->id)->get();


        return view('configurations.moyennes.gestionmoyenne', compact('classes', 'eleves', 'matieres', 'niveaux', 'semestres',  'moyennes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
