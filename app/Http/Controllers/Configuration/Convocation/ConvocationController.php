<?php

namespace App\Http\Controllers\Configuration\Convocation;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Examen;
use App\Models\ProgrammeExamenLigne;
use App\Models\RepartitionDetail;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;

class ConvocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formConvocation()
    {
        return view('configurations.convocations.form');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprimerConvocation(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'matricule' => 'required|string',
        ]);

        $examen = Examen::where('code', $request->code)->firstOrFail();
        $eleve = Eleve::where('matricule', $request->matricule)->firstOrFail();

        $programmes = ProgrammeExamenLigne::where('examen_id', $examen->id)
            ->where('anneeacademique_id', $examen->anneeacademique_id)
            ->with('matiere')
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();


           $salledecomposition =  RepartitionDetail::where('eleve_id', $eleve->id)
                                ->where('examen_id', $examen->id)
                                ->first();
        $pdf = new Fpdf();
        $pdf->AddPage();

        // Titre
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Convocation à l\'examen', 0, 1, 'C');
        $pdf->Ln(5);

        // Infos élève
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Nom : {$eleve->nom}", 0, 1);
        $pdf->Cell(0, 10, "Prénom : {$eleve->prenom}", 0, 1);
        $pdf->Cell(0, 10, "Matricule : {$eleve->matricule}", 0, 1);
        $pdf->Cell(0, 10, "Salle de composition : {$salledecomposition->salle->name}", 0, 1);
        $pdf->Ln(5);

        // Infos examen
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Examen : {$examen->name}", 0, 1);
        $pdf->Cell(0, 10, "Code : {$examen->code}", 0, 1);
        $pdf->Cell(0, 10, "Année Académique : {$examen->anneeAcademique->name}", 0, 1);
        $pdf->Cell(0, 10, "Période : du {$examen->date_debut} au {$examen->date_fin}", 0, 1);
        $pdf->Ln(8);

        // Tableau programme
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Jour');
        $pdf->Cell(60, 10, 'Matière');
        $pdf->Cell(30, 10, 'Heure Début');
        $pdf->Cell(30, 10, 'Heure Fin');
        $pdf->Cell(30, 10, 'Durée');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 11);
        foreach ($programmes as $ligne) {
            $pdf->Cell(40, 10, $ligne->jour);
            $pdf->Cell(60, 10, $ligne->matiere->name ?? '');
            $pdf->Cell(30, 10, $ligne->heure_debut);
            $pdf->Cell(30, 10, $ligne->heure_fin);
            $pdf->Cell(30, 10, $ligne->duree);
            $pdf->Ln();
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="convocation.pdf"');
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
