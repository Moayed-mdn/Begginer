<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\medication>
 */



class MedicationFactory extends Factory
{

    public $scientificNames = ['Aspirin', 'Ibuprofen', 'Paracetamol', 'Amoxicillin', 'Lisinopril', 'Metformin', 'Simvastatin', 'Omeprazole', 'Sertraline', 'Lorazepam','Aspirin123', 'Ibuprofen123', 'Paracetamol123','Metformin123', 'Simvastatin123', 'Omeprazole123'];
    public $tradeNames = ['Bayer Aspirin', 'Advil', 'Tylenol', 'Amoxil', 'Zestril', 'Glucophage', 'Zocor', 'Prilosec', 'Zoloft', 'Ativan','Bayer Aspirin123', 'Advil123', 'Tylenol123', 'Glucophage123', 'Zocor123', 'Prilosec123'];
    public $classifications = ['Analgesic', 'NSAID', 'Antipyretic', 'Antibiotic', 'Antihypertensive', 'Antidiabetic', 'Lipid-lowering', 'Proton Pump Inhibitor', 'Antidepressant', 'Benzodiazepine'];
    public $manufacturers = ['Bayer', 'Pfizer', 'Johnson & Johnson', 'Merck & Co.', 'GlaxoSmithKline', 'AstraZeneca', 'Novartis', 'Roche', 'AbbVie', 'Sanofi'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */



    public function definition(): array
    {

        

        return [
            "warehouse_owner_id"=>'1',
            "scientific_name"=>fake()->randomElement($this->scientificNames),
            "trade_name"=>fake()->randomElement($this->tradeNames),
            "classification"=>fake()->randomElement($this->classifications),
            "quantity"=>fake()->numberBetween(100,1000),
            "price"=>fake()->numberBetween(10,100),
            "manufacturer"=>fake()->randomElement($this->manufacturers),
            "expiration_date"=>fake()->date('y-m-d'),
        ];
    }
}
