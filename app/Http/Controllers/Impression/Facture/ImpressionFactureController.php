<?php

namespace App\Http\Controllers\Impression\Facture;

use App\Http\Controllers\Controller;
use App\Models\TFacture;
use App\Models\TfactureLigne;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class ImpressionFactureController extends Controller
{
    public function generateFacture($codefacture)
    {
        if (str_starts_with($codefacture, 'VP')) {
            // Si le code de facture commence par 'VP'
            $facture = TFacture::where('numvente', 'like', $codefacture)->first();
            $factureLigne = TfactureLigne::where('numvente', 'like', $codefacture)->get();
        } elseif (str_starts_with($codefacture, 'AC')) {
            $facture = TFacture::where('codefacture', 'like', $codefacture)->first();
            $factureLigne = TfactureLigne::where('codefacture', 'like', $codefacture)->get();
        } elseif (str_starts_with($codefacture, 'FAP')) {
            $facture = TFacture::where('codefacture', 'like', $codefacture)->first();
            $factureLigne = TfactureLigne::where('codefacture', 'like', $codefacture)->get();
        } else {
        }



        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        // Logo
        // $logoPath = public_path('homes-assets/images/PrintHead_SONACO.png');
        // if (file_exists($logoPath))
        // {
        //     $logoWidth = 100;
        //     $pageWidth = 210;
        //     $xPosition = ($pageWidth - $logoWidth) / 2;
        //     $pdf->Image($logoPath, $xPosition, 8, $logoWidth);
        // }

        // Ajouter de l'espace entre le logo et le texte PROFORMA
        $pdf->SetXY(36, 28);  // Position du texte "PROFORMA" (augmenter Y pour de l'espace)
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(190, 10, 'FACTURE', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8);

        // Première section
        // Cellule "Numéro"
        $pdf->SetXY(5, 40); // Position initiale
        $pdf->Cell(20, 5, utf8_decode('N° Facture'), 1, 0, 'C');

        // Cellule "Date Facture"
        $pdf->Cell(25, 5, utf8_decode('Date Facture'), 1, 0, 'C');

        // Valeurs
        $pdf->SetFont('Arial', '', 8);


        // Valeur "Numéro"

        if (str_starts_with($codefacture, 'VP')) {
            $pdf->SetXY(5, 45); // Position ajustée pour la valeur du numéro
            $pdf->Cell(20, 5, $facture->numvente, 1, 0, 'C');
        } else if (str_starts_with($codefacture, 'AC')) {
            $pdf->SetXY(5, 45); // Position ajustée pour la valeur du numéro
            $pdf->Cell(20, 5, $facture->codefacture, 1, 0, 'C');
        } else if (str_starts_with($codefacture, 'FAP')) {
            $pdf->SetXY(5, 45); // Position ajustée pour la valeur du numéro
            $pdf->Cell(20, 5, $facture->codefacture, 1, 0, 'C');
        }


        // Valeur "Date Facture"
        $pdf->Cell(25, 5, date('d/m/Y', strtotime($facture->created_at)), 1, 0, 'C');

        // Bon de commande et date
        $pdf->SetFont('Arial', 'B', 8);



        // Bloc principal : Infos Client et Entreprise (remontée)
        $pdf->SetXY(60, 39); // Remontée encore plus haut
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(145, 38, '', 1, 1, 'C');

        $pdf->SetXY(62, 45); // Remontée
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 6, 'Email', 1, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(62, 51); // Remontée

        if (str_starts_with($codefacture, 'FAP')) {
            $pdf->Cell(50, 6, $facture->client->email, 1, 1, 'C');
        } else {
            $pdf->Cell(50, 6, $facture->email, 1, 1, 'C');
        }


        $pdf->SetXY(130, 42); // Remontée
        $pdf->SetFont('Arial', 'B', 15);

        if (str_starts_with($codefacture, 'FAP')) {
            $pdf->Cell(58, 6, $facture->client->libtiers, 0, 1, 'C');
        } else {
            $pdf->Cell(58, 6, $facture->nom . ' ' .  $facture->prenom, 0, 1, 'C');
        }

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(130, 63); // Remontée
        $pdf->MultiCell(58, 5, '', 0, 'C');


        // Téléphone et Fax sur la même ligne remonté à 64 px vers le bas
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(130, 64); // Position de départ pour les deux informations


        // $pdf->SetXY(140, 51); // Positionnez le curseur à 10 mm depuis le bord gauche
        // $pdf->Cell(48, 5, utf8_decode('Tél : '. $facture->client->Telephone), 0, 0, 'L');


        // Téléphone
        $pdf->SetXY(140, 60); // Positionnez le curseur à 10 mm depuis le bord gauche
        if (str_starts_with($codefacture, 'FAP')) {
            $pdf->Cell(48, 5, utf8_decode('Tél : ' . $facture->client->telephone ?? 'rien'), 0, 0, 'L');
        } else {
            $pdf->Cell(48, 5, utf8_decode('Tél : ' . $facture->telephone ?? 'rien'), 0, 0, 'L');
        }


        // Ajouter un espace de 10
        $pdf->Cell(10, 5, '', 0, 0, 'C');

        // Fax


        $pdf->SetXY(140, 66); // Positionnez le curseur à 10 mm depuis le bord gauche

        if (str_starts_with($codefacture, 'FAP')) {
            $pdf->Cell(48, 5, 'Fax : ' . $facture->client->fax, 0, 1, 'L');
        } else {
            $pdf->Cell(48, 5, 'Fax : ' . $facture->fax, 0, 1, 'L');
        }


        $margeHaute = 2;
        $pdf->SetXY(62, 56 + $margeHaute); // Marge ajoutée avant "N compte contribuable"
        $pdf->SetFont('Arial', 'B', 9);
        if (str_starts_with($codefacture, 'FAP')) {
            $pdf->Cell(50, 6, utf8_decode('Adresse : ' . $facture->client->adressepostale), 0, 1, 'C');
        } else {
            $pdf->Cell(50, 6, utf8_decode('Adresse : ' . $facture->adresse), 0, 1, 'C');
        }


        $pdf->SetLineWidth(0.1);
        $pdf->Rect(5, 79, 200, 110, "D"); // Bordure générale du tableau
        $pdf->Line(5, 90, 205, 90);

        // Définition des lignes verticales pour séparer les colonnes
        $pdf->Line(25, 79, 25, 189); // Séparation pour "Quantité"
        $pdf->Line(110, 79, 110, 189); // Séparation entre "Référence" et "Élément"

        if (str_starts_with($codefacture, 'FAP') ||  str_starts_with($codefacture, 'AC')) {
        } else {
            $pdf->Line(140, 79, 140, 189); // Séparation entre "Élément" et "PU HT"
        }

        $pdf->Line(165, 79, 165, 189); // Séparation entre "PU HT" et "Montant HT"

        // Utiliser une taille de police plus petite pour l'en-tête
        $pdf->SetFont('Arial', 'B', 7);

        // Définition des en-têtes des colonnes
        $pdf->SetXY(5, 80);
        $pdf->Cell(20, 8, utf8_decode("Quantité"), 0, 0, 'C'); // Colonne Quantité (réduite à 20)
        $pdf->SetXY(43, 80);
        $pdf->Cell(45, 8, utf8_decode("Désignation"), 0, 0, 'C'); // Colonne Référence (45)
        $pdf->SetXY(90, 80);
        if (str_starts_with($codefacture, 'FAP') ||  str_starts_with($codefacture, 'AC')) {
        } else {
            $pdf->Cell(70, 8, utf8_decode("Catégorie"), 0, 0, 'C'); // Colonne Élément (élargie à 70)
        }

        $pdf->SetXY(140, 80);
        $pdf->Cell(25, 8, "Prix Unit. HT (CFA)", 0, 0, 'C'); // Colonne PU HT (réduite à 25)
        $pdf->SetXY(165, 80);
        $pdf->Cell(40, 8, "Montant HT (CFA)", 0, 0, 'C'); // Colonne Montant HT (réduite à 40)

        $yPosition = 95;

        if (str_starts_with($codefacture, 'VP')) {
            foreach ($factureLigne as $detail) {
                // Quantité
                $pdf->SetXY(5, $yPosition);
                $pdf->Cell(20, 8, number_format($detail->quantite, 0, '.', ' '), 0, 0, 'R');

                // Référence
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(70, 8, utf8_decode($detail->product->libelleproduct), 0, 0, 'L');

                // Élément
                $pdf->SetXY(110, $yPosition);

                $pdf->Cell(70, 8, utf8_decode($detail->product->category->libellecategorieproduct), 0, 0, 'L');

                // Prix Unitaire
                $pdf->SetXY(149, $yPosition);
                $pdf->Cell(25, 8, number_format($detail->prix_unitaire, 0, '.', ' '), 0, 0, 'C');

                // Montant HT
                $pdf->SetXY(180, $yPosition);
                $pdf->Cell(40, 8, number_format($detail->montant_ttc, 0, '.', ' '), 0, 0, 'C');

                // Ajouter une ligne supplémentaire en dessous pour plus de détails (par exemple, RefSONACO)
                $yPosition += 4;
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(85, 8, utf8_decode($detail->RefSONACO), 0, 0, 'L');

                // Espacement pour la prochaine ligne principale
                $yPosition += 6;
            }
        } elseif (str_starts_with($codefacture, 'AC')) {

            foreach ($factureLigne as $detail) {
                // Quantité
                $pdf->SetXY(5, $yPosition);
                $pdf->Cell(20, 8, number_format($detail->quantite, 0, '.', ' '), 0, 0, 'R');

                // Référence
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(70, 8, utf8_decode($detail->designation), 0, 0, 'L');

                // Élément
                $pdf->SetXY(110, $yPosition);

                $pdf->Cell(70, 8, utf8_decode(''), 0, 0, 'L');

                // Prix Unitaire
                $pdf->SetXY(149, $yPosition);
                $pdf->Cell(25, 8, number_format($detail->prix_unitaire, 0, '.', ' '), 0, 0, 'C');

                // Montant HT
                $pdf->SetXY(180, $yPosition);
                $pdf->Cell(40, 8, number_format($detail->montant_ttc, 0, '.', ' '), 0, 0, 'C');

                // Ajouter une ligne supplémentaire en dessous pour plus de détails (par exemple, RefSONACO)
                $yPosition += 4;
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(85, 8, utf8_decode($detail->RefSONACO), 0, 0, 'L');

                // Espacement pour la prochaine ligne principale
                $yPosition += 6;
            }
        } elseif (str_starts_with($codefacture, 'FAP')) {

            foreach ($factureLigne as $detail) {
                // Quantité
                $pdf->SetXY(5, $yPosition);
                $pdf->Cell(20, 8, number_format($detail->quantite, 0, '.', ' '), 0, 0, 'R');

                // Référence
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(70, 8, utf8_decode($detail->designation), 0, 0, 'L');

                // Élément
                $pdf->SetXY(110, $yPosition);

                $pdf->Cell(70, 8, utf8_decode(''), 0, 0, 'L');

                // Prix Unitaire
                $pdf->SetXY(149, $yPosition);
                $pdf->Cell(25, 8, number_format($detail->prix_unitaire, 0, '.', ' '), 0, 0, 'C');

                // Montant HT
                $pdf->SetXY(180, $yPosition);
                $pdf->Cell(40, 8, number_format($detail->montant_ht, 0, '.', ' '), 0, 0, 'C');

                // Ajouter une ligne supplémentaire en dessous pour plus de détails (par exemple, RefSONACO)
                $yPosition += 4;
                $pdf->SetXY(25, $yPosition);
                $pdf->Cell(85, 8, utf8_decode($detail->RefSONACO), 0, 0, 'L');

                // Espacement pour la prochaine ligne principale
                $yPosition += 6;
            }
        } else {
        }




        // Pied de tableau - Première section
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(5, 189); // Position initiale
        $pdf->Cell(20, 6, "Base", 1, 0, 'C');  // Largeur réduite à 20
        $pdf->Cell(20, 6, "Taux", 1, 0, 'C');  // Largeur réduite à 20
        $pdf->Cell(20, 6, "TVA", 1, 0, 'C');   // Largeur réduite à 20

        // Ajout de l'espace de 5 unités
        $pdf->SetX($pdf->GetX() + 5); // Décalage horizontal de 5

        $pdf->Ln(); // Nouvelle ligne
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(5, 195); // Position pour les valeurs
        $pdf->Cell(20, 6, number_format($facture->montantht, 0, '.', ' '), 1, 0, 'C');  // Première colonne
        $pdf->Cell(20, 6, "18%", 1, 0, 'C');  // Deuxième colonne
        $pdf->Cell(20, 6, number_format($facture->montanttva, 0, '.', ' '), 1, 0, 'C'); // Troisième colonne



        //pieds du tableau à droite

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(140, 189); // Position initiale
        $pdf->Cell(30, 6, "TOTAL HT", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($facture->montantht, 0, '.', ' '), 1, 1, 'R');


        $pdf->SetXY(140, 195); // Remontée
        $pdf->Cell(30, 6, "TVA", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($facture->montanttva, 0, '.', ' '), 1, 1, 'R');

        $pdf->SetXY(140, 201); // Remontée
        $pdf->Cell(30, 6, "NET A PAYER", 1, 0, 'C');
        // Cellule bordure avec texte aligné à droite
        $pdf->Cell(35, 6, number_format($facture->montantttc, 0, '.', ' '), 1, 1, 'R');


        // exemplaire comptabilité
        $pdf->SetXY(160, 244); // Décalage vers le bas
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 5, "FACTURE DE COMPTABILITE", 0, 0, 'R');

        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');
        // Sortie du PDF
        $pdf->Output('I', 'facture.pdf');
        exit;



        $fileName = 'Facture' . '.pdf';
        $filePath = storage_path('app/public/proformas/' . $fileName);

        if (!file_exists(storage_path('app/public/proformas'))) {
            mkdir(storage_path('app/public/proformas'), 0777, true);
        }

        $pdf->Output('F', $filePath);

        $fileUrl = asset('storage/proformas/' . $fileName);


        return response()->json([
            'success' => true,
            'message' => 'PDF généré avec succès.',
            'file_url' => $fileUrl, // URL pour accéder au fichier
        ]);
    }




    public function generateListeVenteGroupeParMoisSanstotal()
    {
        // Récupérer les données de la table TfACTURE
        $factures = TFacture::where('numvente', 'like', 'vp%')->get();

        // Initialisation de FPDF
        $pdf = new FPDF();
        $pdf->AddPage('L', 'A4'); // Paysage (Landscape)
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre

        // En-tête principal
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(0, 10, utf8_decode('ETAT DES VENTES'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('(Commandes)'), 0, 1, 'C');

        // Trait horizontal gris juste après le titre
        $pdf->Ln(3);
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre
        $pdf->Ln(5); // Ajoute un petit espace après le trait

        // Informations générales sur une seule ligne
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Première ligne
        $pdf->Cell(95, 0, utf8_decode('De CC24/02506 à CC24/02506'), 0, 1, 'L');

        $pdf->Ln(5); // Ajoute un petit espace après la ligne

        // Seconde ligne
        $pdf->Cell(95, 6, utf8_decode('De US BC RD ISSI BIO F2 G. à US BC RD ISSI BIO F2 G.'), 0, 1, 'L');

        // Ajouter un trait horizontal gris juste après
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Ligne horizontale

        $pdf->Ln(5); // Ajout d'un espace après la ligne horizontale

        // En-tête du tableau (fond gris)
        $pdf->SetFont('Arial', '', 10); // Non gras
        $pdf->SetFillColor(192, 192, 192);  // Gris
        $headers = [
            'Num Vente',
            'Client',
            'Telephone',
            'Montantht',
            'Montanttva',
            'Montantttc',
            'Created_at',
        ];

        // Largeurs des colonnes ajustées
        $widths = [27, 40, 20, 60, 50, 60, 20];

        // Affichage des en-têtes
        foreach ($headers as $i => $header) {
            $pdf->Cell($widths[$i], 7, utf8_decode($header), 1, 0, 'C', true);
        }

        $pdf->Ln(8); // Ajoute un petit espace après l'en-tête

        // Corps du tableau avec les données dynamiques
        $pdf->SetFont('Arial', '', 10);

        // Grouper les factures par mois
        $facturesGroupedByMonth = $factures->groupBy(function ($facture) {
            return $facture->created_at->format('m-Y'); // Groupement par mois et année
        });

        foreach ($facturesGroupedByMonth as $month => $monthlyFactures) {
            // Ajout du titre "Botuique Officielle : PARIS STORE" pour chaque mois
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, utf8_decode('Botuique Officielle : PARIS STORE '), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode('Par mois : ' . $month), 0, 1, 'L'); // Affiche le mois et l'année

            // Remplissage des données dynamiques dans le tableau pour ce mois
            foreach ($monthlyFactures as $facture) {
                $pdf->Cell($widths[0], 7, utf8_decode($facture->numvente), 1, 0, 'C');
                $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->libelleclient, 12)), 1, 0, 'C');
                $pdf->Cell($widths[2], 7, utf8_decode($facture->telephone), 1, 0, 'C');
                $pdf->Cell($widths[3], 7, utf8_decode((string)$facture->montantht), 1, 0, 'C');
                $pdf->Cell($widths[4], 7, utf8_decode((string)$facture->montanttva), 1, 0, 'C');
                $pdf->Cell($widths[5], 7, utf8_decode((string)$facture->montantttc), 1, 0, 'C');
                $pdf->Cell($widths[6], 7, utf8_decode($facture->created_at->format('d/m/Y')), 1, 0, 'C');  // Format de la date
                $pdf->Ln(); // Nouvelle ligne après chaque facture
            }
        }

        // Trait horizontal juste après la note
        $pdf->Ln(2); // Ajoute un petit espace après le tableau

        // Retourner le PDF pour le téléchargement
        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');

        // Sortie du PDF
        // $pdf->Output('I', 'facture.pdf');
        // exit;
    }

    public function generateRapport(Request $request)
    {
        $datevente = $request->input('datevente');
        $searchQuery = $request->input('search');
        $modules = $request->input('modules');

        $facturesQuery = "";

        if ($modules == "ventes") {
            $facturesQuery = TFacture::where('numvente', 'like', 'vp%');

            if (!empty($searchQuery)) {
                $facturesQuery->where('numvente', 'like', "%{$searchQuery}%");
            }

            if (!empty($datevente)) {

                $facturesQuery->whereDate('created_at', '=', $datevente);
            }
        } elseif ($modules == "fapersonalise") {

            $facturesQuery = TFacture::where('codefacture', 'like', 'fap%');

            if (!empty($searchQuery)) {
                $facturesQuery->where('codefacture', 'like', "%{$searchQuery}%");
            }

            if (!empty($datevente)) {

                $facturesQuery->whereDate('codefacture', '=', $datevente);
            }
        } elseif ($modules == "facturelibre") {

            $facturesQuery = TFacture::where('codefacture', 'like', 'ac%');

            if (!empty($searchQuery)) {
                $facturesQuery->where('codefacture', 'like', "%{$searchQuery}%");
            }

            if (!empty($datevente)) {

                $facturesQuery->whereDate('codefacture', '=', $datevente);
            }
        }



        // Récupération des factures
        $factures = $facturesQuery->get();

        // Initialisation de FPDF
        $pdf = new FPDF();
        $pdf->AddPage('L', 'A4'); // Paysage (Landscape)
        $pdf->SetFont('Arial', 'B', 14);

        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre

        // En-tête principal
        $pdf->SetTextColor(0, 0, 255);

        if ($modules == "ventes") {
            $pdf->Cell(0, 10, utf8_decode('ETAT DES VENTES'), 0, 1, 'C');
        } elseif ($modules == "fapersonalise") {
            $pdf->Cell(0, 10, utf8_decode('ETAT DES FACTURES PERSONNALISEES'), 0, 1, 'C');
        } else {
            $pdf->Cell(0, 10, utf8_decode('ETAT DES FACTURES'), 0, 1, 'C');
        }

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('(Liste des ventes génerées)'), 0, 1, 'C');

        // Trait horizontal gris juste après le titre
        $pdf->Ln(3);
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre
        $pdf->Ln(5); // Ajoute un petit espace après le trait

        // Informations générales sur une seule ligne
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        // Première ligne
        $pdf->Cell(95, 0, utf8_decode('Critere der recherche par date  ' . $datevente .  ' par entrée ' . $searchQuery), 0, 1, 'L');
        $pdf->Ln(5); // Ajoute un petit espace après la ligne

        // Seconde ligne
        $pdf->Cell(95, 6, utf8_decode(number_format($factures->sum('montantttc'), '0', '.', ' ')) . ' ' . 'FCFA', 0, 1, 'L');

        // Ajouter un trait horizontal gris juste après
        $pdf->SetDrawColor(192, 192, 192); // Couleur grise
        $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Ligne horizontale

        $pdf->Ln(5); // Ajout d'un espace après la ligne horizowntale

        // En-tête du tableau (fond gris)
        $pdf->SetFont('Arial', '', 10); // Non gras
        $pdf->SetFillColor(192, 192, 192);  // Gris
        $headers = [
            'Num Vente',
            'Client',
            'Telephone',
            'Montantht',
            'Montanttva',
            'Montantttc',
            'Created_at',
        ];

        // Largeurs des colonnes ajustées
        $widths = [27, 40, 20, 60, 50, 60, 20];

        // Affichage des en-têtes
        foreach ($headers as $i => $header) {
            $pdf->Cell($widths[$i], 7, utf8_decode($header), 1, 0, 'C', true);
        }

        $pdf->Ln(8); // Ajoute un petit espace après l'en-tête

        // Corps du tableau avec les données dynamiques
        $pdf->SetFont('Arial', '', 10);

        // Grouper les factures par mois
        $facturesGroupedByMonth = $factures->groupBy(function ($facture) {
            return $facture->created_at->format('m-Y'); // Groupement par mois et année
        });

        foreach ($facturesGroupedByMonth as $month => $monthlyFactures) {
            // Ajout du titre "Botuique Officielle : PARIS STORE" pour chaque mois
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, utf8_decode('Boutique Officielle : PARIS STORE '), 0, 1, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode('Par mois : ' . $month), 0, 1, 'L'); // Affiche le mois et l'année

            // Initialiser la variable de somme pour chaque mois
            $totalTtcForMonth = 0;

            // Remplissage des données dynamiques dans le tableau pour ce mois
            foreach ($monthlyFactures as $facture) {


                if ($modules == "ventes") {
                    $pdf->Cell($widths[0], 7, utf8_decode($facture->numvente), 1, 0, 'C');
                } elseif ($modules == "fapersonalise" || $modules == "facturelibre"  ) {
                    $pdf->Cell($widths[0], 7, utf8_decode($facture->codefacture), 1, 0, 'C');
                }


                if ($modules == "ventes" ||  $modules == "facturelibre") {
                    $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->nom, 12)), 1, 0, 'C');
                } else {
                    $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->client->libtiers, 12)), 1, 0, 'C');
                }

                if ($modules == "ventes" ||  $modules == "facturelibre") {
                    $pdf->Cell($widths[2], 7, utf8_decode($facture->telephone), 1, 0, 'C');
                } else {
                    $pdf->Cell($widths[2], 7, utf8_decode($facture->client->telephone), 1, 0, 'C');
                }


                $pdf->Cell($widths[3], 7, utf8_decode(number_format($facture->montantht, '0', '.', ' ') . ' FCA'), 1, 0, 'C');
                $pdf->Cell($widths[4], 7, utf8_decode(number_format($facture->montanttva, '0', '.', ' ') . ' FCA'), 1, 0, 'C');
                $pdf->Cell($widths[5], 7, utf8_decode(number_format($facture->montantttc, '0', '.', ' ') . ' FCA'), 1, 0, 'C');
                $pdf->Cell($widths[6], 7, utf8_decode($facture->created_at->format('d/m/Y')), 1, 0, 'C');  // Format de la date
                $pdf->Ln(); // Nouvelle ligne après chaque facture

                // Ajouter au total du mois
                $totalTtcForMonth += $facture->montantttc;
            }

            $pdf->SetFont('Arial', 'B', 10);

            // Définir une largeur spécifique pour la cellule (ex. 80 mm ou 100)
            $cellWidth = 70;  // Vous pouvez ajuster cette valeur pour réduire ou agrandir la bordure
            // Déplacer le curseur X vers la droite avant de créer la cellule
            $pdf->SetX(217);  // Ajustez cette valeur en fonction de la position souhaitée (par exemple, 150 mm ou 160 mm)

            // Créer la cellule avec la bordure à une longueur réduite
            $pdf->Cell($cellWidth, 6, utf8_decode('Total' . $month . ' : ' . number_format($totalTtcForMonth, 0, '.', ' ') . ' FCFA'), 1, 1, 'R');
            // Ajouter un petit espace après le total
            $pdf->Ln(5);
        }

        // Trait horizontal juste après la note
        $pdf->Ln(2); // Ajoute un petit espace après le tableau

        // Retourner le PDF pour le téléchargement
        return response($pdf->Output('S', 'facture.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="facture.pdf"');
    }
}
