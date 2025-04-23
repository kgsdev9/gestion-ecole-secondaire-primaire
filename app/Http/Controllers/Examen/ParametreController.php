<?php

namespace App\Http\Controllers\Examen;
use App\Http\Controllers\Controller;
class ParametreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('examens.parametres.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function closeExamen()
    {
        //
    }

    public function openExamen()
    {

    }

}
