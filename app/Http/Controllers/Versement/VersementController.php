<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use Illuminate\Http\Request;

class VersementController extends Controller
{

    public function index()
    {
        $listeleves = Eleve::all();
        return view('versements.index', compact('listeleves'));
    }
}
