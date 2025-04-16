<?php

namespace App\Http\Controllers\Bulletin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class BulletinConroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Création du PDF
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Titre du bulletin scolaire
        $pdf->SetXY(36, 28);
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(190, 10, 'BULLETIN SCOLAIRE', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 8);

        // Informations sur l'élève (Données statiques)
        $pdf->SetXY(5, 40);
        $pdf->Cell(30, 6, 'Nom de l\'Eleve:', 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(50, 6, 'John Doe', 1, 1, 'L');

        $pdf->SetXY(5, 46);
        $pdf->Cell(30, 6, 'Classe:', 1, 0, 'L');
        $pdf->Cell(50, 6, 'Classe de 10ème', 1, 1, 'L'); // Classe de l'élève

        // Ajouter un espace entre les informations et le tableau
        $pdf->SetXY(5, 55);

        // Tableau des matières (Données statiques)
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 6, 'Matière', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Note', 1, 0, 'C');
        $pdf->Cell(30, 6, 'Moyenne', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 8);
        $yPosition = 61;

        // Matières et notes (données statiques)
        $matières = [
            ['Libelle' => 'Mathématiques', 'Note' => 15, 'Moyenne' => 14],
            ['Libelle' => 'Français', 'Note' => 18, 'Moyenne' => 16],
            ['Libelle' => 'Histoire', 'Note' => 12, 'Moyenne' => 14],
            ['Libelle' => 'Sciences', 'Note' => 13, 'Moyenne' => 14],
        ];

        foreach ($matières as $matière) {
            $pdf->SetXY(5, $yPosition);
            $pdf->Cell(40, 6, utf8_decode($matière['Libelle']), 1, 0, 'C');
            $pdf->Cell(30, 6, number_format($matière['Note'], 2), 1, 0, 'C');
            $pdf->Cell(30, 6, number_format($matière['Moyenne'], 2), 1, 1, 'C');

            $yPosition += 6; // Augmenter la position pour la ligne suivante
        }

        // Calcul du total
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(5, $yPosition);
        $pdf->Cell(40, 6, 'Total', 1, 0, 'C');
        $pdf->Cell(30, 6, '', 1, 0, 'C'); // Espace vide pour la note
        $pdf->Cell(30, 6, '15.25', 1, 1, 'C'); // Moyenne générale statique

        // Ajouter un message de fin
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(5, $yPosition + 10);
        $pdf->MultiCell(190, 6, "Félicitations! Vous avez terminé votre année scolaire avec succès. Merci de votre travail acharné.", 0, 'C');

        // Finaliser et télécharger le fichier
        return response($pdf->Output('S', 'bulletin_scolaire.pdf'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="bulletin_scolaire.pdf"');
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
