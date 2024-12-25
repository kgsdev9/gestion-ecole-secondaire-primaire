<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TDepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_departements')->insert([
            ['libelledepartement'=> 'Commercial'] ,
            ['libelledepartement'=> 'Informatique'] ,
            ['libelledepartement'=> 'Transit'] ,
            ['libelledepartement'=> 'Ressource Humaine'] ,
        ]);
    }
}
