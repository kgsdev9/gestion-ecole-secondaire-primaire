<?php

namespace App\Http\Controllers\Vente;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use App\Models\TProduct;
use App\Models\TventeDirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $listeventes = TFacture::query()
            ->where('numvente', 'like', 'vp%')
            ->orderByDesc('created_at')->get();

        return view('ventes.index', compact('listeventes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listeproduct =  TProduct::all();

        return view('ventes.create', compact('listeproduct'));
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
            ->where('numvente', 'like', "{$type}-{$year}-%")
            ->orderBy('numvente', 'desc')
            ->value('numvente');

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
        // Début de la transaction pour garantir la consistance des données
        \DB::beginTransaction();

        $tvainclus = null;
        if ($request->input('tvaincluse')) {
            $tvainclus = 1;
        } else {
            $tvainclus = 0;
        }

        try {
            // Créer une nouvelle facture
            $facture = TFacture::create([
                'numvente' => $this->generateIdentifier('VP', date('y')),
                'adresse' => $request->input('adresse'),
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'montantht' => $request->input('total_ht'),
                'montanttva' => $request->input('total_tva'),
                'tvafacture' => $tvainclus,
                'montantttc' => $request->input('total_ttc'),
                'telephone' => $request->input('telephone'),
            ]);

            // Boucle pour enregistrer les lignes de facture et mettre à jour le stock
            foreach ($request->input('items') as $ligne) {
                // Créer la ligne de facture
                TfactureLigne::create([
                    'numvente' => $facture->numvente,
                    'tproduct_id' => $ligne['product_id'],
                    'quantite' => $ligne['quantity'],
                    'prix_unitaire' => $ligne['price'],
                    'remise' => $ligne['remise'] ?? 0,
                    'montant_ht' => $ligne['montantht'],
                    'montant_tva' => $ligne['montanttva'],
                    'montant_ttc' => $ligne['montanttc'],
                ]);

                // Mettre à jour la quantité disponible du produit
                $product = TProduct::findOrFail($ligne['product_id']);
                $product->qtedisponible -= $ligne['quantity']; // Soustraction de la quantité commandée
                $product->save(); // Sauvegarder la mise à jour
            }


            // Valider la transaction
            \DB::commit();

            return response()->json(['message' => 'vente créée avec succès !', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            \DB::rollBack();
            return response()->json(['error' => 'Échec de la création de vente.'], 500);
        }
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
    public function edit($vente)
    {
        $ventes = TFacture::where('numvente', $vente)->first();
        $venteslignes = TfactureLigne::with(['product' => function ($query) {

            $query->select('id', 'qtedisponible');
        }])
            ->where('numvente', $ventes->numvente)
            ->get();


        $listeproduct =  TProduct::all();

        return view('ventes.edit', [
            'ventes' => $ventes,
            'venteslignes' => $venteslignes,
            'listeproduct' => $listeproduct
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $numvente)
    {

        $facture = TFacture::where('numvente', $numvente)->first();

        // Remettre en stock les quantités des anciennes lignes
        $oldLines = TFactureLigne::where('numvente', $facture->numvente)->get();
        foreach ($oldLines as $oldLine) {
            $product = TProduct::findOrFail($oldLine->tproduct_id);
            $product->qtedisponible += $oldLine->quantite; // Ajouter l'ancienne quantité au stock
            $product->save();
        }

        // Supprimer les anciennes lignes
        TFactureLigne::where('numvente', $facture->numvente)->delete();


        // dd($request->input('totalHT'));
        // Mettre à jour les informations principales de la facture
        $facture->update([
            'adresse' => $request->input('adresse'),
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'montantht' => $request->input('totalHT'),
            'montanttva' => $request->input('totalTVA'),
            'tvafacture' => $request->input('isTVAIncluded'),
            'montantttc' => $request->input('totalTTC'),
            'telephone' => $request->input('telephone'),
        ]);

        // Boucle pour enregistrer les nouvelles lignes et mettre à jour le stock
        foreach ($request->input('items') as $ligne) {
            // Créer la nouvelle ligne
            TFactureLigne::create([
                'numvente' => $facture->numvente,
                'tproduct_id' => $ligne['tproduct_id'],
                'quantite' => $ligne['quantity'],
                'prix_unitaire' => $ligne['price'],
                'remise' => $ligne['remise'] ?? 0,
                'montant_ht' => $ligne['montantht'],
                'montant_tva' => $ligne['montanttva'],
                'montant_ttc' => $ligne['montanttc'],
            ]);

            // Mettre à jour la quantité disponible du produit
            $product = TProduct::findOrFail($ligne['tproduct_id']);
            $product->qtedisponible -= $ligne['quantity']; // Déduire la nouvelle quantité
            $product->save();
        }

        return response()->json(['message' => 'Facture modifiée avec succès !', 'facture' => $facture], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            // Rechercher le produit par ID
            $vente = TFacture::where('id', $id)->first();

            // Supprimer le produit
            $vente->delete();

            return response()->json([
                'success' => true,
                'message' => 'vente supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'vente introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du vente.',
            ], 500);
        }
    }
}
