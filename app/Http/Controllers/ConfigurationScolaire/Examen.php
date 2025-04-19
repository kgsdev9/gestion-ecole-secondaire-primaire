public function printBulletin(Request $request)
    {
        $eleve = Eleve::find($request->eleve_id);
        $anneeAcademiqueEnCours = AnneeAcademique::anneeAcademiqueEnCours();
        $semestre = Semestre::find($request->semestre_id);
        $classe = Classe::find($request->classe_id);

        // Récupération des moyennes de l'élève
        $moyenneEleve = Moyenne::where('semestre_id', $request->semestre_id)
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->where('eleve_id', $request->eleve_id)
            ->get();

        // Moyenne générale de l'élève
        $totalCoef = $moyenneEleve->sum(fn($m) => $m->matiere->coefficient ?? 1);
        $totalNote = $moyenneEleve->sum(fn($m) => $m->moyenne * ($m->matiere->coefficient ?? 1));
        $moyenneGenerale = $totalCoef ? $totalNote / $totalCoef : 0;

        // Liste des élèves de la classe pour cette année
        $eleveIds = Inscription::where('classe_id', $request->classe_id)
            ->where('anneeacademique_id', $anneeAcademiqueEnCours->id)
            ->pluck('eleve_id');

        // Moyenne min et max de la classe
        $moyennesClasse = collect();

        foreach ($eleveIds as $id)
        {
            $moyennes = Moyenne::where('eleve_id', $id)
                ->where('semestre_id', $request->semestre_id)
                ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
                ->get();

            $totalCoef = $moyennes->sum(fn($m) => $m->matiere->coefficient ?? 1);
            $totalNote = $moyennes->sum(fn($m) => $m->moyenne * ($m->matiere->coefficient ?? 1));
            $moyennesClasse->push($totalCoef ? $totalNote / $totalCoef : 0);
        }

        $moyenneMax = $moyennesClasse->max();
        $moyenneMin = $moyennesClasse->min();

        // Premiers de chaque matière
        $premiersParMatiere = Moyenne::whereIn('eleve_id', $eleveIds)
            ->where('semestre_id', $request->semestre_id)
            ->where('annee_academique_id', $anneeAcademiqueEnCours->id)
            ->get()
            ->groupBy('matiere_id')
            ->map(fn($group) => $group->sortByDesc('moyenne')->first());

        // --- Création du PDF ---
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        // Logos, entêtes, infos générales
        $pdf->SetXY(40, 10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(130, 5, utf8_decode("REPUBLIQUE DE CÔTE D'IVOIRE\nMINISTÈRE DE L'ENSEIGNEMENT TECHNIQUE DE LA\nFORMATION PROFESSIONNELLE ET DE L’APPRENTISSAGE\nDIRECTION RÉGIONALE SAN PEDRO"), 0, 'C');

        $pdf->SetXY(150, 25);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, utf8_decode("LYCÉE PROFESSIONNEL DE SAN-PEDRO"), 0, 2, 'C');
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
        $pdf->MultiCell(100, 5, utf8_decode("Régime :\tInterne : Non\nSexe : Masculin\nNationalité : Ivoirienne\nAffecté(e):\tNé(e) le : 03/11/2006 à SAN PEDRO\nRedoublant(e): Non"), 0, 'L');

        $pdf->Ln(5);

        // --- Tableau des notes ---
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 10, 'Matière', 1);
        $pdf->Cell(20, 10, 'Moy', 1);
        $pdf->Cell(20, 10, 'Coef.', 1);
        $pdf->Cell(20, 10, 'M*Coeff.', 1);
        $pdf->Cell(20, 10, 'Rang', 1);
        $pdf->Cell(50, 10, utf8_decode('Appréciation'), 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 9);

        foreach ($moyenneEleve as $moyenne) {
            $matiere = $moyenne->matiere;
            $moy = $moyenne->moyenne;
            $coef = $matiere->coefficient ?? 1;
            $mcoeff = $moy * $coef;

            // Récupérer le premier de la classe dans cette matière
            $premier = $premiersParMatiere[$matiere->id] ?? null;
            $rang = $premier && $premier->eleve_id == $eleve->id ? '1er' : '2e';

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

        // Moyenne générale
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 8, 'Moyenne Générale', 1);
        $pdf->Cell(20, 8, number_format($moyenneGenerale, 2), 1, 1, 'C');

        $pdf->Cell(60, 8, 'Meilleure Moy. Classe', 1);
        $pdf->Cell(20, 8, number_format($moyenneMax, 2), 1, 1, 'C');

        $pdf->Cell(60, 8, 'Plus Faible Moy.', 1);
        $pdf->Cell(20, 8, number_format($moyenneMin, 2), 1, 1, 'C');

        // Enregistrement du fichier PDF
        $filename = 'bulletin_' . $request->eleve_id . '_' . Str::random(6) . '.pdf';
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
