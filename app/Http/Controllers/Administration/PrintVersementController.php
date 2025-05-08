<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Versement;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
class PrintVersementController extends Controller
{
    /**
     * imprimer la liste des versements.
     *
     * @return \Illuminate\Http\Response
     */


    public function printVersement(Request $request)
    {
        $eleve = Eleve::findOrFail($request->eleve_id);
        $versements = Versement::where('eleve_id', $request->eleve_id)->get();



        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode("Relevé des versements - Élève : " . $eleve->nom), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10, 10, '#', 1);
        $pdf->Cell(50, 10, 'Date', 1);
        $pdf->Cell(50, 10, 'Montant', 1);
        $pdf->Cell(60, 10, utf8_decode('Méthode / Référence'), 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($versements as $index => $versement) {
            $pdf->Cell(10, 10, $index + 1, 1);
            $pdf->Cell(50, 10, $versement->date_versement, 1);
            $pdf->Cell(50, 10, number_format($versement->montant_verse, 2) . ' FCFA', 1);
            $pdf->Cell(60, 10, utf8_decode($versement->reference ?? '---'), 1); // Si tu as une méthode de paiement
            $pdf->Ln();
        }

        $filename = 'versements_eleve_' . $eleve->id . '_' . time() . '.pdf';
        $savePath = public_path('versements/' . $filename);
        if (!file_exists(public_path('versements'))) {
            mkdir(public_path('versements'), 0777, true);
        }

        $pdf->Output('F', $savePath);
        $publicUrl = asset('versements/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }


}
