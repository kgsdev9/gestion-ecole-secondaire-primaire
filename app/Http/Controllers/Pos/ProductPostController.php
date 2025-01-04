<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\ModeReglemnt;
use App\Models\Serveur;
use App\Models\TabRestaurant;
use App\Models\TCategorieProduct;
use App\Models\TProduct;
use Illuminate\Http\Request;

class ProductPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProducts()
    {
        // $listeProducts = TProduct::all();

        $query = TProduct::with('category')->orderByDesc('created_at');
        $listecategories = TCategorieProduct::all();
        $listeProducts = $query->get();
        $listetabrestaurant  = TabRestaurant::all();

        $listemodereglement  = ModeReglemnt::all();

        $listeserveurs  = Serveur::all();
        // Ajouter l'URL de l'image à chaque produit
        $listeProducts->each(function ($product) {
            // Assurez-vous que l'URL est construite correctement sans doublon
            $product->image_url = $product->image
                ? asset('s3/' . $product->image) // Utilisation correcte du disque local
                : asset('defaultimage.webp');     // Image par défaut
        });


        return view('Pos.products.gridproduct', compact('listeProducts', 'listecategories', 'listemodereglement', 'listetabrestaurant', 'listeserveurs'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
