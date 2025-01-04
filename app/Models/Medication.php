<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    /** @use HasFactory<\Database\Factories\MedicationFactory> */
    use HasFactory;

    public function warehouseOwner(){
       return  $this->belongsTo(WarehouseOwner::class);


    }

    public function pharmacies(){
        return $this->belongsToMany(Pharmacy::class,'favorite_medications');
    }


    public function getFilteredAttributes()  
    {
        return collect($this->getAttributes())->except(['warehouse_owner_id','created_at', 'updated_at']);
    }

    public function checkFav($id){

        return  FavoriteMedication::where(["pharmacy_id"=>$id,"medication_id"=>$this->id])->exists()? "exists":"";

    }


}
