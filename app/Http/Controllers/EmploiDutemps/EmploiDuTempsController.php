<?php

namespace App\Http\Controllers\EmploiDutemps;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\Classe;
use App\Models\EmploiDuTemps;
use App\Models\Jour;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }




    public function index()
    {
        // Récupérer tous les emplois du temps avec les relations
        $emplois = EmploiDuTemps::with(['matiere', 'classe', 'jour'])->get();

        // Récupérer les jours sous forme d'un tableau de noms de jours
        $jours = Jour::pluck('name')->toArray();

        // Récupérer les heures de début uniques triées
        $heures = $emplois->pluck('heure_debut')->unique()->sort()->values();

        // Organiser les emplois par jour et heure
        $emploisParJourEtHeure = [];
        foreach ($emplois as $emploi) {
            $jour = $emploi->jour->name;
            $heureDebut = $emploi->heure_debut;

            $emploisParJourEtHeure[$jour][$heureDebut][] = [
                'matiere' => $emploi->matiere->name,
                'classe' => $emploi->classe->name,
                'heure_debut' => $emploi->heure_debut,
                'heure_fin' => $emploi->heure_fin,
            ];
        }

        // Récupérer les classes liées à AffectionAcademique
        $classes = Classe::with('niveau')->get();

        return view('emploidutemps', compact('emploisParJourEtHeure', 'jours', 'heures', 'classes'));
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
