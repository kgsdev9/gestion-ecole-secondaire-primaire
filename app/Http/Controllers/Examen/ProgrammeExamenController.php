<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Examen;
use App\Models\TypeExamen;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
class ProgrammeExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $anneeAcademiqueService;
     public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
     {
         $this->middleware('auth');
         $this->anneeAcademiqueService = $anneeAcademiqueService;
     }
    public function index()
    {


        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        $classe = Classe::all();
        $anneAcademique = AnneeAcademique::all();
        $typexamen = TypeExamen::all();
        $listeexamens = Examen::with('anneeAcademique', 'typeExamen', 'classe')
                                ->where('anneeacademique_id', $anneeScolaireActuelle->id)
                                ->get();
        return view('examens.programmes.index', compact('listeexamens', 'classe', 'anneAcademique', 'typexamen'));
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
