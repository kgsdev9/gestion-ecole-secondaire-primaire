<?php

namespace App\Http\Controllers\Examen;

use App\Http\Controllers\Controller;
use App\Models\Repartition;
use App\Models\RepartitionDetail;
use App\Models\ResultatExamen;
use App\Models\ResultatExamenLigne;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
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


    public function printRepartitonExamen(Request $request)
    {

        $repartition = Repartition::where('code', $request->codeexamen)->firstOrFail();
        $details = RepartitionDetail::with(['eleve', 'salle'])
            ->where('code', $repartition->code)
            ->get();


            $pdf = new Fpdf();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode("Répartition de l'examen : {$repartition->examen->name}"), 0, 1, 'C');

            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 10, utf8_decode("Examen : {$repartition->examen->name} | Année académique : {$repartition->anneeAcademique->name}"), 0, 1);
            $pdf->Cell(0, 5, utf8_decode("Code : {$repartition->code}"), 0, 1);
            $pdf->Cell(0, 5, utf8_decode("Classe : {$repartition->examen->classe->name}"), 0, 1);

            // ✅ Dates depuis $repartition->examen
            $debut = Carbon::parse($repartition->examen->date_debut)->format('d/m/Y');
            $fin = Carbon::parse($repartition->examen->date_fin)->format('d/m/Y');
            $pdf->Cell(0, 5, utf8_decode("Période de l'examen : du {$debut} au {$fin}"), 0, 1);

            $pdf->Ln(5);
        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 8, '#', 1);
        $pdf->Cell(70, 8, utf8_decode('Nom de l\'élève'), 1);
        $pdf->Cell(40, 8, utf8_decode('Matricule'), 1);
        $pdf->Cell(60, 8, 'Salle', 1);
        $pdf->Ln();

        // Lignes
        $pdf->SetFont('Arial', '', 10);
        foreach ($details as $i => $detail) {
            $pdf->Cell(10, 8, $i + 1, 1);
            $pdf->Cell(70, 8, utf8_decode($detail->eleve->nom), 1);
            $pdf->Cell(40, 8, $detail->eleve->matricule, 1);
            $pdf->Cell(60, 8, utf8_decode(optional($detail->salle)->name ?? '---'), 1);
            $pdf->Ln();
        }

        $filename = 'repartition' . time() . '.pdf';
        $savePath = public_path('repartition/' . $filename);
        if (!file_exists(public_path('repartition'))) {
            mkdir(public_path('repartition'), 0777, true);
        }

        $pdf->Output('F', $savePath);
        $publicUrl = asset('repartition/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }


    public function printMoyenneExamen(Request $request)
    {

        $repartition = Repartition::where('code', $request->codeexamen)->firstOrFail();
        $details = RepartitionDetail::with(['eleve', 'salle'])
            ->where('code', $repartition->code)
            ->get();


            $pdf = new Fpdf();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode("Répartition de l'examen : {$repartition->examen->name}"), 0, 1, 'C');

            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(0, 10, utf8_decode("Examen : {$repartition->examen->name} | Année académique : {$repartition->anneeAcademique->name}"), 0, 1);
            $pdf->Cell(0, 5, utf8_decode("Code : {$repartition->code}"), 0, 1);
            $pdf->Cell(0, 5, utf8_decode("Classe : {$repartition->examen->classe->name}"), 0, 1);

            // ✅ Dates depuis $repartition->examen
            $debut = Carbon::parse($repartition->examen->date_debut)->format('d/m/Y');
            $fin = Carbon::parse($repartition->examen->date_fin)->format('d/m/Y');
            $pdf->Cell(0, 5, utf8_decode("Période de l'examen : du {$debut} au {$fin}"), 0, 1);

            $pdf->Ln(5);
        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 8, '#', 1);
        $pdf->Cell(70, 8, utf8_decode('Nom de l\'élève'), 1);
        $pdf->Cell(40, 8, utf8_decode('Matricule'), 1);
        $pdf->Cell(60, 8, 'Salle', 1);
        $pdf->Ln();

        // Lignes
        $pdf->SetFont('Arial', '', 10);
        foreach ($details as $i => $detail) {
            $pdf->Cell(10, 8, $i + 1, 1);
            $pdf->Cell(70, 8, utf8_decode($detail->eleve->nom), 1);
            $pdf->Cell(40, 8, $detail->eleve->matricule, 1);
            $pdf->Cell(60, 8, utf8_decode(optional($detail->salle)->name ?? '---'), 1);
            $pdf->Ln();
        }

        $filename = 'repartition' . time() . '.pdf';
        $savePath = public_path('repartition/' . $filename);
        if (!file_exists(public_path('repartition'))) {
            mkdir(public_path('repartition'), 0777, true);
        }

        $pdf->Output('F', $savePath);
        $publicUrl = asset('repartition/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }

}
