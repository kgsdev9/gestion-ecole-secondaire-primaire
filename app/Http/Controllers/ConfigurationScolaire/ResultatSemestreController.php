<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\RapportSemestre;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
class ResultatSemestreController extends Controller
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
        $this->anneeAcademiqueService->checkAndCreateAnneeAcademique();
        
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        $resultatsemestres = RapportSemestre::with(['anneeacademique', 'niveau', 'classe', 'semestre'])
        ->where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->get();


        return view('configurations.resultats.index', compact('resultatsemestres'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($resultatid)
    {
        $this->anneeAcademiqueService->checkAndCreateAnneeAcademique();

        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        $resultatsemestres = RapportSemestre::with(['anneeacademique', 'niveau', 'classe', 'semestre'])
        ->where('anneeacademique_id', $anneeScolaireActuelle->id)
        ->where('id', $resultatid)
        ->first();

        $resultatsemestreslignes =$resultatsemestres->itemsRapport;
        return view('configurations.resultats.show', compact('resultatsemestres','resultatsemestreslignes'));
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
