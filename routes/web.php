<?php

use App\Http\Controllers\Bulletin\BulletinConroller;
use App\Http\Controllers\Categorie\CategoryController;
use App\Http\Controllers\Classe\AffectionAcademiqueController;
use App\Http\Controllers\Classe\ClasseController;
use App\Http\Controllers\Commandes\CommandeController;
use App\Http\Controllers\Depenses\DepensesController;
use App\Http\Controllers\Eleve\EleveController;
use App\Http\Controllers\EmploiDutemps\EmploiDuTempsController;
use App\Http\Controllers\Enseignant\EnseignantController;
use App\Http\Controllers\Examen\ExamenController;
use App\Http\Controllers\Factures\FactureController;
use App\Http\Controllers\Factures\FacturePersonnaliseController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Impression\Facture\ImpressionFactureController;
use App\Http\Controllers\Inscription\InscriptionController;
use App\Http\Controllers\Matiere\MatiereController;
use App\Http\Controllers\Niveau\NiveauController;
use App\Http\Controllers\Note\NoteController;
use App\Http\Controllers\Pos\ProductPostController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Rapport\RapportController;
use App\Http\Controllers\Rapport\SuiviVersement;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Salle\AnneAcademiqueController;
use App\Http\Controllers\Salle\SalleController;
use App\Http\Controllers\Scolarite\ScolariteController;
use App\Http\Controllers\Semestre\SemestreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Vente\VenteController;
use App\Http\Controllers\Versement\VersementController;
use App\Models\EmploiDuTemps;
use Illuminate\Support\Facades\Route;

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
Route::resource('/anneeacademique', AnneAcademiqueController::class);
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
Route::resource('/affectionacademique', AffectionAcademiqueController::class);
Route::resource('/inscription', InscriptionController::class);
Route::resource('/notes', NoteController::class);
Route::resource('/examens', ExamenController::class);
Route::resource('/emplois-du-temps', EmploiDuTempsController::class);
Route::resource('/versements', VersementController::class);
Route::resource('/bulletin', BulletinConroller::class);
Route::resource('/suiviversement', SuiviVeX1rsement::class);



Route::resource('/rapport', RapportController::class);
Route::resource('/depenses', DepensesController::class);
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
