<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Services\PharmacyService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pharmacy extends Authenticatable
{
    use HasFactory,HasApiTokens;
    


    // public function __construct(PharmacyService $pharmacyService){
    //     $this->pharmacyService=$pharmacyService;
    // }
    
    
    public function orders():HasMany{
        return $this->hasMany(Order::class,"pharmacy_id");
    }   

    public function favoriteMedications() {
        return $this->belongsToMany(Medication::class, 'favorite_medications');
    }
    
    public function createOrder(Request $request) {
        // dd($request);
        // $validatedAttributes=$request->validate([
        //     "*.medicationId"=>['required',"integer","array"],// the  id  of the order
        //     "*.quantity"=>['required',"integer","array"]
        // ]);
        

     

        return  app(PharmacyService::class)->createOrder($request);
    }
}


