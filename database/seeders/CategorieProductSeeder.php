<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_categorie_products')->insert([
            ['libellecategorieproduct' => 'Téléphone'],
            ['libellecategorieproduct' => 'Ordinateurs'],
            ['libellecategorieproduct' => 'Tablettes'],
            ['libellecategorieproduct' => 'Accessoires Téléphoniques'],
            ['libellecategorieproduct' => 'Écouteurs / Casques'],
            ['libellecategorieproduct' => 'Smartwatches'],
            ['libellecategorieproduct' => 'Appareils Photo'],
            ['libellecategorieproduct' => 'Caméras de Sécurité'],
            ['libellecategorieproduct' => 'Électroniques Domestiques'],
            ['libellecategorieproduct' => 'Meubles'],
            ['libellecategorieproduct' => 'Vêtements'],
            ['libellecategorieproduct' => 'Chaussures'],
            ['libellecategorieproduct' => 'Accessoires de Mode'],
            ['libellecategorieproduct' => 'Bijoux'],
            ['libellecategorieproduct' => 'Montres'],
            ['libellecategorieproduct' => 'Cosmétiques'],
            ['libellecategorieproduct' => 'Parfums'],
            ['libellecategorieproduct' => 'Produits de Soin de la Peau'],
            ['libellecategorieproduct' => 'Produits Capillaires'],
            ['libellecategorieproduct' => 'Maquillage'],
            ['libellecategorieproduct' => 'Alimentation et Boissons'],
            ['libellecategorieproduct' => 'Vins et Spiritueux'],
            ['libellecategorieproduct' => 'Produits Bio'],
            ['libellecategorieproduct' => 'Équipement de Fitness'],
            ['libellecategorieproduct' => 'Vélos et Accessoires'],
            ['libellecategorieproduct' => 'Équipements de Camping'],
            ['libellecategorieproduct' => 'Jardin et Extérieur'],
            ['libellecategorieproduct' => 'Outils et Bricolage'],
            ['libellecategorieproduct' => 'Maison et Décoration'],
            ['libellecategorieproduct' => 'Produits pour Animaux'],
            ['libellecategorieproduct' => 'Livres'],
            ['libellecategorieproduct' => 'Musique'],
            ['libellecategorieproduct' => 'Films et Séries'],
            ['libellecategorieproduct' => 'Jeux Vidéo'],
            ['libellecategorieproduct' => 'Instruments de Musique'],
            ['libellecategorieproduct' => 'Sports et Loisirs'],
            ['libellecategorieproduct' => 'Bébé et Puériculture'],
            ['libellecategorieproduct' => 'Équipement de Voyage'],
            ['libellecategorieproduct' => 'Décoration de Noël'],
            ['libellecategorieproduct' => 'Mobilier de Bureau'],
            ['libellecategorieproduct' => 'Produits d\'entretien'],
            ['libellecategorieproduct' => 'Fournitures de Bureau'],
            ['libellecategorieproduct' => 'Accessoires pour Voiture'],
            ['libellecategorieproduct' => 'Pièces Détachées pour Voiture'],
        ]);

    }
}
