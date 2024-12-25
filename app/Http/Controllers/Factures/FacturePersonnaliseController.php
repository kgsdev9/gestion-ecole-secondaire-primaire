<?php

namespace App\Http\Controllers\Factures;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturePersonnaliseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listefactures  = TFacture::with('client')
            ->where('codefacture', 'like', 'FAP%')
            ->orderByDesc('created_at')
            ->get();
        return view('facturepersonnalites.index', compact('listefactures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listeclients = TClient::all();
        return view('facturepersonnalites.create', compact('listeclients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function generateIdentifier(string $type, string $year): string
    {

        $lastIdentifier = DB::table('t_factures')
            ->where('codefacture', 'like', "{$type}-{$year}-%")
            ->orderBy('codefacture', 'desc')
            ->value('codefacture');

        if ($lastIdentifier) {

            $lastNumber = (int) substr($lastIdentifier, strrpos($lastIdentifier, '-') + 1);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {

            $newNumber = str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        // Retourner le nouvel identifiant
        return "{$type}-{$year}-{$newNumber}";
    }
    public function store(Request $request)
    {
        $facture = TFacture::create([
            'codefacture' => $this->generateIdentifier('FAP', date('y')),
            'date_echance' => $request->input('echeance'),
            'client_id'  => $request->input('clientid'),
            'tvafacture'  => $request->input('tvainclus'),
            'montantht' => $request->input('total_ht'),
            'montanttva' => $request->input('total_tva'),
            'montantttc' => $request->input('total_ttc'),
        ]);

        foreach ($request->input('items') as $ligne) {
            TfactureLigne::create([
                'codefacture' => $facture->codefacture,
                'designation' => $ligne['name'] ?? '',
                'quantite' => $ligne['quantity'] ?? 0,
                'prix_unitaire' => $ligne['price'],
                'remise' => $ligne['remise'] ?? 0,
                'montant_ht' => $ligne['montantht'] ?? 0,
                'montant_tva' => $ligne['montanttva'] ?? 0,
                'montant_ttc' => $ligne['montanttc'] ?? 0,
            ]);
        }

        return response()->json(['message' => 'Facture créée avec succès !', 'facture' => $facture], 200);
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
    public function edit($facture)
    {
        $facture = TFacture::where('codefacture', $facture)->first();
        $facturelignes = TfactureLigne::where('codefacture', $facture->codefacture)->get();
        $listeclients = TClient::all();
        return view('facturepersonnalites.edit', compact('facture', 'facturelignes', 'listeclients'));
    }


    public function update(Request $request, $codefacture)
    {

        $facture = TFacture::where('codefacture', $codefacture)->first();
        $facture->update([
            'date_echance' => $request->input('echeanceDate'),
            'tvafacture' => $request->input('tvainclus') ?? 1,
            'created_at' => $request->input('created_at') ?? now(),
            'client_id' => $request->input('clientid'),
            'montantht' => $request->input('totalHT'),
            'montanttva' => $request->input('totalTVA'),
            'montantttc' => $request->input('totalTTC'),
        ]);

        // Supprimer les anciennes lignes de facture
        TFactureLigne::where('codefacture', $facture->codefacture)->delete();

        // Ajouter les nouvelles lignes de facture
        foreach ($request->input('items') as $ligne) {
            TFactureLigne::create([
                'codefacture' => $facture->codefacture,
                'designation' => $ligne['name'] ?? '',
                'quantite' => $ligne['quantity'] ?? 0,
                'prix_unitaire' => $ligne['price'],
                'remise' => $ligne['remise'] ?? 0,
                'montant_ht' => $ligne['montantht'] ?? 0,
                'montant_tva' => $ligne['montanttva'] ?? 0,
                'montant_ttc' => $ligne['montanttc'] ?? 0,
            ]);
        }

        return response()->json(['message' => 'Facture  mise à jour avec succès !'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($codefacture)
    {

        try {
            // Rechercher le produit par ID
            $vente = TFacture::where('codefacture', $codefacture)->first();

            // Supprimer le produit
            $vente->delete();

            return response()->json([
                'success' => true,
                'message' => 'facture supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'facture introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du facture.',
            ], 500);
        }
    }
}
