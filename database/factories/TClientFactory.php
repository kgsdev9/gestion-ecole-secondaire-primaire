<?php

namespace Database\Factories;

use App\Models\TCodeDevise;
use App\Models\TRegimeFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

class TClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'codeclient' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{4}'), // Ex: ABC1234
            'libtiers' => $this->faker->company, // Nom de société
            'adressepostale' => $this->faker->optional()->address, // Adresse postale
            'adressegeo' => $this->faker->optional()->address, // Adresse géographique
            'fax' => $this->faker->optional()->phoneNumber, // Numéro de fax
            'email' => $this->faker->unique()->safeEmail, // Email unique
            'telephone' => $this->faker->optional()->phoneNumber, // Numéro de téléphone
            'numerocomtribuabe' => $this->faker->optional()->regexify('[A-Z0-9]{10}'), // Numéro fiscal
            'numerodecompte' => $this->faker->optional()->regexify('[0-9]{12}'), // Numéro de compte bancaire
            'capital' => $this->faker->optional()->randomFloat(2, 1000, 1000000), // Montant financier entre 1k et 1M
            'tregimefiscal_id' => TRegimeFiscal::inRandomOrder()->value('id'), // ID aléatoire depuis TRegimeFiscal
            'tcodedevise_id' => TCodeDevise::inRandomOrder()->value('id'), // ID aléatoire depuis TCodeDevise
        ];
    }
}
