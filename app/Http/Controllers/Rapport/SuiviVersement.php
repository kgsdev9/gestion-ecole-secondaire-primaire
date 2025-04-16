<?php

namespace App\Http\Controllers\Rapport;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\Inscription;
use Illuminate\Http\Request;

class SuiviVersement extends Controller
{

    public function index()
    {
        $listeclasse = AffectionAcademique::all();
        $listeeleves = Inscription::all();
        return view('versemens.suivi.index', compact('listeclasse', 'listeeleves'));
    }
}
