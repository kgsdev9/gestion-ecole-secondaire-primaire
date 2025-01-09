<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;


class AnneAcademiqueController extends Controller
{
    public function index()
    {
        $listeanneeacademique = AnneeAcademique::orderByDesc('created_at')->get();
        return view('anneacademique.index', compact('listeanneeacademique'));
    }
}
