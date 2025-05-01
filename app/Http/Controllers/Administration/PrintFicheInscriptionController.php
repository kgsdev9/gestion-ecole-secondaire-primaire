<?php

namespace App\Http\Controllers\Administration;
use App\Http\Controllers\Controller;
use App\Models\Eleve;
use Codedge\Fpdf\Fpdf\Fpdf;
class PrintFicheInscriptionController extends Controller
{
    public function printFicheInscription($eleveId)
    {
        $eleve = Eleve::with(['classe', 'niveau', 'anneeacademique', 'genre', 'statuseleve'])->findOrFail($eleveId);

        $pdf = new Fpdf();
        $pdf->AddPage();

        // En-tête
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Fiche d\'Inscription Élève'), 0, 1, 'C');
        $pdf->Ln(5);

        // Infos Élève
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(0, 10, utf8_decode("Nom : {$eleve->nom}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Prénom : {$eleve->prenom}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Matricule : {$eleve->matricule}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Date de naissance : " . date('d/m/Y', strtotime($eleve->date_naissance))), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Adresse : {$eleve->adresse}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Téléphone Parent : {$eleve->telephone_parent}"), 0, 1);
        $pdf->Ln(5);

        // Infos Scolaires
        $pdf->Cell(0, 10, utf8_decode("Année Académique : {$eleve->anneeacademique->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Classe : {$eleve->classe->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Niveau : {$eleve->niveau->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Genre : {$eleve->genre->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Statut : {$eleve->statuseleve->name}"), 0, 1);
        $pdf->Cell(0, 10, utf8_decode("Nationalité : {$eleve->nationalite}"), 0, 1);
        $pdf->Ln(15);

        // Zone des soussignés
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 10, utf8_decode("Fait à _____________________ le ____/____/______"), 0, 1);
        $pdf->Ln(10);
        $pdf->Cell(90, 10, utf8_decode("Signature du Chef d'Établissement"), 0, 0, 'C');
        $pdf->Cell(90, 10, utf8_decode("Signature du Parent / Tuteur"), 0, 1, 'C');

        // Sortie

        $pdf->Output('D', "Resultat_{$eleve->matricule}.pdf");

    }
}
