<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TFacture>
 */
class TFactureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = 'VP';
        $year = date('y');

        // Générer un identifiant unique
        $lastIdentifier = DB::table('t_factures')
            ->where('numvente', 'like', "{$type}-{$year}-%")
            ->orderBy('numvente', 'desc')
            ->value('numvente');

        if ($lastIdentifier) {
            $lastNumber = (int) substr($lastIdentifier, strrpos($lastIdentifier, '-') + 1);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        $numvente = "{$type}-{$year}-{$newNumber}";

        // Générer une date aléatoire pour 2019, 2023 ou 2024
        $years = ['2019', '2023', '2024'];
        $randomYear = $this->faker->randomElement($years);
        $startDate = "{$randomYear}-01-01";
        $endDate = "{$randomYear}-12-31";

        return [
            'numvente' => $numvente,
            'adresse' => $this->faker->address(),
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'montantht' => $this->faker->randomFloat(2, 100, 10000), // HT aléatoire entre 100 et 10,000
            'montanttva' => $this->faker->randomFloat(2, 10, 2000), // TVA aléatoire entre 10 et 2,000
            'tvafacture' => $this->faker->boolean(), // Booléen pour indiquer si la TVA est incluse
            'montantttc' => $this->faker->randomFloat(2, 110, 12000), // TTC aléatoire
            'telephone' => $this->faker->phoneNumber(),
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate), // Date aléatoire
            'updated_at' => now(),
        ];
    }
}
