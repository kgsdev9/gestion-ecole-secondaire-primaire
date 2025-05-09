<?php

namespace App\Http\Controllers\EmploiDutemps;

use App\Http\Controllers\Controller;
use App\Models\AffectionAcademique;
use App\Models\Classe;
use App\Models\EmploiDuTemps;
use App\Models\Jour;
use App\Models\Matiere;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\AnneeAcademiqueService;
use Codedge\Fpdf\Fpdf\Fpdf;

class EmploiDuTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }


    public function index()
    {
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        $classes = Classe::with(['niveau', 'anneeAcademique', 'salle'])
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->get();

        return view('emploidutemps.index', compact('classes'));
    }

    public function configurationEmploiTime($classeID)
    {
        $classe = Classe::findOrFail($classeID);
        $matieres = Matiere::all();
        $jours = Jour::all();
        $emplois =  EmploiDuTemps::with(['matiere', 'jour'])
            ->where('classe_id', $classeID)
            ->get();

        return view('emploidutemps.configuration', compact('classe', 'matieres', 'jours', 'emplois'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $classeId = $data['classe_id'];
        $lignes = $data['emplois'];
        $anneeScolaireActuelle = $this->anneeAcademiqueService->getAnneeActive();

        // Supprimer les anciens emplois du temps de cette classe pour l'année active
        EmploiDuTemps::where('classe_id', $classeId)
            ->where('anneeacademique_id', $anneeScolaireActuelle->id)
            ->delete();

        // Recréer les nouvelles lignes
        foreach ($lignes as $ligne) {
            EmploiDuTemps::create([
                'classe_id'   => $classeId,
                'matiere_id'  => $ligne['matiere_id'],
                'jour_id'     => $ligne['jour_id'],
                'heure_debut' => $ligne['heure_debut'],
                'heure_fin'   => $ligne['heure_fin'],
                'anneeacademique_id' => $anneeScolaireActuelle->id
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Emploi du temps mis à jour.']);
    }





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
        $fpdf->Cell(0, 5, utf8_decode("Année Scolaire : 2024-2025"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("NOM D'UNE ECOLE"), 0, 0, 'L');
        $fpdf->Cell(0, 5, utf8_decode("Code : 015012   Statut : Public"), 0, 1, 'R');
        $fpdf->Cell(130, 5, utf8_decode("Email : kgsinformatique@gmail.com"), 0, 1, 'L');

        $fpdf->Ln(5);

        // Titre centré
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Cell(0, 10, utf8_decode("EMPLOI DU TEMPS CLASSE : " . $classe->name), 1, 1, 'C');

        // // Infos élève
        // $fpdf->SetFont('Arial', '', 10);
        // $fpdf->Cell(90, 8, utf8_decode("NOM & PRENOMS : " . $eleve['nom']), 0);
        // $fpdf->Cell(60, 8, utf8_decode("MATRICULE : " . $eleve['matricule']), 0);
        // $fpdf->Cell(40, 8, utf8_decode("SEXE : " . $eleve['sexe']), 0, 1);

        // $fpdf->Cell(90, 8, utf8_decode("REGIME : " . $eleve['regime']), 0);
        // $fpdf->Cell(60, 8, utf8_decode("REDOUBLANT : " . $eleve['redoublant']), 0, 1);

        // $fpdf->Cell(130, 8, utf8_decode("PROFESSEUR PRINCIPAL : " . $eleve['prof_principal']), 0, 1);
        // $fpdf->Cell(130, 8, utf8_decode("EDUCATEUR : " . $eleve['educateur']), 0, 1);
        // $fpdf->Cell(130, 8, utf8_decode("NOMBRE HEURES DE COURS PAR SEMAINE = " . $eleve['heures_semaine']), 0, 1);

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

                $fpdf->Cell(30, 10, $matiereText, 1, 0, 'C');
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
