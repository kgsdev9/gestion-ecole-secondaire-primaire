<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\TClient;
use App\Models\TCodeDevise;
use App\Models\TRegimeFiscal;
use Illuminate\Http\Request;

class ClientController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listeclients = TClient::orderByDesc('created_at')->get();
        $listecodedevises = TCodeDevise::all();
        $listeregimesfiscaux  = TRegimeFiscal::all();
        return view('clients.index', compact('listeclients', 'listecodedevises', 'listeregimesfiscaux'));
    }


    public function store(Request $request)
    {

        // Vérifier si product_id existe dans la requête
        $clientId = $request->input('client_id');

        if ($clientId) {
            // Si product_id existe, on modifie le produit
            $client = TClient::find($clientId);

            // Si le produit n'existe pas, le créer
            if (!$client) {
                // Créer un nouveau client
                return $this->createClient($request);
            }

            // Si le produit existe, procéder à la mise à jour
            return $this->updateClient($client, $request);
        } else {
            // Si client_id est absent, on crée un nouveau produit
            return $this->createClient($request);
        }
    }


    private function updateClient($client, Request $request)
    {
        $data = [
            'libtiers' => $request->libtiers,
            'adressepostale' => $request->adressepostale,
            'adressegeo' => $request->adressegeo,
            'fax' => $request->fax,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'numerocomtribuabe' => $request->numerocomtribuabe,
            'numerodecompte' => $request->numerodecompte,
            'capital' => $request->capital,
            'tregimefiscal_id' => $request->tregimefiscal_id,
            'tcodedevise_id' => $request->tcodedevise_id,
        ];

        $client->update($data);
        // $user->load('codedevise');
        return response()->json(['message' => 'Client mis à jour avec succès', 'client' => $client], 200);
    }



    // Générer un code client unique
    public function generateClientCode()
    {
        // Définir le préfixe (par exemple, CI- ou CR-)
        $prefix = 'CI-';

        // Générer une partie du code (par exemple, un numéro aléatoire ou une séquence)
        // Ici, on génère un code numérique aléatoire de 4 chiffres suivi de 4 autres chiffres
        $randomPart = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Vérifier que le code généré est unique dans la base de données
        $existingCode = TClient::where('codeclient', $prefix . $randomPart)->first();

        // Si le code existe déjà, rappeler la fonction pour en générer un autre
        if ($existingCode) {
            return $this->generateClientCode();
        }

        // Retourner le code complet unique
        return $prefix . $randomPart;
    }


    private function createClient(Request $request)
    {
        $email = $request->email;

        // Vérifier si l'email existe déjà
        if ($email && TClient::where('email', $email)->exists()) {
            $email = $this->generateUniqueEmail($email);
        }

        $client = TClient::create([
            'codeclient' => $this->generateClientCode(),
            'libtiers' => $request->libtiers,
            'adressepostale' => $request->adressepostale,
            'adressegeo' => $request->adressegeo,
            'fax' => $request->fax,
            'email' => $email,
            'telephone' => $request->telephone,
            'numerocomtribuabe' => $request->numerocomtribuabe,
            'numerodecompte' => $request->numerodecompte,
            'capital' => $request->capital,
            'tregimefiscal_id' => $request->tregimefiscal_id,
            'tcodedevise_id' => $request->tcodedevise_id,
        ]);

        return response()->json(['message' => 'Client créé avec succès', 'client' => $client], 201);
    }

    /**
     * Générer un email unique en ajoutant un suffixe incrémental.
     */
    private function generateUniqueEmail($email)
    {
        $originalEmail = $email;
        $i = 1;

        // Découper l'email en partie locale et domaine
        [$localPart, $domain] = explode('@', $email);

        // Boucler jusqu'à trouver un email qui n'existe pas
        while (TClient::where('email', $email)->exists()) {
            $email = "{$localPart}_{$i}@{$domain}";
            $i++;
        }

        return $email;
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
            $client = TClient::findOrFail($id);

            // Supprimer le produit
            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du Client.',
            ], 500);
        }
    }
}
