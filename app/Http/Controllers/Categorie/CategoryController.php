<?php

namespace App\Http\Controllers\Categorie;

use App\Http\Controllers\Controller;
use App\Models\TCategorieProduct;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

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
        $listecategorie = TCategorieProduct::all();
        return view('categories.index', compact('listecategorie'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Vérifier si product_id existe dans la requête
        $categoryId = $request->input('category_id');

        if ($categoryId) {
            // Si product_id existe, on modifie le produit
            $category = TCategorieProduct::find($categoryId);

            // Si le produit n'existe pas, le créer
            if (!$category) {
                // Créer un nouveau produit
                return $this->createProduct($request);
            }

            // Si le produit existe, procéder à la mise à jour
            return $this->updateProduct($category, $request);
        } else {
            // Si product_id est absent, on crée un nouveau produit
            return $this->createProduct($request);
        }
    }

    private function updateProduct($category, Request $request)
    {
        $category->update([
            'libellecategorieproduct' => $request->name,

        ]);
        return response()->json(['message' => 'Catégorie mis à jour avec succès', 'product' => $category], 200);
    }

    private function createProduct(Request $request)
    {

        // Création d'un nouveau produit
        $category = TCategorieProduct::create([
            'libellecategorieproduct' => $request->name,
        ]);


        return response()->json(['message' => 'Catégorie créé avec succès', 'product' => $category], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Rechercher le produit par ID
            $categorie = TCategorieProduct::findOrFail($id);

            // Supprimer le produit
            $categorie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé avec succès.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit introuvable.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du produit.',
            ], 500);
        }
    }
}
