<?php

namespace App\Http\Controllers\Administration\Impressions;

use App\Http\Controllers\Controller;
use App\Models\RapportSemestre;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
class PrintResultatSemestreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function printResultatSemestre($resultatId)
    {
        $rapportsemestre = RapportSemestre::find($resultatId);

        if (!$rapportsemestre) {
            return response()->json(['error' => 'Rapport non trouvé'], 404);
        }

        $rapportdetails = $rapportsemestre->itemsRapport;

        $pdf = new Fpdf();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 14);

        // Titre principal
        $pdf->SetFont('Arial', 'B', 14);
        $titre = 'RAPPORT DE SEMESTRE - ' . $rapportsemestre->semestre->name;
        $pdf->Cell(0, 10, utf8_decode($titre), 0, 1, 'C');

        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);

        // Infos générales
        $pdf->Cell(100, 8, utf8_decode('Année académique : ') . utf8_decode($rapportsemestre->anneeacademique->name), 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Niveau : ') . utf8_decode($rapportsemestre->niveau->name), 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Classe : ') . utf8_decode($rapportsemestre->classe->name), 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Semestre : ') . utf8_decode($rapportsemestre->semestre->name), 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Nombre d\'élèves : ') . $rapportsemestre->nombre_eleves, 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Taux de réussite : ') . $rapportsemestre->taux_reussite . '%', 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Moyenne générale : ') . $rapportsemestre->moyenne_generale, 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Observations : ') . utf8_decode($rapportsemestre->observations), 0, 1);
        $pdf->Ln(10);

        // Tableau des résultats
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(100, 10, utf8_decode('Nom Prénom'), 1, 0, 'C', true);
        $pdf->Cell(60, 10, utf8_decode('Matricule'), 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Moy.', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Rang', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Mention', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Admis', 1, 0, 'C', true);
        $pdf->Cell(30, 10, utf8_decode('Observation'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 11);

        foreach ($rapportdetails as $ligne) {
            $eleve = $ligne->eleve;

            $pdf->Cell(100, 8, utf8_decode($eleve->nom . ' ' . $eleve->prenom), 1);
            $pdf->Cell(60, 8, utf8_decode($eleve->matricule), 1);
            $pdf->Cell(20, 8, $ligne->moyenne, 1, 0, 'C');
            $pdf->Cell(20, 8, $ligne->rang, 1, 0, 'C');
            $pdf->Cell(30, 8, utf8_decode($ligne->mention), 1, 0, 'C');
            $pdf->Cell(20, 8, $ligne->admis ? 'Oui' : 'Non', 1, 0, 'C');
            $pdf->Cell(30, 8, utf8_decode($ligne->observation), 1, 1);
        }

        $pdf->Output('D', 'Rapport_Semestre_' . $rapportsemestre->id . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
