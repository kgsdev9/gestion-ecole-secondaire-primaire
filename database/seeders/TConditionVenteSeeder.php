<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TConditionVenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_conditon_vtes')->insert([
            ['libellecondtionvte'=> 'DAP'] ,
            ['libellecondtionvte'=> 'Comptant à la commande'] ,

        ]);
    }
}

