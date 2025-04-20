<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $salles = Salle::all();
        return view('salles.index', compact('salles'));
    }

    public function store(Request $request)
    {
        // Vérifier si salle_id existe dans la requête
        $salleId = $request->input('salle_id');

        if ($salleId) {
            // Si salle_id existe, on modifie la salle
            $salle = Salle::find($salleId);

            // Si la salle n'existe pas, on la crée
            if (!$salle) {
                return $this->createSalle($request);
            }

            // Si la salle existe, procéder à la mise à jour
            return $this->updateSalle($salle, $request);
        } else {
            // Si salle_id est absent, on crée une nouvelle salle
            return $this->createSalle($request);
        }
    }

    private function createSalle(Request $request)
    {
        $salle = Salle::create([
            'name' => $request->name,
            'capacite' => $request->capacite,
        ]);

        return response()->json(['message' => 'Salle créée avec succès', 'salle' => $salle], 201);
    }

    private function updateSalle(Salle $salle, Request $request)
    {
        $salle->update([
            'name' => $request->name,
            'capacite' => $request->capacite,
        ]);

        return response()->json(['message' => 'Salle mise à jour avec succès', 'salle' => $salle], 200);
    }


    public function destroy($id)
    {
        $salle = Salle::find($id);

        if ($salle) {
            $salle->delete();
            return response()->json(['success' => true, 'message' => 'Salle supprimée avec succès']);
        }

        return response()->json(['success' => false, 'message' => 'Salle non trouvée']);
    }
}
