<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\TClient;
use App\Models\TFacture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function generateBulletin($bulletin)
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        // --- Logos et titres principaux ---
        // Logo gauche
        // $pdf->Image(public_path('images/logo_menfpa.png'), 10, 10, 30); // ajuster selon ton chemin

        // // Logo droite
        // $pdf->Image(public_path('images/logo_lycee.png'), 170, 10, 30);

        // Texte du haut centré
        $pdf->SetXY(40, 10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(130, 5, utf8_decode("REPUBLIQUE DE CÔTE D'IVOIRE\nMINISTÈRE DE L'ENSEIGNEMENT TECHNIQUE DE LA\nFORMATION PROFESSIONNELLE ET DE L’APPRENTISSAGE\nDIRECTION RÉGIONALE SAN PEDRO"), 0, 'C');

        // Encadré lycée à droite
        $pdf->SetXY(150, 25);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, utf8_decode("LYCÉE PROFESSIONNEL DE SAN-PEDRO"), 0, 2, 'C');
        $pdf->Cell(50, 5, "Code: 058877", 0, 2, 'C');
        $pdf->Cell(50, 5, "Statut: Public", 0, 2, 'C');

        $pdf->Ln(25);

        // --- Titre du bulletin ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, utf8_decode("BULLETIN DE NOTES: Semestre 1"), 1, 1, 'L');

        // --- Ligne: Année scolaire / Matricule ---
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(130, 8, " ", 0, 0); // espace à gauche
        $pdf->Cell(60, 8, utf8_decode("Année Scolaire: 2023–2024"), 0, 1, 'R');

        $pdf->SetFillColor(192, 192, 192); // fond gris clair
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(120, 8, "AMON ADAMA", 1, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("Matricule: 19614204A"), 1, 1, 'L', true);

        // --- Bloc Infos personnelles ---
        $pdf->SetFont('Arial', '', 9);
        $y = $pdf->GetY();

        // Image QR code ou icône
        // $pdf->Image(public_path('images/qr.png'), 10, $y + 2, 25);

        $pdf->SetXY(38, $y);
        $pdf->MultiCell(60, 5, utf8_decode("Filière : 1CAP MENUISERIE EBENISTERIE\nClasse : 1CAP/ME\nEffectif : 21"), 0, 'L');

        $pdf->SetXY(100, $y);
        $pdf->MultiCell(100, 5, utf8_decode("Régime :\tInterne : Non\nSexe : Masculin\nNationalité : Ivoirienne\nAffecté(e):\tNé(e) le : 03/11/2006 à SAN PEDRO\nRedoublant(e): Non"), 0, 'L');

        $pdf->Ln(5);

        // Tableau des notes
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 10, 'Matière', 1);
        $pdf->Cell(20, 10, 'Moy', 1);
        $pdf->Cell(20, 10, 'Coef.', 1);
        $pdf->Cell(20, 10, 'M*Coeff.', 1);
        $pdf->Cell(20, 10, 'Rang', 1);
        $pdf->Cell(50, 10, utf8_decode('Appréciation'), 1);
        $pdf->Ln();

        $notes = [
            ['Travaux Pratiques', 13, 10, 130, '10ex', 'Assez Bien'],
            ['Anglais', 1.66, 1, 1.66, '10ème', 'Très Faible'],
            ['Education aux Droits...', 10.5, 2, 21, '4ème', 'Passable'],
            ['EPS', 0.1, 1, 0.1, '6ème', 'Très Faible'],
            ['Informatique', 4, 1, 4, '12ème', 'Très Faible'],
            ['Législation', 4, 1, 4, '9ex', 'Faible'],
            ['Mathématiques', 3.8, 2, 7.6, '11ème', 'Très Faible'],
            ['Sciences Physiques', 2.75, 1, 2.75, '13ème', 'Faible'],
            ['Techniques d\'expression', 3, 1, 3, '10ème', 'Très Faible'],
            ['Connaissances du Monde', 3.2, 1, 3.2, '9ex', 'Très Faible'],
        ];

        $pdf->SetFont('Arial', '', 9);
        foreach ($notes as $note) {
            [$matiere, $moy, $coef, $mcoeff, $rang, $appreciation] = $note;
            $pdf->Cell(60, 8, utf8_decode($matiere), 1);
            $pdf->Cell(20, 8, $moy, 1, 0, 'C');
            $pdf->Cell(20, 8, $coef, 1, 0, 'C');
            $pdf->Cell(20, 8, $mcoeff, 1, 0, 'C');
            $pdf->Cell(20, 8, $rang, 1, 0, 'C');
            $pdf->Cell(50, 8, utf8_decode($appreciation), 1);
            $pdf->Ln();
        }

        $pdf->Output();
        exit;
    }

    public function index()
    {
        $this->generateBulletin([]);

        $listerecentesfactures =  [];
        $countlisterecentesfactures = 0;
        $ventes  =  [];
        $counCclient = 0;
        $counFaturesVentes = 0;


        // $bilan = Eleve::select(DB::raw('MONTH(created_at) as mois'), DB::raw('SUM(created_at as total'))
        //     ->groupBy('mois')
        //     ->orderBy('mois')
        //     ->get();


        // Transformer les données pour Chart.js
        // $labels = $bilan->pluck('mois')->map(function ($mois) {
        //     return date('F', mktime(0, 0, 0, $mois, 1));
        // });

        // $data = $bilan->pluck('total');

        $labels = "";
        $data = [];


        $ventesJour = 0;

        $ventesSemaine = 0;


        $ventesMois = 0;


        return view('welcome', compact('listerecentesfactures', 'countlisterecentesfactures', 'ventes', 'counCclient', 'counFaturesVentes', 'labels', 'data', 'ventesJour', 'ventesSemaine', 'ventesMois'));
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
