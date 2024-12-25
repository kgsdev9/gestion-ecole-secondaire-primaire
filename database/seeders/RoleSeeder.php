<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['libellerole' => 'Super Admin'],
            ['libellerole' => 'utilisateur'],
            ['libellerole' => 'manager'],
        ]);
    }
}
