<?php

namespace App\Http\Controllers\Administration\Impressions;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
class PrintListeClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function printClasseListe(Request $request)
    {
        $classe = Classe::with('students')->findOrFail($request->classe_id);
        $students = $classe->students;
        // Générer le PDF avec FPDF
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode("Liste des élèves - Classe : " . $classe->name), 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10, 10, '#', 1);
        $pdf->Cell(80, 10, 'Nom complet', 1);
        $pdf->Cell(50, 10, 'Matricule', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        foreach ($students as $index => $student) {
            $pdf->Cell(10, 10, $index + 1, 1);
            $pdf->Cell(80, 10, utf8_decode($student->nom), 1);
            $pdf->Cell(50, 10, $student->matricule, 1);
            $pdf->Ln();
        }

        $filename = 'liste_classe_' . time() . '.pdf';
        $savePath = public_path('listeclasse/' . $filename);
        if (!file_exists(public_path('listeclasse'))) {
            mkdir(public_path('listeclasse'), 0777, true);
        }

        $pdf->Output('F', $savePath);
        $publicUrl = asset('listeclasse/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);

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
