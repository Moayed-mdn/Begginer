<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\orderDetails>
 */
class OrderDetailsFactory extends Factory
{


    public $scientificNames = ['Aspirin', 'Ibuprofen', 'Paracetamol', 'Amoxicillin', 'Lisinopril', 'Metformin', 'Simvastatin', 'Omeprazole', 'Sertraline', 'Lorazepam'];
    public $tradeNames = ['Bayer Aspirin', 'Advil', 'Tylenol', 'Amoxil', 'Zestril', 'Glucophage', 'Zocor', 'Prilosec', 'Zoloft', 'Ativan'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "order_id"=>"1",
            "scientific_name"=>fake()->randomElement($this->scientificNames),
            "trade_name"=>fake()->randomElement($this->tradeNames),
            "quantity"=>random_int(10,1000),
        ];
    }
}
// order 
///    
////    