<?php

namespace App\Http\Controllers\configurationScolaire;
use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Moyenne;
use App\Models\Niveau;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;
use App\Services\AnneeAcademiqueService;

class MoyenneScolaireController extends Controller
{
    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        // Récupère les affectations de classes pour l'année en cours
        $classes = Classe::with(['niveau', 'salle'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        $eleves = Inscription::with('eleve')
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get()
            ->pluck('eleve');


        $matieres = Matiere::all();
        $niveaux = Niveau::all();
        $semestres = $anneeScolaireActuelle->semestres;
        $moyennes = Moyenne::where('anneeacademique_id', $anneeScolaireActuelle->id)->get();

        return view('configurations.moyennes.gestionmoyenne', compact('classes', 'eleves', 'matieres', 'niveaux', 'semestres',  'moyennes'));
    }


    public function printBulletin(Request $request)
    {
        $eleve = Eleve::find($request->eleve_id);
        $anneeAcademiqueEnCours  = $this->anneeAcademiqueService->getAnneeActive();

        $semestre = Semestre::find($request->semestre_id);
        $classe = Classe::find($request->classe_id);

        // Récupération des moyennes de l'élève
        $moyenneEleve = Moyenne::where('semestre_id', $semestre->id)
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->where('eleve_id', $eleve->id)
            ->get();

        // Moyenne générale
        $totalCoef = $moyenneEleve->sum(fn($m) => $m->matiere->coefficient ?? 1);
        $totalNote = $moyenneEleve->sum(fn($m) => $m->moyenne * ($m->matiere->coefficient ?? 1));
        $moyenneGenerale = $totalCoef ? $totalNote / $totalCoef : 0;

        // Élèves de la classe
        $eleveIds = Inscription::where('classe_id', $classe->id)
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->pluck('eleve_id');

        // Moyennes générales de la classe
        $moyennesClasse = collect();
        foreach ($eleveIds as $id) {
            $moyennes = Moyenne::where('eleve_id', $id)
                ->where('semestre_id', $semestre->id)
                ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
                ->get();

            $coefSum = $moyennes->sum(fn($m) => $m->matiere->coefficient ?? 1);
            $noteSum = $moyennes->sum(fn($m) => $m->moyenne * ($m->matiere->coefficient ?? 1));
            $moyennesClasse->push($coefSum ? $noteSum / $coefSum : 0);
        }

        $moyenneMax = $moyennesClasse->max();
        $moyenneMin = $moyennesClasse->min();

        // Préparer les rangs par matière
        $rangsParMatiere = [];
        $moyennesParClasse = Moyenne::whereIn('eleve_id', $eleveIds)
            ->where('semestre_id', $semestre->id)
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->get()
            ->groupBy('matiere_id');

        foreach ($moyennesParClasse as $matiere_id => $notes) {
            $classement = $notes->sortByDesc('moyenne')->values();
            foreach ($classement as $index => $item) {
                $rangsParMatiere[$matiere_id][$item->eleve_id] = $index + 1;
            }
        }
        // --- Création PDF ---
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        $pdf->SetXY(40, 10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(130, 5, utf8_decode("REPUBLIQUE DE CÔTE D'IVOIRE\nMINISTÈRE DE L'EDUCATION NATIONALE ET DE LA RECHERCHE SCIENTIFIQUE"), 0, 'C');


        $pdf->SetXY(150, 25);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, utf8_decode("INSTITUT ROOSEVELT"), 0, 2, 'C');
        $pdf->Cell(50, 5, "Code: 058877", 0, 2, 'C');
        $pdf->Cell(50, 5, "Statut: Public", 0, 2, 'C');

        $pdf->Ln(25);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, utf8_decode("BULLETIN DE NOTES: $semestre->name"), 1, 1, 'L');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(130, 8, " ", 0, 0);
        $pdf->Cell(60, 8, utf8_decode("Année Scolaire: $anneeAcademiqueEnCours->name"), 0, 1, 'R');

        $pdf->SetFillColor(192, 192, 192);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(120, 8, "$eleve->nom $eleve->prenom", 1, 0, 'L', true);
        $pdf->Cell(70, 8, utf8_decode("Matricule: $eleve->matricule"), 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $y = $pdf->GetY();
        $pdf->SetXY(100, $y);
        $pdf->MultiCell(100, 5, utf8_decode("Status eleve : " . ($eleve->statuseleve_id == 1 ? "Oui" : "Non") . "\nSexe : " . ($eleve->genre_id == 1 ? "Masculin" : "Féminin") . "\nNationalité : " . $eleve->nationalite . "\nAffecté(e) :\tNé(e) le : " . date('d/m/Y', strtotime($eleve->date_naissance)) . " à " . $eleve->adresse . "\nRedoublant(e): " . ($eleve->statuseleve_id == 2 ? "Oui" : "Non")), 0, 'L');


        $pdf->Ln(5);

        // --- Tableau des notes ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 10, utf8_decode('Matière'), 1);
        $pdf->Cell(20, 10, utf8_decode('Moy'), 1);
        $pdf->Cell(20, 10, utf8_decode('Coef.'), 1);
        $pdf->Cell(20, 10, utf8_decode('M*Coeff.'), 1);
        $pdf->Cell(20, 10, utf8_decode('Rang'), 1);
        $pdf->Cell(50, 10, utf8_decode('Appréciation'), 1);

        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);

        foreach ($moyenneEleve as $moyenne) {
            $matiere = $moyenne->matiere;
            $moy = $moyenne->moyenne;
            $coef = $matiere->coefficient ?? 1;
            $mcoeff = $moy * $coef;

            // Rang réel dans la matière
            $rang = $rangsParMatiere[$matiere->id][$eleve->id] ?? '-';

            $appreciation = match (true) {
                $moy < 5 => 'Très Faible',
                $moy < 10 => 'Passable',
                $moy < 15 => 'Bien',
                default => 'Très Bien'
            };

            $pdf->Cell(60, 8, utf8_decode($matiere->name), 1);
            $pdf->Cell(20, 8, number_format($moy, 2), 1, 0, 'C');
            $pdf->Cell(20, 8, $coef, 1, 0, 'C');
            $pdf->Cell(20, 8, number_format($mcoeff, 2), 1, 0, 'C');
            $pdf->Cell(20, 8, $rang, 1, 0, 'C');
            $pdf->Cell(50, 8, utf8_decode($appreciation), 1);
            $pdf->Ln();
        }

        // Moyenne générale et statistiques
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 8, utf8_decode('Moyenne Générale'), 1);
        $pdf->Cell(20, 8, number_format($moyenneGenerale, 2), 1, 1, 'C');

        $pdf->Cell(60, 8, utf8_decode('Meilleure Moy. Classe'), 1);
        $pdf->Cell(20, 8, number_format($moyenneMax, 2), 1, 1, 'C');

        $pdf->Cell(60, 8, utf8_decode('Plus Faible Moy.'), 1);
        $pdf->Cell(20, 8, number_format($moyenneMin, 2), 1, 1, 'C');


        // Génération du fichier
        $filename = 'bulletin_' . $eleve->id . '_' . Str::random(6) . '.pdf';
        $savePath = public_path('bulletins/' . $filename);
        if (!file_exists(public_path('bulletins'))) {
            mkdir(public_path('bulletins'), 0777, true);
        }

        $pdf->Output('F', $savePath);
        $publicUrl = asset('bulletins/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }
}
