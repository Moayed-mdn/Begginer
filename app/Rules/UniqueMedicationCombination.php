<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
class UniqueMedicationCombination implements ValidationRule
{
    public $scientificName;
    public $tradeName;
    public function __construct( $scientificName,$tradeName) {
        $this->scientificName = $scientificName;
        $this->tradeName = $tradeName;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       $count=DB::table('medications')->where('scientific_name',$this->scientificName)->where('trade_name',$this->tradeName)->count();

        if ($count > 0) {
            $fail('The combination of scientific name and trade name already exists.');
        }
    }
}
