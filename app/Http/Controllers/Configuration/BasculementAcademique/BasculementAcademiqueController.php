<?php

namespace App\Http\Controllers\Configuration\BasculementAcademique;
use App\Models\Eleve;
use App\Models\AnneeAcademique;
use App\Models\Classe;
use App\Models\Basculement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BasculementAcademiqueController extends Controller
{

    public function basculerEleves()
    {
        // Récupère l'année académique actuelle et l'année suivante
        $anneeAcademiqueActuelle = AnneeAcademique::where('nom', '2024-2025')->first();
        $anneeAcademiqueSuivante = AnneeAcademique::where('nom', '2025-2026')->first();

        // Récupère tous les élèves de l'année académique actuelle
        $eleves = Eleve::where('anneeacademique_id', $anneeAcademiqueActuelle->id)->get();

        foreach ($eleves as $eleve) {
            // Récupère la classe actuelle de l'élève
            $classeActuelle = $eleve->classe;

            // On détermine la classe suivante
            $classeSuivante = Classe::where('niveau_id', $classeActuelle->niveau_id)
                                    ->where('anneeacademique_id', $anneeAcademiqueSuivante->id)
                                    ->first();

            // Mise à jour de l'année académique et de la classe de l'élève
            $eleve->update([
                'anneeacademique_id' => $anneeAcademiqueSuivante->id,
                'classe_id' => $classeSuivante->id,
            ]);

            // Enregistrement du basculement dans la table `basculements`
            Basculement::create([
                'eleve_id' => $eleve->id,
                'ancienne_anneeacademique_id' => $anneeAcademiqueActuelle->id,
                'nouvelle_anneeacademique_id' => $anneeAcademiqueSuivante->id,
                'ancienne_classe_id' => $classeActuelle->id,
                'nouvelle_classe_id' => $classeSuivante->id,
            ]);
        }

        return response()->json(['message' => 'Basculement des élèves effectué avec succès']);
    }


    public function basculerElevess()
    {
        // Récupère l'année académique actuelle et l'année suivante
        $anneeAcademiqueActuelle = AnneeAcademique::where('nom', '2024-2025')->first();
        $anneeAcademiqueSuivante = AnneeAcademique::where('nom', '2025-2026')->first();

        // Récupère tous les élèves de l'année académique actuelle
        $eleves = Eleve::where('anneeacademique_id', $anneeAcademiqueActuelle->id)->get();

        foreach ($eleves as $eleve) {
            // Récupère la classe actuelle de l'élève
            $classeActuelle = $eleve->classe;

            // On détermine la classe suivante
            // Ici, on suppose que chaque classe passe directement à la classe suivante de manière linéaire.
            // Tu pourrais ajuster la logique selon tes règles (p.ex. passage automatique, examen, etc.)
            $classeSuivante = Classe::where('niveau_id', $classeActuelle->niveau_id)
                                    ->where('anneeacademique_id', $anneeAcademiqueSuivante->id)
                                    ->first();

            // Mise à jour de l'année académique et de la classe de l'élève
            $eleve->update([
                'anneeacademique_id' => $anneeAcademiqueSuivante->id,
                'classe_id' => $classeSuivante->id,
            ]);

            // Enregistrement du basculement dans la table `basculements`
            Basculement::create([
                'eleve_id' => $eleve->id,
                'ancienne_anneeacademique_id' => $anneeAcademiqueActuelle->id,
                'nouvelle_anneeacademique_id' => $anneeAcademiqueSuivante->id,
                'ancienne_classe_id' => $classeActuelle->id,
                'nouvelle_classe_id' => $classeSuivante->id,
            ]);
        }

        return response()->json(['message' => 'Basculement des élèves effectué avec succès']);
    }


//     use Jenssegers\Agent\Agent;

//     public function someAction()
//     {
//         $agent = new Agent();

//         // Récupérer les informations sur le téléphone
//         $device = $agent->device(); // Nom de l'appareil (ex. 'Pixel 4 XL')
//         $platform = $agent->platform(); // Système d'exploitation (ex. 'Android')
//         $browser = $agent->browser(); // Navigateur (ex. 'Chrome')
//         $version = $agent->version($browser); // Version du navigateur (ex. '89.0.4389.90')

//         // Afficher les informations
//         dd($device, $platform, $browser, $version);
//     }


//     use Jenssegers\Agent\Agent;
// use App\Models\UserAction;
// use Illuminate\Support\Facades\Auth;

// public function someAction()
// {
//     $agent = new Agent();

//     // Obtenir les informations sur le téléphone
//     $device = $agent->device(); // Nom de l'appareil
//     $platform = $agent->platform(); // Système d'exploitation (Android, iOS, etc.)
//     $browser = $agent->browser(); // Navigateur utilisé
//     $version = $agent->version($browser); // Version du navigateur

//     // Enregistrer l'action dans la table user_actions
//     UserAction::create([
//         'user_id' => Auth::id(),
//         'action' => 'Accès à une fonctionnalité',
//         'details' => 'L\'utilisateur a accédé à une fonctionnalité avec un appareil ' . $device,
//         'ip_address' => request()->ip(),
//         'user_agent' => request()->userAgent(),
//         'device_info' => 'Appareil: ' . $device . ', Système: ' . $platform . ', Navigateur: ' . $browser . ' ' . $version, // Stocker les infos détaillées
//     ]);
// }



}
