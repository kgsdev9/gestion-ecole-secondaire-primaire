<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimeFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_regime_fiscals')->insert([
            ['libelleregimefiscale'=> 'Régime de la Taxe sur la Valeur Ajoutée (TVA)'] ,
            ['libelleregimefiscale'=> 'Régime du Réel Normal'] ,
            ['libelleregimefiscale'=> 'Régime du Réel Simplifié'],
            ['libelleregimefiscale'=> 'Régime de la Microentreprise'] ,
            ['libelleregimefiscale'=> 'Régime de l\'Exportation'] ,
            ['libelleregimefiscale'=> 'Régime d\'Exonération Partielle ou Totale'] ,
            ['libelleregimefiscale'=> 'Régime des Zones Franches']
        ]);
    }
}
