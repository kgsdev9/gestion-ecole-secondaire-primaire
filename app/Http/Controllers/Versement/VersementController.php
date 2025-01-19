<?php

namespace App\Http\Controllers\Versement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersementController extends Controller
{

    public function index()
    {
        return view('versements.index');
    }
}
