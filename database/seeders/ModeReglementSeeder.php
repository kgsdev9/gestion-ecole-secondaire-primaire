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
            ['libellemodereglement'=> 'ORANGE MONEY'],
            ['libellemodereglement'=> 'WAVE'] ,
            ['libellemodereglement'=> 'MOOV MONEY'],
            ['libellemodereglement'=> 'MTN MONEY'],
            ['libellemodereglement'=> 'VISA CARD'],
            ['libellemodereglement'=> 'En Espece'] ,
            ['libellemodereglement'=> 'AFG BANK'] ,
        ]);
    }
}
