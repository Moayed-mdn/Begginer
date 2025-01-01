<?php

namespace Database\Factories;

use App\Models\Medication;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FavoriteMedication>
 */
class FavoriteMedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "pharmacy_id"=>"1",
            "medication_id"=>"1",

        ];
    }
}
