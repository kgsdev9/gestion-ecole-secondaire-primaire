<?php

namespace App\Http\Controllers\Inscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\AnneeAcademique;


class InscriptionController extends Controller
{

    public function index()
    {
        $inscriptions = Inscription::with(['eleve', 'classe', 'niveau', 'anneeAcademique'])->get();

        $eleves = Eleve::all();
        $niveaux = Niveau::all();
        $classes = Classe::all();
        $anneesAcademiques = AnneeAcademique::all();
        return view('inscriptions.index', compact('inscriptions', 'eleves', 'niveaux', 'classes', 'anneesAcademiques'));
    }
}
