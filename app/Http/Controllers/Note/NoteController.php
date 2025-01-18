<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        $listeannee = AnneeAcademique::all();
        $niveaux = Niveau::all();
        $classes  = Classe::all();
        $listeAffectionAcademique = AffectionAcademique::all();
        $eleves = Inscription::with(['eleve.notes.typenote'])->get();
        $matieres = Matiere::all();
        return view('notes.index', compact('listeAffectionAcademique', 'classes', 'niveaux', 'eleves', 'matieres'));
    }
}
