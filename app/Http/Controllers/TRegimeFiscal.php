use FPDF;
use Carbon\Carbon;

public function generateRapport(Request $request)
{
// Récupérer les critères envoyés par Alpine.js
$timeframe = $request->input('timeframe'); // 'day', 'month', 'week'
$startDate = $request->input('start_date'); // Date début
$endDate = $request->input('end_date'); // Date fin

// Construire la requête de base
$query = TFacture::query()
->where('numvente', 'like', 'vp%'); // Filtrer uniquement les ventes qui commencent par "vp"

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
    $factures = $query->orderByDesc('created_at')->get();

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
    $pdf->Cell(0, 6, utf8_decode('(Liste des ventes générées)'), 0, 1, 'C');

    // Trait horizontal gris juste après le titre
    $pdf->Ln(3);
    $pdf->SetDrawColor(192, 192, 192); // Couleur grise
    $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Trait horizontal sous le titre
    $pdf->Ln(5); // Ajoute un petit espace après le trait

    // Informations générales sur une seule ligne
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0, 0, 0);

    // Première ligne avec critères de recherche
    $pdf->Cell(95, 6, utf8_decode('Critère de recherche par date : ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')), 0, 1, 'L');
    $pdf->Ln(5); // Ajoute un petit espace après la ligne

    // Seconde ligne : Montant total
    $pdf->Cell(95, 6, utf8_decode('Montant total : ' . number_format($factures->sum('montantttc'), 0, '.', ' ') . ' FCFA'), 0, 1, 'L');

    // Ajouter un trait horizontal gris juste après
    $pdf->SetDrawColor(192, 192, 192); // Couleur grise
    $pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY()); // Ligne horizontale

    $pdf->Ln(5); // Ajout d'un espace après la ligne horizontale

    // En-tête du tableau (fond gris)
    $pdf->SetFont('Arial', '', 10); // Non gras
    $pdf->SetFillColor(192, 192, 192); // Gris
    $headers = [
    'Num Vente',
    'Client',
    'Téléphone',
    'Montant HT',
    'Montant TVA',
    'Montant TTC',
    'Date de Vente',
    ];

    // Largeurs des colonnes ajustées
    $widths = [27, 40, 30, 50, 40, 50, 40];

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
    // Ajout du titre pour chaque mois
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, utf8_decode('Boutique Officielle : PARIS STORE '), 0, 1, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, utf8_decode('Par mois : ' . $month), 0, 1, 'L'); // Affiche le mois et l'année

    // Initialiser la variable de somme pour chaque mois
    $totalTtcForMonth = 0;

    // Remplissage des données dynamiques dans le tableau pour ce mois
    foreach ($monthlyFactures as $facture) {
    $pdf->Cell($widths[0], 7, utf8_decode($facture->numvente), 1, 0, 'C');
    $pdf->Cell($widths[1], 7, utf8_decode(\Str::limit($facture->nom, 12)), 1, 0, 'C');
    $pdf->Cell($widths[2], 7, utf8_decode($facture->telephone), 1, 0, 'C');

    $pdf->Cell($widths[3], 7, utf8_decode(number_format($facture->montantht, 0, '.', ' ') . ' FCA'), 1, 0, 'C');
    $pdf->Cell($widths[4], 7, utf8_decode(number_format($facture->montanttva, 0, '.', ' ') . ' FCA'), 1, 0, 'C');
    $pdf->Cell($widths[5], 7, utf8_decode(number_format($facture->montantttc, 0, '.', ' ') . ' FCA'), 1, 0, 'C');
    $pdf->Cell($widths[6], 7, utf8_decode($facture->created_at->format('d/m/Y')), 1, 0, 'C'); // Format de la date
    $pdf->Ln(); // Nouvelle ligne après chaque facture

    // Ajouter au total du mois
    $totalTtcForMonth += $facture->montantttc;
    }

    $pdf->SetFont('Arial', 'B', 10);

    // Afficher le total du mois
    $cellWidth = 70; // Ajustez cette valeur pour réduire ou agrandir la bordure
    $pdf->SetX(217); // Ajustez cette valeur en fonction de la position souhaitée
    $pdf->Cell($cellWidth, 6, utf8_decode('Total ' . $month . ' : ' . number_format($totalTtcForMonth, 0, '.', ' ') . ' FCFA'), 1, 1, 'R');
    $pdf->Ln(5);
    }

    // Retourner le PDF pour le téléchargement
    return response($pdf->Output('S', 'rapport_ventes.pdf'), 200)
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="rapport_ventes.pdf"');
    }
