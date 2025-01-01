<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AddMedicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;// 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   

        return [
            "scientific_name"=>["required","string"],
            "trade_name"=>["required","string",
        ], 
        "quantity"=>["required"],
        "price"=>["required"],
        "expiration_date"=>["required"],
        "classification"=>["required","string"],
        "manufacturer"=>["required","string"], 
        // new UniqueMedicationCombination(),
    ];
    }

    // public function messages(){
    //     return ["trade_name.unique"=>"bla bla "];
    // }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->all()[0]], 422));
    }
}
