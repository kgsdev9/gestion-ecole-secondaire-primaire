<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TFacture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $listerecentesfactures = TFacture::where('numvente', 'like', 'POS%')->orderByDesc('created_at')->take(20)->get();
        $countlisterecentesfactures = TFacture::where('numvente', 'like', 'POS%')->sum('montantttc');
        $ventes  =  TFacture::where('numvente', 'like', 'POS%')->sum('montantttc');
        $counCclient = TClient::count();
        $counFaturesVentes = TFacture::where('numvente', 'like', 'POS%')->count();


        $bilan = TFacture::where('numvente', 'like', 'POS%')
            ->select(DB::raw('MONTH(created_at) as mois'), DB::raw('SUM(montantttc) as total'))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Transformer les données pour Chart.js
        $labels = $bilan->pluck('mois')->map(function ($mois) {
            return date('F', mktime(0, 0, 0, $mois, 1));
        });

        $data = $bilan->pluck('total');

        $ventesJour = TFacture::where('numvente', 'like', 'POS%')
            ->whereDate('created_at', Carbon::today())
            ->sum('montantttc');

        $ventesSemaine = TFacture::where('numvente', 'like', 'POS%')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('montantttc');


        $ventesMois = TFacture::where('numvente', 'like', 'POS%')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('montantttc');


        return view('welcome', compact('listerecentesfactures', 'countlisterecentesfactures', 'ventes', 'counCclient', 'counFaturesVentes', 'labels', 'data', 'ventesJour', 'ventesSemaine', 'ventesMois'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
