<?php

use App\Http\Controllers\Administration\Impressions\PrintListeClasseController;
use App\Http\Controllers\Administration\Impressions\PrintResultatSemestreController;
use App\Http\Controllers\Administration\PrintFicheInscriptionController;
use App\Http\Controllers\Administration\PrintVersementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Note\NoteController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Eleve\EleveController;
use App\Http\Controllers\Salle\SalleController;
use App\Http\Controllers\Vente\VenteController;
use App\Http\Controllers\Classe\ClasseController;
use App\Http\Controllers\Examen\ExamenController;
use App\Http\Controllers\Niveau\NiveauController;
use App\Http\Controllers\Examen\ResultatController;
use App\Http\Controllers\Matiere\MatiereController;
use App\Http\Controllers\Pos\ProductPostController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Rapport\RapportController;
use App\Http\Controllers\Bulletin\BulletinConroller;
use App\Http\Controllers\Examen\ParametreController;
use App\Http\Controllers\Factures\FactureController;
use App\Http\Controllers\Semestre\SemestreController;
use App\Http\Controllers\Categorie\CategoryController;
use App\Http\Controllers\Commandes\CommandeController;
use App\Http\Controllers\Examen\ConvocationController;
use App\Http\Controllers\Examen\PrintExamenController;
use App\Http\Controllers\Examen\RepartitionController;
use App\Http\Controllers\Scolarite\ScolariteController;
use App\Http\Controllers\Versement\VersementController;
use App\Http\Controllers\Examen\MoyenneExamenController;
use App\Http\Controllers\Salle\AnneAcademiqueController;
use App\Http\Controllers\Enseignant\EnseignantController;
use App\Http\Controllers\Examen\ProgrammeExamenController;
use App\Http\Controllers\Inscription\InscriptionController;
use App\Http\Controllers\EmploiDutemps\EmploiDuTempsController;
use App\Http\Controllers\Factures\FacturePersonnaliseController;
use App\Http\Controllers\ConfigurationScolaire\NoteScolaireController;
use App\Http\Controllers\Impression\Facture\ImpressionFactureController;
use App\Http\Controllers\configurationScolaire\MoyenneScolaireController;
use App\Http\Controllers\ConfigurationScolaire\ResultatSemestreController;
use App\Http\Controllers\ConfigurationScolaire\RapportSemestreTrimestreController;
use App\Http\Controllers\Configuration\Convocation\ConvocationController as ConvocationConvocationController;
use App\Http\Controllers\Versement\SuiviVersementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/', [HomeController::class, 'index']);

Route::resource('/users', UserController::class);
Route::resource('/roles', RoleController::class);
Route::resource('/factures', FactureController::class);
Route::resource('/eleves', EleveController::class);
Route::resource('/product', ProductController::class);
Route::resource('/categories', CategoryController::class);
Route::resource('/ventes', VenteController::class);
Route::resource('/matieres', MatiereController::class);
Route::resource('/niveaux', NiveauController::class);
Route::resource('/salles', SalleController::class);
Route::resource('/enseignants', EnseignantController::class);
Route::resource('/scolarites', ScolariteController::class);
Route::resource('/classes', ClasseController::class);
Route::resource('/inscription', InscriptionController::class);
Route::resource('/notes', NoteController::class);
Route::resource('/versement', VersementController::class);
Route::resource('/bulletin', BulletinConroller::class);


Route::resource('/rapport', RapportController::class);
Route::resource('/commandes', CommandeController::class);
Route::resource('/devis', CommandeController::class);
Route::resource('/facturepersonnalite', FacturePersonnaliseController::class);
Route::get('/generatefacture/{codefacure}', [ImpressionFactureController::class, 'generateFacture'])->name('facture.generate');
Route::get('/generateRapport', [ImpressionFactureController::class, 'generateRapport'])->name('facture.rapport');


Route::get('/rapport', [RapportController::class, 'generateRapportForm'])->name('vente.rapport');
Route::post('/rapport/vente', [RapportController::class, 'generateRapport'])->name('rapport.vente');

Route::get('/post/ventes', [ProductPostController::class, 'allProducts'])->name('product.pos');
Route::post('/ventes/{vente}/validate', [VenteController::class, 'validatVente'])->name('ventes.validate');



Route::get('/generatefactureVente/{codefacure}', [VenteController::class, 'generateFactureVente'])->name('facture.vente.generate');
Route::post('/rapport/vente/days', [VenteController::class, 'PrintAllVente'])->name('facture.vente.rapport');
Route::get('/gestion/semestre/{id}', [SemestreController::class, 'gestionSemestre'])->name('gestion.semestre');
Route::post('/store', [SemestreController::class, 'store'])->name('semestre.store');
Route::post('/cloture-semestre', [SemestreController::class, 'toggleCloture'])->name('semestre.toggleCloture');
Route::post('/delete/semestre/{id}', [SemestreController::class, 'destroy'])->name('semestre.destroy');


Route::prefix('configurationnote')->name('configurationnote.')->group(function () {

    Route::get('/classe/anneeacademique', [NoteScolaireController::class, 'index'])->name('classe.anneeacademique');
    Route::get('/classe/note/by/classe/{id}', [NoteScolaireController::class, 'gestionNote'])->name('classe.gestion.note');
    Route::post('/note/create', [NoteScolaireController::class, 'addNote'])->name('create.gestion.note');
    Route::delete('/note/{note}', [NoteScolaireController::class, 'destroy'])->name('delete.gestion.note');
    Route::post('/validermoyennne/{id}', [NoteScolaireController::class, 'validerMoyenne'])->name('validate.matiere');
    Route::get('/rapport/semestre', [RapportSemestreTrimestreController::class, 'index'])->name('rapport.semestre');
    Route::post('/semestre/action', [RapportSemestreTrimestreController::class, 'actionSurSemestre'])->name('action.semestre');
    Route::resource('/resultat/semestre', ResultatSemestreController::class);

});


Route::prefix('gestionmoyenne')->name('gestionmoyenne.')->group(function () {
    Route::get('/moyenne', [MoyenneScolaireController::class, 'index'])->name('gestion.moyenne');
    Route::post('/print/bulletin', [MoyenneScolaireController::class, 'printBulletin'])->name('print.bulletin');
});


Route::prefix('examens')->name('examens.')->group(function () {
    Route::resource('/gestion', ExamenController::class);
    Route::resource('/programme', ProgrammeExamenController::class);
    Route::resource('/moyenne', MoyenneExamenController::class);
    Route::resource('/repartition', RepartitionController::class);
    Route::resource('/convocation', ConvocationController::class);
    Route::resource('/resultats', ResultatController::class)->except(['edit', 'store']);
    Route::get('/create/programme/examens/{id}', [ProgrammeExamenController::class, 'createProgrammeExamen'])->name('programme.examens.create');
    Route::get('/create/repartition/examens/{id}', [RepartitionController::class, 'createRepartition'])->name('repartition.examens.create');
    Route::get('/create/moyenne/examens/{id}', [MoyenneExamenController::class, 'createMoyenne'])->name('moyenne.examens.create');
    Route::get('/parametre/examen', [ParametreController::class, 'index'])->name('parametre.examens');
    Route::post('/action/examen', [ParametreController::class, 'executeExamAction'])->name('execute.action');
    Route::get('/print/resultat/{id}', [PrintExamenController::class, 'printresultatExam'])->name('print.examens');
    Route::post('/imprimer/repartition/examen', [PrintExamenController::class, 'printRepartitonExamen'])->name('impression.repartition.examen');
});



Route::prefix('administration')->name('administration.')->group(function () {
    Route::resource('/anneeacademique', AnneAcademiqueController::class);
    Route::post('/annneacademique/active', [AnneAcademiqueController::class, 'active'])->name('active.anneeacademique');
});



// systemes
Route::prefix('configuration')->name('configuration.')->group(function () {
    Route::get('/convocation/impression/examen', [ConvocationConvocationController::class, 'formConvocation'])->name('convocation.examen');
    Route::get('/historique', [ConvocationConvocationController::class, 'formConvocation'])->name('convocation.examen');
    Route::post('/print/convocation', [ConvocationConvocationController::class, 'imprimerConvocation'])->name('convocation.examens.print');
});



Route::prefix('administration')->name('administration.')->group(function () {
    Route::get('/print/fiche/inscription/{inscriptionId}', [PrintFicheInscriptionController::class, 'printFicheInscription'])->name('print.fiche.inscription');
    Route::resource('/suiviversement', SuiviVersementController::class);
    Route::get('/print/resultat/semestre/{resultatsemestreId}', [PrintResultatSemestreController::class, 'printResultatSemestre'])->name('print.resultat.semestre');
    Route::post('/imprimer/liste/classe', [PrintListeClasseController::class, 'printClasseListe'])->name('impression.classe');
    Route::post('/print/versement', [PrintVersementController::class, 'printVersement'])->name('impression.versement');
    Route::resource('/emplois-du-temps', EmploiDuTempsController::class);
    Route::get('/emploidutempsbyclasse/{classeId}', [EmploiDuTempsController::class, 'configurationEmploiTime'])->name('configuration.emploidutemp');
    Route::post('/printemploidutemps', [EmploiDuTempsController::class, 'printEmploiDuTemps'])->name('configuration.print.emploi.temps');
});

