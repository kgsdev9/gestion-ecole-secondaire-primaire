<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
