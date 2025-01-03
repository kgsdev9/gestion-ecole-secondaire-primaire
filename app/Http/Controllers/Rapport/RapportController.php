<?php

namespace App\Http\Controllers\Rapport;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RapportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateRapportForm()
    {

        $yearStart = Carbon::now()->month >= 9
            ? Carbon::create(Carbon::now()->year, 9, 1)  // Si on est après août, début de l'année académique en septembre de l'année en cours
            : Carbon::create(Carbon::now()->year - 1, 9, 1); // Sinon, début de l'année académique en septembre de l'année précédente

        $listeventesdays = TFacture::query()
            ->where('numvente', 'like', 'vp%')
            ->whereYear('created_at', Carbon::now()->year)  // Filtrer par année actuelle
            ->whereMonth('created_at', Carbon::now()->month)  // Filtrer par mois actuel
            ->orderByDesc('created_at')
            ->sum('montantttc');


        $listeventesacademic = TFacture::query()
            ->where('numvente', 'like', 'vp%')  // Filtrer uniquement les ventes qui commencent par "vp"
            ->whereYear('created_at', $yearStart->year)  // Filtrer les ventes de l'année académique
            ->orderByDesc('created_at')  // Trier les résultats par date décroissante
            ->sum('montantttc');
        return view('rapport.index', compact('listeventesdays', 'listeventesacademic'));
    }


    public function generateRapport(Request $request)
    {
        // Récupérer les critères envoyés par Alpine.js
        $timeframe = $request->input('timeframe'); // 'day', 'month', 'week'
        $startDate = $request->input('start_date'); // Date début
        $endDate = $request->input('end_date'); // Date fin

        // Construire la requête de base
        $query = TFacture::query()
            ->where('numvente', 'like', 'vp%');  // Filtrer uniquement les ventes qui commencent par "vp"

        // Application des filtres en fonction de la période sélectionnée
        if ($timeframe == 'day' && $startDate && $endDate) {
            // Filtrer par jour si la période est "Jour"
            $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay())
                ->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        } elseif ($timeframe == 'month' && $startDate && $endDate) {
            // Filtrer par mois si la période est "Mois"
            $query->whereMonth('created_at', Carbon::parse($startDate)->month)
                ->whereYear('created_at', Carbon::parse($startDate)->year);
        } elseif ($timeframe == 'week' && $startDate && $endDate) {
            // Filtrer par semaine si la période est "Semaine"
            $query->whereBetween('created_at', [Carbon::parse($startDate)->startOfWeek(), Carbon::parse($endDate)->endOfWeek()]);
        }

        // Récupérer les résultats
        $listeventes = $query->orderByDesc('created_at')->get();  // Trier par date décroissante

        return response()->json($listeventes);  // Vous pouvez renvoyer la réponse sous forme de JSON
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateRapportss()
    {
        dd('ss');
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
