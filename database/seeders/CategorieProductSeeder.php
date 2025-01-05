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
         
        ]);

    }
}
