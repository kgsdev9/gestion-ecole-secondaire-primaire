<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\ResultatExamen;
use App\Models\ResultatExamenLigne;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
class PrintExamenController extends Controller
{



    public function printresultatExam($code)
    {
        // 1. Récupérer les données
        $resultat = ResultatExamen::where('code', $code)->firstOrFail();
        $lignes = ResultatExamenLigne::where('resultat_examen_id', $resultat->id)->get();

        // 2. Créer le PDF
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode("Résultat Examen - Code : {$resultat->code}"), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 10, utf8_decode("Examen {$resultat->examen->name} | Année: {$resultat->anneeAcademique->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Taux de réussite: {$resultat->taux_reussite}% | Moyenne: {$resultat->moyenne_examen}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Admis: {$resultat->nb_admis} / Participants: {$resultat->nb_total_participant}"), 0, 1);
        $pdf->Ln(5);


        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 8, utf8_decode('Élève'), 1);
        $pdf->Cell(30, 8, 'Points', 1);
        $pdf->Cell(30, 8, 'Moyenne', 1);
        $pdf->Cell(20, 8, 'Admis', 1);
        $pdf->Cell(30, 8, 'Mention', 1);
        $pdf->Cell(20, 8, 'Rang', 1);
        $pdf->Ln();

        // Lignes des résultats
        $pdf->SetFont('Arial', '', 10);
        foreach ($lignes as $ligne) {
            $pdf->Cell(60, 8, $ligne->eleve->nom, 1);
            $pdf->Cell(30, 8, $ligne->nombre_total_points, 1);
            $pdf->Cell(30, 8, $ligne->moyenne, 1);
            $pdf->Cell(20, 8, $ligne->admis ? 'Oui' : 'Non', 1);
            $pdf->Cell(30, 8, $ligne->mention, 1);
            $pdf->Cell(20, 8, $ligne->rang, 1);
            $pdf->Ln();
        }


        // 3. Sortie du PDF
        $pdf->Output('D', "Resultat_{$resultat->code}.pdf");
    }


}
