<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\ResultatExamen;

class ResultatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resultats = ResultatExamen::with(['examen', 'anneeAcademique'])->get();

        return view('examens.resultats.index',compact('resultats'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($codeexamen)
    {
        $examen = ResultatExamen::where('code', $codeexamen)->first();
        $resultatexamens = $examen->resultatExamensLignes;
        return view('examens.resultats.show', compact('resultatexamens', 'examen'));

    }


}
