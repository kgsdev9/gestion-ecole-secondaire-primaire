
public function printEmploiDuTemps(Request $request)
    {
        $classeID = $request->classe_id;

        $classe = Classe::findOrFail($classeID);
        $jours = Jour::all()->keyBy('id');
        $emplois = EmploiDuTemps::with(['matiere', 'jour'])
            ->where('classe_id', $classeID)
            ->get();

        // Créneaux dynamiques
        $creneaux = $emplois->map(function ($e) {
            return [
                'debut' => $e->heure_debut,
                'fin'   => $e->heure_fin,
            ];
        })
            ->unique(function ($item) {
                return $item['debut'] . '-' . $item['fin'];
            })
            ->sortBy('debut')
            ->values()
            ->all();

        // Infos élève (exemple, adapte à ton modèle)
        $eleve = [
            'nom' => 'SANOGO LACINA',
            'matricule' => '19126509H',
            'sexe' => 'M',
            'regime' => 'BOURSIER',
            'redoublant' => 'NON',
            'prof_principal' => 'HAMED ADEWALE (PROFESSEUR DE PHYSIQUE - CHIMIE)',
            'educateur' => 'KOUAKOU AYA EMILIE (0748093465)',
            'heures_semaine' => '31H',
        ];

        // Début PDF
        $fpdf = new Fpdf();
        $fpdf->AddPage();

        // En-tête Éducation Nationale
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->Cell(130, 5, utf8_decode("MINISTERE DE L'EDUCATION NATIONALE"), 0, 0, 'L');
        $fpdf->Cell(0, 5, utf8_decode("REPUBLIQUE DE COTE D'IVOIRE"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("ET DE L'ALPHABETISATION"), 0, 0, 'L');
        $fpdf->Cell(0, 5, utf8_decode("Union - Discipline - Travail"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("DRENA ABIDJAN 4"), 0, 0, 'L');
        $fpdf->Cell(0, 5, utf8_decode("Année Scolaire : 2021-2022"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("LYCÉE MODERNE ALASSANE OUATTARA - ANYAMA"), 0, 0, 'L');
        $fpdf->Cell(0, 5, utf8_decode("Code : 015012   Statut : Public"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("Email : lymao2015@gmail.com"), 0, 1, 'L');

        $fpdf->Ln(5);

        // Titre centré
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Cell(0, 10, utf8_decode("EMPLOI DU TEMPS CLASSE : " . $classe->name), 1, 1, 'C');

        // Infos élève
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->Cell(90, 8, utf8_decode("NOM & PRENOMS : " . $eleve['nom']), 0);
        $fpdf->Cell(60, 8, utf8_decode("MATRICULE : " . $eleve['matricule']), 0);
        $fpdf->Cell(40, 8, utf8_decode("SEXE : " . $eleve['sexe']), 0, 1);

        $fpdf->Cell(90, 8, utf8_decode("REGIME : " . $eleve['regime']), 0);
        $fpdf->Cell(60, 8, utf8_decode("REDOUBLANT : " . $eleve['redoublant']), 0, 1);

        $fpdf->Cell(130, 8, utf8_decode("PROFESSEUR PRINCIPAL : " . $eleve['prof_principal']), 0, 1);
        $fpdf->Cell(130, 8, utf8_decode("EDUCATEUR : " . $eleve['educateur']), 0, 1);
        $fpdf->Cell(130, 8, utf8_decode("NOMBRE HEURES DE COURS PAR SEMAINE = " . $eleve['heures_semaine']), 0, 1);

        $fpdf->Ln(5);

        // Tableau des cours
        $fpdf->SetFont('Arial', 'B', 8);
        $fpdf->Cell(30, 10, 'Horaires', 1, 0, 'C');
        foreach ($jours as $jour) {
            $fpdf->Cell(30, 10, utf8_decode($jour->name), 1, 0, 'C');
        }
        $fpdf->Ln();

        $fpdf->SetFont('Arial', '', 9);
        foreach ($creneaux as $creneau) {
            $debut = substr($creneau['debut'], 0, 5); // Garde uniquement hh:mm
            $fin = substr($creneau['fin'], 0, 5);     // Garde uniquement hh:mm

            $label = $debut . ' - ' . $fin;
            $fpdf->Cell(30, 10, $label, 1);

            foreach ($jours as $jourId => $jour) {
                $matiereText = '';

                foreach ($emplois as $emploi) {
                    if (
                        $emploi->jour_id == $jourId &&
                        $emploi->heure_debut == $creneau['debut'] &&
                        $emploi->heure_fin == $creneau['fin']
                    ) {
                        $matiereText = utf8_decode($emploi->matiere->name);
                        break;
                    }
                }

                $fpdf->Cell(30, 10, $matiereText, 1);
            }

            $fpdf->Ln();
        }


        // Sauvegarde
        $directory = public_path('emplois');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $filename = 'emploi_du_temps_' . $classe->id . '_' . time() . '.pdf';
        $savePath = $directory . '/' . $filename;

        $fpdf->Output('F', $savePath);
        $publicUrl = asset('emplois/' . $filename);

        return response()->json([
            'url' => $publicUrl
        ]);
    }
