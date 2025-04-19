<?php

namespace App\Http\Controllers\Depenses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepensesController extends Controller
{

    public function printBulletin(Request $request)
    {

        $eleve = Eleve::find($request->eleve_id);


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
        $pdf->Cell(120, 8, "$eleve->nom" . " " . "$eleve->nom", 1, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("Matricule: $eleve->matricule"), 1, 1, 'L', true);

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


        $filename = 'bulletin_' . $request->eleve_id . '_' . Str::random(6) . '.pdf';
        $savePath = public_path('bulletins/' . $filename);

        // S'assurer que le dossier existe
        if (!file_exists(public_path('bulletins'))) {
            mkdir(public_path('bulletins'), 0777, true);
        }

        $pdf->Output('F', $savePath); // F pour File (sauvegarde dans un fichier)

        // Retourner le lien public du fichier PDF
        $publicUrl = asset('bulletins/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('de');
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
    public function printBulletin(Request $request)
    {
        $eleve = Eleve::find($request->eleve_id);
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

        // Récupérer les moyennes de l'élève
        $moyenneEleve = Moyenne::where('semestre_id', $request->semestre_id)
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->where('eleve_id', $request->eleve_id)
            ->get();

        // Classe et semestre
        $classe = AffectionAcademique::find($request->classe_id);
        $semestre = Semestre::find($request->semestre_id);

        // --- Calcul de la moyenne générale de l'élève ---
        $totalMoyenne = 0;
        $totalCoef = 0;
        foreach ($moyenneEleve as $moyenne) {
            $totalMoyenne += $moyenne->moyenne * $moyenne->matiere->coefficient;
            $totalCoef += $moyenne->matiere->coefficient ?? 1;
        }
        $moyenneGenerale = $totalMoyenne / $totalCoef;

        // --- Calcul de la plus faible et la plus forte moyenne de la classe ---
        $moyennesClasse = Moyenne::where('semestre_id', $request->semestre_id)
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->whereIn('eleve_id', AffectionAcademique::where('classe_id', $request->classe_id)->pluck('eleve_id'))
            ->get();

        $moyenneMax = $moyennesClasse->max('moyenne');
        $moyenneMin = $moyennesClasse->min('moyenne');

        // --- Affichage dans le PDF ---
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        // --- Titre du bulletin ---
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, utf8_decode("BULLETIN DE NOTES: $semestre->name"), 1, 1, 'L');

        // --- Année scolaire / Matricule ---
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(130, 8, " ", 0, 0);
        $pdf->Cell(60, 8, utf8_decode("Année Scolaire: $anneeAcademiqueEnCours->name"), 0, 1, 'R');

        // --- Informations de l'élève ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(120, 8, "$eleve->nom $eleve->prenom", 1, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("Matricule: $eleve->matricule"), 1, 1, 'L', true);

        // --- Table des moyennes ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 10, 'Matière', 1);
        $pdf->Cell(20, 10, 'Moy', 1);
        $pdf->Cell(20, 10, 'Coef.', 1);
        $pdf->Cell(20, 10, 'M*Coeff.', 1);
        $pdf->Cell(20, 10, 'Rang', 1);
        $pdf->Cell(50, 10, utf8_decode('Appréciation'), 1);
        $pdf->Ln();

        // --- Boucle sur les matières de l'élève ---
        $pdf->SetFont('Arial', '', 9);
        foreach ($moyenneEleve as $moyenne) {
            $matiere = $moyenne->matiere;
            $moy = $moyenne->moyenne;
            $coef = $matiere->coefficient ?? 1;
            $mcoeff = $moy * $coef;

            // --- Calcul du rang pour chaque matière ---
            $moyennesMatiere = Moyenne::where('matiere_id', $matiere->id)
                ->where('semestre_id', $request->semestre_id)
                ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
                ->get();
            $rang = $moyennesMatiere->sortByDesc('moyenne')->search(function ($item) use ($moy) {
                return $item->moyenne == $moy;
            }) + 1;

            // --- Appréciation ---
            $appreciation = '';
            if ($moy < 5) {
                $appreciation = 'Très Faible';
            } elseif ($moy >= 5 && $moy < 10) {
                $appreciation = 'Passable';
            } elseif ($moy >= 10 && $moy < 15) {
                $appreciation = 'Bien';
            } else {
                $appreciation = 'Très Bien';
            }

            // Affichage dans le PDF
            $pdf->Cell(60, 8, utf8_decode($matiere->name), 1);
            $pdf->Cell(20, 8, number_format($moy, 2), 1, 0, 'C');
            $pdf->Cell(20, 8, $coef, 1, 0, 'C');
            $pdf->Cell(20, 8, number_format($mcoeff, 2), 1, 0, 'C');
            $pdf->Cell(20, 8, $rang, 1, 0, 'C');
            $pdf->Cell(50, 8, utf8_decode($appreciation), 1);
            $pdf->Ln();
        }

        // --- Affichage des statistiques ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 8, "Moyenne Générale", 1);
        $pdf->Cell(60, 8, number_format($moyenneGenerale, 2), 1, 1, 'C');
        $pdf->Cell(60, 8, "Moyenne Max de la classe", 1);
        $pdf->Cell(60, 8, number_format($moyenneMax, 2), 1, 1, 'C');
        $pdf->Cell(60, 8, "Moyenne Min de la classe", 1);
        $pdf->Cell(60, 8, number_format($moyenneMin, 2), 1, 1, 'C');

        // --- Sauvegarde du PDF ---
        $filename = 'bulletin_' . $request->eleve_id . '_' . Str::random(6) . '.pdf';
        $savePath = public_path('bulletins/' . $filename);
        if (!file_exists(public_path('bulletins'))) {
            mkdir(public_path('bulletins'), 0777, true);
        }
        $pdf->Output('F', $savePath);

        // Retourner le lien du PDF
        $publicUrl = asset('bulletins/' . $filename);
        return response()->json([
            'url' => $publicUrl
        ]);
    }


    // public function printBulletinR(Request $request)
    // {

    //     // dd($request->all());
    //     $eleve = Eleve::find($request->eleve_id);

    //     $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();

    //     $moyenneEleve = Moyenne::where('semestre_id', $request->semestre_id)
    //         ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
    //         ->where('eleve_id', $request->eleve_id)
    //         ->get();

    //     $classe =  AffectionAcademique::find($request->classe_id);

    //     $semestre = Semestre::find($request->semestre_id);

    //     $pdf = new Fpdf();
    //     $pdf->AddPage();
    //     $pdf->SetFont('Arial', '', 10);

    //     // --- Logos et titres principaux ---
    //     // Logo gauche
    //     // $pdf->Image(public_path('images/logo_menfpa.png'), 10, 10, 30); // ajuster selon ton chemin

    //     // // Logo droite
    //     // $pdf->Image(public_path('images/logo_lycee.png'), 170, 10, 30);

    //     // Texte du haut centré
    //     $pdf->SetXY(40, 10);
    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->MultiCell(130, 5, utf8_decode("REPUBLIQUE DE CÔTE D'IVOIRE\nMINISTÈRE DE L'ENSEIGNEMENT TECHNIQUE DE LA\nFORMATION PROFESSIONNELLE ET DE L’APPRENTISSAGE\nDIRECTION RÉGIONALE SAN PEDRO"), 0, 'C');

    //     // Encadré lycée à droite
    //     $pdf->SetXY(150, 25);
    //     $pdf->SetFont('Arial', '', 9);
    //     $pdf->Cell(50, 5, utf8_decode("LYCÉE PROFESSIONNEL DE SAN-PEDRO"), 0, 2, 'C');
    //     $pdf->Cell(50, 5, "Code: 058877", 0, 2, 'C');
    //     $pdf->Cell(50, 5, "Statut: Public", 0, 2, 'C');

    //     $pdf->Ln(25);

    //     // --- Titre du bulletin ---
    //     $pdf->SetFont('Arial', 'B', 11);
    //     $pdf->Cell(0, 8, utf8_decode("BULLETIN DE NOTES: $semestre->name"), 1, 1, 'L');

    //     // --- Ligne: Année scolaire / Matricule ---
    //     $pdf->SetFont('Arial', '', 10);
    //     $pdf->Cell(130, 8, " ", 0, 0); // espace à gauche
    //     $pdf->Cell(60, 8, utf8_decode("Année Scolaire: $anneeAcademiqueEnCours->name"), 0, 1, 'R');

    //     $pdf->SetFillColor(192, 192, 192); // fond gris clair
    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->Cell(120, 8, "$eleve->nom" . " " . "$eleve->nom", 1, 0, 'L', true);
    //     $pdf->Cell(70, 8, utf8_decode("Matricule: $eleve->matricule"), 1, 1, 'L', true);

    //     // --- Bloc Infos personnelles ---
    //     $pdf->SetFont('Arial', '', 9);
    //     $y = $pdf->GetY();

    //     // Image QR code ou icône
    //     // $pdf->Image(public_path('images/qr.png'), 10, $y + 2, 25);

    //     $pdf->SetXY(100, $y);
    //     $pdf->MultiCell(100, 5, utf8_decode("Régime :\tInterne : Non\nSexe : Masculin\nNationalité : Ivoirienne\nAffecté(e):\tNé(e) le : 03/11/2006 à SAN PEDRO\nRedoublant(e): Non"), 0, 'L');

    //     $pdf->Ln(5);

    //     $pdf->SetFont('Arial', 'B', 10);
    //     $pdf->Cell(60, 10, 'Matière', 1);
    //     $pdf->Cell(20, 10, 'Moy', 1);
    //     $pdf->Cell(20, 10, 'Coef.', 1);
    //     $pdf->Cell(20, 10, 'M*Coeff.', 1);
    //     $pdf->Cell(20, 10, 'Rang', 1);
    //     $pdf->Cell(50, 10, utf8_decode('Appréciation'), 1);
    //     $pdf->Ln();

    //     // Récupération des moyennes et des matières
    //     $pdf->SetFont('Arial', '', 9);
    //     foreach ($moyenneEleve as $moyenne) {
    //         // Récupérer la matière associée à chaque moyenne
    //         $matiere = $moyenne->matiere; // Assurer que la relation est définie dans le modèle Moyenne
    //         $moy = $moyenne->moyenne;  // Moyenne de l'élève
    //         $coef = $matiere->coefficient ?? 1;  // Coefficient de la matière (Assume cette donnée est dans le modèle Matiere)
    //         $mcoeff = $moy * $coef;  // Calcul du produit Moyenne * Coefficient
    //         $rang = '';  // Tu peux calculer le rang si tu veux, selon un critère particulier
    //         $appreciation = '';  // Logique pour apprécier la note (par exemple : "Très Faible", "Assez Bien")

    //         // Exemple d'appréciation basée sur la moyenne
    //         if ($moy < 5) {
    //             $appreciation = 'Très Faible';
    //         } elseif ($moy >= 5 && $moy < 10) {
    //             $appreciation = 'Passable';
    //         } elseif ($moy >= 10 && $moy < 15) {
    //             $appreciation = 'Bien';
    //         } else {
    //             $appreciation = 'Très Bien';
    //         }

    //         // Affichage des données dans le tableau
    //         $pdf->Cell(60, 8, utf8_decode($matiere->name), 1);
    //         $pdf->Cell(20, 8, number_format($moy, 2), 1, 0, 'C');
    //         $pdf->Cell(20, 8, $coef, 1, 0, 'C');
    //         $pdf->Cell(20, 8, number_format($mcoeff, 2), 1, 0, 'C');
    //         $pdf->Cell(20, 8, $rang, 1, 0, 'C');
    //         $pdf->Cell(50, 8, utf8_decode($appreciation), 1);
    //         $pdf->Ln();
    //     }



    //     $filename = 'bulletin_' . $request->eleve_id . '_' . Str::random(6) . '.pdf';
    //     $savePath = public_path('bulletins/' . $filename);

    //     // S'assurer que le dossier existe
    //     if (!file_exists(public_path('bulletins'))) {
    //         mkdir(public_path('bulletins'), 0777, true);
    //     }

    //     $pdf->Output('F', $savePath); // F pour File (sauvegarde dans un fichier)

    //     // Retourner le lien public du fichier PDF
    //     $publicUrl = asset('bulletins/' . $filename);

    //     return response()->json([
    //         'url' => $publicUrl
    //     ]);
    // }

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
