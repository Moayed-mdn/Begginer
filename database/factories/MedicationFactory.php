<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class MedicationFactory extends Factory
{
    public $scientificNames = ['Aspirin', 'Ibuprofen', 'Paracetamol', 'Amoxicillin', 'Lisinopril', 'Metformin', 'Simvastatin', 'Omeprazole', 'Sertraline', 'Lorazepam', 'Aspirin123', 'Ibuprofen123', 'Paracetamol123', 'Metformin123', 'Simvastatin123', 'Omeprazole123'];
    public $tradeNames = ['Bayer Aspirin', 'Advil', 'Tylenol', 'Amoxil', 'Zestril', 'Glucophage', 'Zocor', 'Prilosec', 'Zoloft', 'Ativan', 'Bayer Aspirin123', 'Advil123', 'Tylenol123', 'Glucophage123', 'Zocor123', 'Prilosec123'];
    public $classifications = ['Analgesic', 'NSAID', 'Antipyretic', 'Antibiotic', 'Antihypertensive', 'Antidiabetic', 'Lipid-lowering', 'Proton Pump Inhibitor', 'Antidepressant', 'Benzodiazepine'];
    public $manufacturers = ['Bayer', 'Pfizer', 'Johnson & Johnson', 'Merck & Co.', 'GlaxoSmithKline', 'AstraZeneca', 'Novartis', 'Roche', 'AbbVie', 'Sanofi'];

    public function definition(): array
    {
        $uniqueNames = $this->getUniqueNames();

        return [
            "warehouse_owner_id" => '1',
            "scientific_name" => $uniqueNames['scientific_name'],
            "trade_name" => $uniqueNames['trade_name'],
            "classification" => fake()->randomElement($this->classifications),
            "quantity" => fake()->numberBetween(100, 1000),
            "price" => fake()->randomFloat(2, 10, 100),
            "manufacturer" => fake()->randomElement($this->manufacturers),
            "expiration_date" => fake()->date('y-m-d'),
        ];
    }

    private function getUniqueNames(): array
    {
        do {
            $scientificName = fake()->randomElement($this->scientificNames);
            $tradeName = fake()->randomElement($this->tradeNames);
            $names = ['scientific_name' => $scientificName, 'trade_name' => $tradeName];
            $medications = DB::table("medications")->select('scientific_name', 'trade_name')->get();

            $isDuplicate = $medications->contains(function ($medication) use ($names) {
                return $medication->scientific_name === $names['scientific_name'] && $medication->trade_name === $names['trade_name'];
            });


        } while ($isDuplicate);

        return $names;
    }
}
