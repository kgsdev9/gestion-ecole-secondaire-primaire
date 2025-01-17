<?php

use App\Http\Controllers\Categorie\CategoryController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Commandes\CommandeController;
use App\Http\Controllers\Depenses\DepensesController;
use App\Http\Controllers\Eleve\EleveController;
use App\Http\Controllers\EmploiDutemps\EmploiDuTempsController;
use App\Http\Controllers\Factures\FactureController;
use App\Http\Controllers\Factures\FacturePersonnaliseController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Impression\Facture\ImpressionFactureController;
use App\Http\Controllers\Pos\ProductPostController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Rapport\RapportController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Salle\AnneAcademiqueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Vente\VenteController;
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
Route::resource('/roles', RoleController::class);
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



Route::get('/emplois-du-temps', [EmploiDuTempsController::class, 'index']);
Route::get('/generatefactureVente/{codefacure}', [VenteController::class, 'generateFactureVente'])->name('facture.vente.generate');
Route::post('/rapport/vente/days', [VenteController::class, 'PrintAllVente'])->name('facture.vente.rapport');
