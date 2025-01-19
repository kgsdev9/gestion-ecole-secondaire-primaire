<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Versement;
use Illuminate\Http\Request;

class VersementController extends Controller
{

    public function index()
    {
        $listeleves = Eleve::all();
        $versements  = Versement::with(['eleve', 'typeVersement'])->get();
        return view('versements.index', compact('listeleves', 'versements'));
    }
}
