<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Matiere;
use App\Models\ProgrammeExamen;
use App\Models\Repartition;
use App\Models\Salle;
use Illuminate\Http\Request;

class GestionExamenController extends Controller
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
    public function createProgrammeExamen($id)
    {
        $examen = Examen::findOrFail($id);
        $matieres = Matiere::all() ?? [];
        $programmeexamens = $examen->examenProgrammes()->with('matiere')->get();

        return view('examens.listeexamens.programme.programmeexamen', compact('examen', 'programmeexamens', 'matieres', 'examen'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function createRepartition($id)
    {
        $examen = Examen::with('classe.students')->findOrFail($id);
        $salles = Salle::all();
        $eleves = $examen->classe->students()->orderBy('nom')->get();

        $index = 0;
        $totalEleves = $eleves->count();

        foreach ($salles as $salle) {
            $capacite = $salle->capacite;

            for ($i = 0; $i < $capacite && $index < $totalEleves; $i++) {
                Repartition::create([
                    'examen_id' => $examen->id,
                    'eleve_id' => $eleves[$index]->id,
                    'salle_id' => $salle->id,
                    'annee_academique_id' => $examen->anneeacademique_id,
                ]);

                $index++;
            }
            if ($index >= $totalEleves) {
                break;
            }
        }

        if ($index < $totalEleves)
        {
            return back()->with('error', 'Pas assez de salles pour tous les élèves.');
        }

        return view('examens.repartition.examen.gestionrepartition');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $examenId = $request->input('examen_id');
        $programmes = json_decode($request->input('programmeexamen'), true);


        ProgrammeExamen::where('examen_id', $examenId)->delete();

        // Créer les nouveaux programmes
        foreach ($programmes as $programme) {
            $instance = ProgrammeExamen::create([
                'examen_id' => $examenId,
                'matiere_id' => $programme['matiere_id'],
                'heure_debut' => $programme['heure_debut'],
                'heure_fin' => $programme['heure_fin'],
                'jour' => $programme['jour'],
                'duree' => $programme['duree'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Programme d\'examen enregistré avec succès.'
        ]);
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
