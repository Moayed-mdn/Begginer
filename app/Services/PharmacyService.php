<?php

namespace App\Services;

use App\Models\Pharmacy; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Medication;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Request;

class PharmacyService
{
    protected $firebaseService;
    
    public function __construct(){
        $firebaseService=new FirebaseService;
        $this->firebaseService=$firebaseService;
    }

   

    public function createOrder($request){

        // who pharmacy ? it is the owner of this sesssion
        // $pharmacy=Auth::guard('pharmacy')->user();

        $pharmacy_id=$request["pharmacy_id"];
        $medicationsIdWithQuantity=$request["medicationIdWithQuantity"];
        try{
            DB::transaction(function () use ($medicationsIdWithQuantity,$pharmacy_id){

                $order = new Order();
                $order->pharmacy_id = $pharmacy_id;
                // order's statue and order's is_paid will let them default 
                $order->save();
                foreach ($medicationsIdWithQuantity as $validateAttribute) {

                    $medication = Medication::find($validateAttribute["medicationId"]);
        
                    if(!$medication)
                        throw new \Exception('some HHHvthing wrong happened');
                        
                    
                    $quantity = $validateAttribute["quantity"];
                    
                    if ($quantity > $medication->quantity) {
                        throw new \Exception("created unsuccessfully completed,the entered quantity for  {$medication['trade_name']} is   larger than the available quantity");// response()->json(['success'=>false,'message' => "created unsuccessfully completed,the entered quantity for  {$medication['trade_name']} is   larger than the available quantity"], 500 ); 

                    }


                    $orderDetail = new OrderDetails();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->scientific_name = $medication->scientific_name;
                    $orderDetail->trade_name = $medication->trade_name;
                    $orderDetail->quantity = $quantity;

                    $orderDetail->save();
                    $medication->quantity -= $quantity;
                    $medication->save();
                }

            });
        }
        catch(\Exception $e){
            
            $message="created unsuccessfully completed,something wrong happened";
            if($e->getCode()==0)
                $message=$e->getMessage();
            return response()->json(["message"=>$message,"line"=>$e->getLine()],500);
                
        }
        /// noteification to warehouse_owner
        $fcm_token=Medication::find($medicationsIdWithQuantity[0]["medicationId"])->warehouseOwner->fcm_token;
        if($fcm_token) 
            $this->firebaseService->sendNotification("Warehouse","warehouse",$fcm_token);
        
        return response()->json(['message' => 'created successfully'], 201 );

    }
}



