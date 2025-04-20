<?php

namespace App\Http\Controllers\Semestre;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemestreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestionSemestre($id)
    {
        $anneacademique  =  AnneeAcademique::find($id);
        $semestres = $anneacademique->semestres;
        return view('semestres.create', compact('anneacademique', 'semestres'));
    }

    public function store(Request $request)
    {
        $semestre = Semestre::create([
            'anneeacademique_id' => $request->annee_academique_id,
            'name' => $request->name,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'cloture' => false,
        ]);

        return response()->json([
            'success' => true,
            'semestre' => $semestre
        ]);
    }

    public function destroy($id)
    {
        $semestre = Semestre::findOrFail($id);
        $semestre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semestre supprimé avec succès.'
        ]);
    }


    public function toggleCloture(Request $request)
    {
        $semestre = Semestre::find($request->id);

        if (!$semestre) {
            return response()->json([
                'success' => false,
                'message' => 'Semestre introuvable.'
            ], 404);
        }

        DB::transaction(function () use ($semestre) {

            Semestre::where('anneeacademique_id', $semestre->anneeacademique_id)->update(['active' => false]);
            $semestre->active = true;
            $semestre->save();
        });

        return response()->json([
            'success' => true,
            'message' => 'Semestre activé avec succès.',
            'id_active' => $semestre->id,
        ]);
    }
}
