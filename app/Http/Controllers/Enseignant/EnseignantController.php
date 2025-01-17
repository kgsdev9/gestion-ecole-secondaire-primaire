<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index()
    {
        $matieres = Matiere::all();
        $enseignants = Enseignant::with('matiere')->get();

        return view('enseignants.index', compact('matieres', 'enseignants'));
    }
}
