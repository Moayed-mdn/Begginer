<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $hidden=["updated_at","created_at"];//
    public function pharmacy(){
        return $this->belongsTo(Pharmacy::class);
    }

    public function ordersDetails(){
        return $this->hasMany(OrderDetails::class);
    }   

    public function warehouseOwner(){
        return $this->belongsTo(WarehouseOwner::class);
    }

    public function orders(){
        return $this->getAttributes();
    }

   




}
