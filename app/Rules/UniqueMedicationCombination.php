<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
// rule is deprecated 
class UniqueMedicationCombination implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        dd($value);

        $scientificName = $value['scientific_name'] ?? null; 
        $tradeName = $value['trade_name'] ?? null;   

        if (is_null($scientificName) || is_null($tradeName)) {
             $fail('Both scientific name and trade name are required.');
        
            }

        $count = DB::table('medications')
            ->where('scientific_name', $scientificName)
            ->where('trade_name', $tradeName)
            ->count();

        if ($count > 0) {
            $fail('The combination of scientific name and trade name already exists.');
        }
    }
}
