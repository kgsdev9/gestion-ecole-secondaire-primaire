<?php

namespace App\Http\Controllers\ConfigurationScolaire;

use App\Http\Controllers\Controller;
use App\Models\Examen;
use App\Models\Matiere;
use App\Models\ProgrammeExamen;
use App\Models\Repartition;
use App\Models\RepartitionDetail;
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

    public function createRepartition($id)
    {

        $examen = Examen::with('classe.students')->findOrFail($id);

        $salles = Salle::all();
        $eleves = $examen->classe->students()->orderBy('nom')->get();

        $index = 0;
        $totalEleves = $eleves->count();

        $repartitionexiste = RepartitionDetail::where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->exists();
       
        if (!$repartitionexiste) {
            foreach ($salles as $salle) {
                $capacite = $salle->capacite;


                for ($i = 0; $i < $capacite && $index < $totalEleves; $i++) {
                    RepartitionDetail::create([
                        'examen_id' => $examen->id,
                        'eleve_id' => $eleves[$index]->id,
                        'salle_id' => $salle->id,
                        'anneeacademique_id' => $examen->anneeacademique_id,
                    ]);

                    $index++;
                }
                if ($index >= $totalEleves) {
                    break;
                }
            }

            if ($index < $totalEleves) {

                return back()->with('error', 'Pas assez de salles pour tous les élèves.');
            }
        }

        $repartitions = RepartitionDetail::with(['examen', 'eleve', 'salle', 'anneeAcademique'])
            ->where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->get();


        return view('examens.repartition.examen.gestionrepartition', compact('repartitions', 'examen'));
    }


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
}
