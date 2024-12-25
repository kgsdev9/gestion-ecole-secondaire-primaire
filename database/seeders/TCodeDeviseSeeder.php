<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TCodeDeviseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_code_devises')->insert([
            ['libellecodedevise'=> 'CFA'] ,
            ['libellecodedevise'=> 'EURO'] ,
            ['libellecodedevise'=> 'DOLLAR'] ,
        ]);
    }
}
