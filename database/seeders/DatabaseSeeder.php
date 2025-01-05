<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        // \App\Models\TFacture::factory(100)->create();
        $this->call(CategorieProductSeeder::class);
        // $this->call(ProductSeeder::class);
        \App\Models\User::factory(300)->create();
        $this->call(RoleSeeder::class);
        $this->call(ModeReglementSeeder::class);
        $this->call(RegimeFiscalSeeder::class);
        $this->call(TCodeDeviseSeeder::class);
        $this->call(RegimeFiscalSeeder::class);
        \App\Models\User::factory(30)->create();
        \App\Models\TClient::factory(100)->create();
        $this->call(ModeReglementSeeder::class);
    }
}
