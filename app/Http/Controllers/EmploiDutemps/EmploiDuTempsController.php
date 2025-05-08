<?php

namespace App\Http\Controllers\EmploiDutemps;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\Classe;
use App\Models\EmploiDuTemps;
use App\Models\Jour;
use App\Models\Matiere;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;

class EmploiDuTempsController extends Controller
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

        $classes = Classe::with(['niveau', 'anneeAcademique', 'salle'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        return view('emploidutemps.index', compact('classes'));
    }

    public function configurationEmploiTime($classeID)
    {
        $classe = Classe::findOrFail($classeID);
        $matieres = Matiere::all();
        $jours = Jour::all();
        $emplois = EmploiDuTemps::where('classe_id', $classeID)->get();

        return view('emploidutemps.configuration', compact('classe', 'matieres', 'jours', 'emplois'));
    }




    public function store(Request $request)
    {
        $data = $request->all();
        $classeId = $data['classe_id'];
        $lignes = $data['emplois'];
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();
        foreach ($lignes as $ligne) {
            EmploiDuTemps::updateOrCreate(
                ['id' => $ligne['id'] ?? null],
                [
                    'classe_id'   => $classeId,
                    'matiere_id'  => $ligne['matiere_id'],
                    'jour_id'     => $ligne['jour_id'],
                    'heure_debut' => $ligne['heure_debut'],
                    'heure_fin'   => $ligne['heure_fin'],
                    'anneeacademique_id'=> $anneeScolaireActuelle->id
                ]
            );
        }

        return response()->json(['message' => 'Emploi du temps enregistré avec succès']);
    }





    // public function indexs()
    // {
    //     // Récupérer tous les emplois du temps avec les relations
    //     $emplois = EmploiDuTemps::with(['matiere', 'classe', 'jour'])->get();

    //     // Récupérer les jours sous forme d'un tableau de noms de jours
    //     $jours = Jour::pluck('name')->toArray();

    //     // Récupérer les heures de début uniques triées
    //     $heures = $emplois->pluck('heure_debut')->unique()->sort()->values();

    //     // Organiser les emplois par jour et heure
    //     $emploisParJourEtHeure = [];
    //     foreach ($emplois as $emploi) {
    //         $jour = $emploi->jour->name;
    //         $heureDebut = $emploi->heure_debut;

    //         $emploisParJourEtHeure[$jour][$heureDebut][] = [
    //             'matiere' => $emploi->matiere->name,
    //             'classe' => $emploi->classe->name,
    //             'heure_debut' => $emploi->heure_debut,
    //             'heure_fin' => $emploi->heure_fin,
    //         ];
    //     }

    //     // Récupérer les classes liées à AffectionAcademique
    //     $classes = Classe::with('niveau')->get();

    //     return view('emploidutemps', compact('emploisParJourEtHeure', 'jours', 'heures', 'classes'));
    // }










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
