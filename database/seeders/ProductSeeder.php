<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_products')->insert([
            [
                'libelleproduct' => 'Ordinateur HP Pavilion 15',
                'prixachat' => '450',
                'prixvente' => '600',
                'codeproduct' => 'HP-PAV-15',
                'qtedisponible' => '50',
                'tcategorieproduct_id' => 1 // ID correspondant à la catégorie "Ordinateurs"
            ],
            [
                'libelleproduct' => 'Ordinateur Dell XPS 13',
                'prixachat' => '650',
                'prixvente' => '850',
                'codeproduct' => 'DELL-XPS13',
                'qtedisponible' => '30',
                'tcategorieproduct_id' => 1
            ],
            [
                'libelleproduct' => 'Tablette Samsung Galaxy Tab S6',
                'prixachat' => '300',
                'prixvente' => '400',
                'codeproduct' => 'SAMSUNG-TAB-S6',
                'qtedisponible' => '40',
                'tcategorieproduct_id' => 2 // ID correspondant à la catégorie "Tablettes"
            ],
            [
                'libelleproduct' => 'Tablette Apple iPad Pro 11',
                'prixachat' => '600',
                'prixvente' => '800',
                'codeproduct' => 'APPLE-IPAD-PRO-11',
                'qtedisponible' => '25',
                'tcategorieproduct_id' => 2
            ],
            [
                'libelleproduct' => 'Smartphone Samsung Galaxy S23',
                'prixachat' => '700',
                'prixvente' => '900',
                'codeproduct' => 'SAMSUNG-S23',
                'qtedisponible' => '60',
                'tcategorieproduct_id' => 3 // ID correspondant à la catégorie "Téléphone"
            ],
            [
                'libelleproduct' => 'Smartphone iPhone 15',
                'prixachat' => '900',
                'prixvente' => '1100',
                'codeproduct' => 'APPLE-IPHONE-15',
                'qtedisponible' => '45',
                'tcategorieproduct_id' => 3
            ],
            [
                'libelleproduct' => 'Casque Sony WH-1000XM5',
                'prixachat' => '300',
                'prixvente' => '400',
                'codeproduct' => 'SONY-WH-1000XM5',
                'qtedisponible' => '35',
                'tcategorieproduct_id' => 4 // ID correspondant à la catégorie "Accessoires Téléphoniques"
            ],
            [
                'libelleproduct' => 'Casque Bose QuietComfort 45',
                'prixachat' => '350',
                'prixvente' => '450',
                'codeproduct' => 'BOSE-QC45',
                'qtedisponible' => '30',
                'tcategorieproduct_id' => 4
            ],
            [
                'libelleproduct' => 'Montre Garmin Forerunner 945',
                'prixachat' => '500',
                'prixvente' => '650',
                'codeproduct' => 'GARMIN-FORERUNNER-945',
                'qtedisponible' => '20',
                'tcategorieproduct_id' => 5 // ID correspondant à la catégorie "Smartwatches"
            ],
            [
                'libelleproduct' => 'Montre Apple Watch Series 8',
                'prixachat' => '550',
                'prixvente' => '700',
                'codeproduct' => 'APPLE-WATCH-SERIES-8',
                'qtedisponible' => '18',
                'tcategorieproduct_id' => 5
            ],
            [
                'libelleproduct' => 'Appareil Photo Canon EOS 90D',
                'prixachat' => '800',
                'prixvente' => '1000',
                'codeproduct' => 'CANON-EOS-90D',
                'qtedisponible' => '15',
                'tcategorieproduct_id' => 6 // ID correspondant à la catégorie "Appareils Photo"
            ],
            [
                'libelleproduct' => 'Caméra GoPro HERO10 Black',
                'prixachat' => '450',
                'prixvente' => '600',
                'codeproduct' => 'GOPRO-HERO10',
                'qtedisponible' => '25',
                'tcategorieproduct_id' => 7 // ID correspondant à la catégorie "Caméras de Sécurité"
            ],
            [
                'libelleproduct' => 'Machine à laver Samsung WW90T554DAX',
                'prixachat' => '400',
                'prixvente' => '500',
                'codeproduct' => 'SAMSUNG-WW90T554DAX',
                'qtedisponible' => '40',
                'tcategorieproduct_id' => 8 // ID correspondant à la catégorie "Électroniques Domestiques"
            ],
            [
                'libelleproduct' => 'Réfrigérateur LG GSX961NEAZ',
                'prixachat' => '1200',
                'prixvente' => '1500',
                'codeproduct' => 'LG-GSX961NEAZ',
                'qtedisponible' => '10',
                'tcategorieproduct_id' => 8
            ],
            [
                'libelleproduct' => 'Chaise de bureau IKEA Markus',
                'prixachat' => '150',
                'prixvente' => '250',
                'codeproduct' => 'IKEA-MARKUS',
                'qtedisponible' => '30',
                'tcategorieproduct_id' => 9 // ID correspondant à la catégorie "Meubles"
            ],
            [
                'libelleproduct' => 'Canapé convertible IKEA Friheten',
                'prixachat' => '450',
                'prixvente' => '600',
                'codeproduct' => 'IKEA-FRIHETEN',
                'qtedisponible' => '20',
                'tcategorieproduct_id' => 9
            ],
            [
                'libelleproduct' => 'T-shirt Nike Air',
                'prixachat' => '20',
                'prixvente' => '30',
                'codeproduct' => 'NIKE-AIR-T-shirt',
                'qtedisponible' => '100',
                'tcategorieproduct_id' => 10 // ID correspondant à la catégorie "Vêtements"
            ],
            [
                'libelleproduct' => 'Jeans Levi\'s 501',
                'prixachat' => '40',
                'prixvente' => '60',
                'codeproduct' => 'LEVIS-501',
                'qtedisponible' => '80',
                'tcategorieproduct_id' => 10
            ],
            [
                'libelleproduct' => 'Sac à main Gucci GG Marmont',
                'prixachat' => '800',
                'prixvente' => '1000',
                'codeproduct' => 'GUCCI-GG-MARMONT',
                'qtedisponible' => '15',
                'tcategorieproduct_id' => 11 // ID correspondant à la catégorie "Accessoires de Mode"
            ]
        ]);

    }
}
