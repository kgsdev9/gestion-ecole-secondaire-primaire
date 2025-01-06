<?php

namespace App\Http\Controllers\Vente;

use App\Http\Controllers\Controller;
use App\Models\ModeReglemnt;
use App\Models\TabRestaurant;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use App\Models\TProduct;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenteController extends Controller
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
        $listeventes = TFacture::with(['modereglement'])
            ->where('numvente', 'like', 'POS%')
            ->whereDate('created_at', Carbon::today())
            ->orderByDesc('created_at')
            ->get();

        $listemodereglement = ModeReglemnt::all();
        $listetablerestaurant = TabRestaurant::all();

        return view('ventes.index', compact('listeventes', 'listemodereglement', 'listetablerestaurant'));
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


    public function generateFactureVente($numvente)
    {
        // Récupérer la vente avec ses informations liées
        $vente = TFacture::with(['modereglement'])->where('numvente', $numvente)->firstOrFail();

        // Récupérer les lignes de facture
        $factureLigne = TfactureLigne::where('numvente', 'like', $vente->numvente)->get();

        // Créer une instance de FPDF avec un format de ticket (80 mm de largeur)
        $pdf = new FPDF('P', 'mm', [80, 200]); // Format 80x200 mm (ticket)
        $pdf->AddPage();

        // Encodage pour gérer les caractères spéciaux (UTF-8)
        $pdf->SetFont('Arial', 'B', 10);

        // Titre de l'entreprise (centré)
        $pdf->Cell(0, 5, utf8_decode('NOM DE L\'ENTREPRISE'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode('Adresse : Exemple 123'), 0, 1, 'C');
        $pdf->Cell(0, 5, utf8_decode('Tel : 0123456789'), 0, 1, 'C');
        $pdf->Ln(5);

        // Informations principales de la vente (centré)
        $pdf->Cell(0, 5, utf8_decode('Facture N°: ') . $vente->numvente, 0, 1, 'C');
        $pdf->Cell(0, 5, 'Date : ' . $vente->created_at->format('d/m/Y H:i'), 0, 1, 'C');

        // Afficher le mode de règlement
        $pdf->Ln(5);
        $pdf->Cell(0, 5, utf8_decode('Mode de règlement : ') . utf8_decode($vente->modereglement->libellemodereglement), 0, 1, 'C');
        $pdf->Ln(5);

        // En-têtes du tableau (décalé vers la gauche, sans bordure)
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 5, utf8_decode('Produit'), 0, 0, 'L');
        $pdf->SetX(40); // Définir la position X à 5 mm (ajustez selon vos besoins)
        $pdf->Cell(10, 5, utf8_decode('Qté'), 0, 0, 'L');

        $pdf->Cell(20, 5, 'Total', 0, 1, 'L');

        // Liste des produits (décalé, sans bordure)
        $pdf->SetFont('Arial', '', 8);
        foreach ($factureLigne as $item) {
            $pdf->SetX(10); // Définir la position X à 10 mm, vous pouvez ajuster cette valeur selon vos besoins
            $pdf->Cell(45, 5, utf8_decode($item->product->libelleproduct), 0, 0, 'L');
            $pdf->SetX(40); // Définir la position X avant d'afficher la quantité
            $pdf->Cell(10, 5, $item->quantite, 0, 0, 'L');
            $pdf->Cell(20, 5, number_format($item->montant_ttc, 2, ',', ' ') . '', 0, 1, 'L');
        }

        $pdf->Ln(5);

        // Total (décalé vers la gauche)
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(45, 5, utf8_decode('Total TTC'), 0, 0, 'L'); // Première cellule (45mm), alignée à gauche
        $pdf->SetX(30); // Définir la position X plus petite pour déplacer vers la gauche
        $pdf->Cell(20, 5, number_format($vente->montantttc, 2, ',', ' ') . ' FCFA', 0, 1, 'L');


        // Remerciement (centré)
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(0, 5, utf8_decode('Merci pour votre achat !'), 0, 'C');

        // Générer le PDF pour l'imprimante de caisse
        $pdf->Output('I', 'facture_' . $vente->numvente . '.pdf');
    }

    public function PrintAllVente(Request $request)
    {
        // Récupérer les critères de filtrage
        $modereglement = $request->input('modereglement');
        $table = $request->input('table');

        // Construire la requête de base
        $query = TFacture::with(['items.product.category'])
            ->where('numvente', 'like', 'POS%')
            ->whereDate('created_at', Carbon::today())
            ->orderByDesc('created_at');

        if (!empty($modereglement)) {
            $query->whereHas('modereglement', function ($q) use ($modereglement) {
                $q->where('id', $modereglement);
            });
        }

        if (!empty($table)) {
            $query->whereHas('table', function ($q) use ($table) {
                $q->where('id', $table);
            });
        }

        $listeventes = $query->get();

        // Regrouper par catégorie et produit
        $ventesParCategorie = [];
        foreach ($listeventes as $vente) {
            foreach ($vente->items as $ligne) {
                $categorie = $ligne->product->category->libellecategorieproduct ?? 'Sans Catégorie';
                $produit = $ligne->product->libelleproduct ?? 'Produit Inconnu';
                $prixvente = $ligne->product->prixvente ?? 0; // Récupérer le prix de vente

                if (!isset($ventesParCategorie[$categorie][$produit])) {
                    $ventesParCategorie[$categorie][$produit] = [
                        'quantite' => 0,
                        'montant_ttc' => 0,
                        'qtedisponible' => 0, // Nouvelle clé pour stocker la quantité disponible
                        'prixvente' => $prixvente, // Ajouter le prix de vente
                    ];
                }

                $ventesParCategorie[$categorie][$produit]['quantite'] += $ligne->quantite;
                $ventesParCategorie[$categorie][$produit]['montant_ttc'] += $ligne->montant_ttc;

                // On récupère la dernière ligne de facture basée sur 'created_at'
                // On met à jour la quantité disponible seulement si la ligne courante a une date plus récente
                $last_qte = $ligne->qtedisponible;  // Récupérer la quantité disponible de cette ligne
                $last_created_at = $ligne->created_at; // Récupérer la date de création

                // Si la ligne a une date plus récente, on garde cette quantité disponible
                if (!isset($ventesParCategorie[$categorie][$produit]['created_at']) ||
                    $ventesParCategorie[$categorie][$produit]['created_at'] < $last_created_at) {
                    $ventesParCategorie[$categorie][$produit]['qtedisponible'] = $last_qte;
                    $ventesParCategorie[$categorie][$produit]['created_at'] = $last_created_at;
                }
            }
        }

        // Générer le PDF
        $pdf = new FPDF();
        $pdf->AddPage('L', 'A4');
        $pdf->SetFont('Arial', 'B', 14);

        // Trait horizontal avant le titre
        $pdf->Line(10, 20, 200, 20);
        $pdf->Cell(0, 10, utf8_decode('ETAT DES VENTES (par catégorie et produit)'), 0, 1, 'C');
        // Trait horizontal après le titre
        $pdf->Line(10, 30, 200, 30);
        $pdf->Ln(5);

        $totalTTC = 0;

        // Ajouter les ventes par catégorie et produit
        foreach ($ventesParCategorie as $categorie => $produits) {
            // Titre de la catégorie
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode("Catégorie : $categorie"), 0, 1, 'L');
            $pdf->Ln(2);

            // En-têtes du tableau sans bordures verticales et horizontales
            $pdf->SetFillColor(169, 169, 169); // Couleur de fond des en-têtes (gris clair)
            $pdf->SetFont('Arial', 'B', 10); // Police en gras pour les en-têtes

            $headers = ['Produit', 'Prix Unitaire', 'Quantité Vendues', 'Quantité Restante', 'Montant TTC'];
            $widths = [60, 40, 40, 40, 40]; // Largeurs des colonnes

            // Ajouter les en-têtes sans bordures verticales
            foreach ($headers as $i => $header) {
                $pdf->Cell($widths[$i], 7, utf8_decode($header), 0, 0, 'C', true);
            }
            $pdf->Ln();

            // Données des produits
            $pdf->SetFont('Arial', '', 10);
            foreach ($produits as $produit => $data) {
                $totalTTC += $data['montant_ttc'];

                $pdf->Cell($widths[0], 7, utf8_decode($produit), 0, 0, 'C');
                $pdf->Cell($widths[1], 7, utf8_decode(number_format($data['prixvente'], 2)), 0, 0, 'C'); // Prix unitaire
                $pdf->Cell($widths[2], 7, utf8_decode($data['quantite']), 0, 0, 'C'); // Quantité commandée
                $pdf->Cell($widths[3], 7, utf8_decode($data['qtedisponible']), 0, 0, 'C'); // Quantité restante
                $pdf->Cell($widths[4], 7, utf8_decode(number_format($data['montant_ttc'], 2)), 0, 0, 'C');
                $pdf->Ln();
            }

            // Pas de trait horizontal ici, on enlève celui ajouté après la catégorie
            $pdf->Ln(5); // Ajouter un espace après chaque catégorie
        }

        // Total général sans trait horizontal après le total TTC
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 7, utf8_decode('Total TTC:'), 0, 0, 'R');
        $pdf->Cell(30, 7, utf8_decode(number_format($totalTTC, 2)), 0, 0, 'C');

        return response($pdf->Output('S', 'ventes_par_categorie.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="ventes_du_jour.pdf"');
    }

    public function validatVente(Request $request, $id)
    {

        $vente = TFacture::where('numvente', $id)->first();

        // Vérifier si la vente est déjà validée
        if ($vente->status === 'valide') {
            return response()->json([
                'success' => false,
                'message' => 'Cette vente est déjà validée.',
            ], 400);
        }

        // Mettre à jour le statut
        $vente->status = 'valide';
        $vente->save();

        return response()->json([
            'success' => true,
            'message' => 'Vente validée avec succès.',
        ]);
    }


    public function store(Request $request)
    {
        // Démarrer une transaction pour garantir l'intégrité des données
        \DB::beginTransaction();

        try {
            // Créer la facture principale
            $facture = TFacture::create([
                'numvente' => $this->generateIdentifier('POS', date('y')),
                'nom' => $request->input('nom') ?? 'MA BOUTIQUE',
                'montantht' => $request->input('totalttc'),
                'montantttc' => $request->input('totalttc'),
                'mode_reglement_id' => $request->input('modereglement_id'),
            ]);

            // Parcourir les articles vendus
            foreach ($request->input('items') as $ligne) {
                // Récupérer le produit correspondant
                $product = TProduct::findOrFail($ligne['id']);

                // Vérifier la disponibilité du stock
                if ($product->qtedisponible < $ligne['quantity']) {
                    throw new \Exception("Stock insuffisant pour le produit: {$product->libelleproduct}");
                }

                // Mettre à jour le stock global dans la table TProduct
                $product->qtedisponible -= $ligne['quantity'];
                $product->save();

                // Ajouter la ligne de facture et inclure la quantité restante après la vente
                TfactureLigne::create([
                    'numvente' => $facture->numvente,
                    'tproduct_id' => $ligne['id'],
                    'quantite' => $ligne['quantity'],
                    'qtedisponible' => $product->qtedisponible, // Quantité restante après la mise à jour
                    'prix_unitaire' => $ligne['prixvente'],
                    'remise' => $ligne['remise'] ?? 0,
                    'montant_ht' => $ligne['prixvente'] * $ligne['quantity'],
                    'montant_tva' => 0,
                    'montant_ttc' => $ligne['prixvente'] * $ligne['quantity'],
                ]);
            }

            // Valider la transaction
            \DB::commit();

            return response()->json(['message' => 'Vente créée avec succès !', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            \DB::rollBack();
            return response()->json(['error' => 'Échec de la création de vente.', 'details' => $e->getMessage()], 500);
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
