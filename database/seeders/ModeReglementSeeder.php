<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModeReglementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mode_reglemnts')->insert([
            ['libellemodereglement'=> 'Cheque'] ,
            ['libellemodereglement'=> 'Virement Bancaire'] ,
            ['libellemodereglement'=> 'En Espece'] ,
        ]);
    }
}
