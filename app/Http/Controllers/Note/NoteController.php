<?php

namespace App\Http\Controllers\Note;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $listenotes = Note::all();
        $listeannee = AnneeAcademique::all();
        $niveaux = Niveau::all();
        $classes  = Classe::all();
        $listeAffectionAcademique = AffectionAcademique::all();
        $eleves = Inscription::with(['eleve.notes'])->get();
        $matieres = Matiere::all();
        return view('notes.index', compact('listeAffectionAcademique', 'classes', 'niveaux', 'eleves', 'matieres'));
    }
}
