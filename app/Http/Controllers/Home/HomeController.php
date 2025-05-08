<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Versement;
use App\Services\AnneeAcademiqueService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    protected $anneeAcademiqueService;
    public function __construct(AnneeAcademiqueService $anneeAcademiqueService)
    {
        $this->middleware('auth');
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }
    /**
     * Affichage de la page d'accueil.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::table('jours')->insert([
        //     'name'=> 'Mardi'
        // ]);
        // Récupérer l'année scolaire active
        $anneeScolaireActuelle  = $this->anneeAcademiqueService->getAnneeActive();

        // Extraire les années et les classes (en utilisant l'ID des classes)
        $elevesParAnneeParClasse = Eleve::selectRaw('anneeacademique_id, classe_id, count(*) as total')
            ->groupBy('anneeacademique_id', 'classe_id')
            ->get();

        // Extraire les années uniques
        $annees = $elevesParAnneeParClasse->pluck('anneeacademique_id')->unique();

        // Extraire les objets des classes, pas seulement leurs noms
        $classes = $elevesParAnneeParClasse->map(function ($eleve) {
            return $eleve->classe; // Ici, on retourne l'objet 'Classe', pas juste son nom
        })->unique('id'); // On assure que l'on garde uniquement des objets distincts par ID

        // Créer un tableau associant les années et les totaux des élèves par classe
        $data = [];
        foreach ($classes as $classe) {
            $data[$classe->name] = []; // Utiliser le nom de la classe
            foreach ($annees as $annee) {
                // Chercher les élèves pour chaque classe et année
                $data[$classe->name][$annee] = $elevesParAnneeParClasse
                    ->where('classe_id', $classe->id)
                    ->where('anneeacademique_id', $annee)
                    ->first()->total ?? 0;
            }
        }


        $versements = Versement::with(['typeVersement', 'eleve'])
        ->latest() // Trier par date, du plus récent au plus ancien
        ->limit(10) // Limiter aux 10 derniers versements
        ->get();

        $inscriptions = Inscription::with(['eleve', 'classe', 'niveau'])
        ->latest() // Trier par date d'inscription
        ->take(5) // Limiter à 5 dernières inscriptions
        ->get();


        // Passer les données à la vue
        return view('welcome', compact('anneeScolaireActuelle', 'classes', 'data', 'annees', 'versements', 'inscriptions'));
    }
}
