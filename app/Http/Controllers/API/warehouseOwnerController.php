<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\WarehouseOwner;
use App\Rules\UniqueMedicationCombination;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Medication;
use App\Rules\OrderStatusRule;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class warehouseOwnerController extends Controller
{
////    event  boolean : ture          code each quarter  الجدولة (job) |  schudual (console )
////    event  listener laravel (tiger ||tiger )  (jobs) called in event 
////    
   
    public function authenticate(){


        try{
            request()->validate([ 
                'username'=>['required'],
                'password'=>['required'],
            ]);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error'=>$e->errors()],422);
        }

        $credentials=request()->only('username','password');
   
        try{

            if(Auth::guard("warehouseOwner")->attempt($credentials)){/// we have authenticated the user

                /// laravel's authentication system will store the authenticated user'information internally
                // request()->session()->regenerate(); this is a  Session regeneration 
                //  we dont need it in api request , Api authentication works using tokens, not session 
                $user =Auth::guard("warehouseOwner")->user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'msg'=>"successfully authenticated",
                    'token'=>$token,
                    "user"=>$user],201);
            }
      
        }
        catch(\Exception $e){
            return  response()->json(['error'=>"your credentials do not match with our records"],401);// unauthorized
        }
        return  response()->json(['error'=>"your credentials do not match with our records"],401);// unauthorized
        
    }
    public function update(){
        try{
            request()->validate([ 
                'current_username'=>['required','exists:warehouse_owners,username'],
                'current_password'=>[''],
                "new_username"=>['required','email'],
                'new_password'=>['required','min:3']
            ]);
            $owner=WarehouseOwner::where('username',request()->current_username)->first();
           if(!Hash::check(request()->current_password,$owner->password)){
               return response()->json(['error'=>'the password isn\'t correct  '],422);
            }
            
            $owner->username=request()->new_username;
            $owner->password=Hash::make(request()->new_password);
            $owner->save();
        
            return response()->json(['msg'=>'updated successfully'],200);

        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error'=>$e->errors()],422);
        }
        catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }


    }

    public function getMedications(){
        try{

            $user=Auth::user();
            $medications=$user->medications;
            return response()->json(["medications"=>$medications]);
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }

    }
    public function getExpiredMedications(){
        try{

            $user=Auth::user();
            $currentDate=date("Y-m-d"); 
            $expiredMedications=Medication::Where('warehouse_owner_id',$user->id)->Where("expiration_date","<=",$currentDate)->get();
            return response()->json(["expiredMedications"=>$expiredMedications]);
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }

    }
    public function getOrders(){
        try{

            $orders =Order::all();
            return response()->json(["orders"=>$orders],200);        
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
    public function addMedication(){

        try{

            $validatedAttributes=request()->validate([
                "scientific_name"=>["required","string",new UniqueMedicationCombination(request()->scientific_name,request()->trade_name)],
                "trade_name"=>["required","string"], 
                "quantity"=>["required",'numeric','min:1'],
                "price"=>["required",'numeric','min:0.00000001'],
                "expiration_date"=>["required",'date','after_or_equal:today'],
                "classification"=>["required","string"],
                "manufacturer"=>["required","string"], 
            ]);
            $warehouseOwner=Auth::user();
            
        
        
            $medication=  $warehouseOwner->medications()->create($validatedAttributes);
        
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['error'=>$e->getMessage()],422);
        }
        catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500); 
        }
            
  
        return response()->json(['msg'=>'Your medication has been created',"medication"=>$medication],201);
      
    }
    public function updatePay(Request $request,Order $order){

        $request->validate([ 
            'is_paid' => ['required', "boolean"],
        ]);
        

        $order->update(['is_paid' => $request->is_paid]);
        return response()->json(['message' => 'Order payment status updated successfully',"order"=>$order], 200);
  
    }   
    public function updateStatus(Request $request,Order $order){
        try{
            $request->validate([ 
                'status' => ['required', Rule::in(['receive','sent']),new OrderStatusRule($order)],
            ]);
        }    
        catch(ValidationException $e){// if any fail fire from OrderStatusRule , without catch error you will get 404
            return response()->json(['error'=>$e->getMessage()],422);
        }
        if($request->status==="sent"){
        
            $orderDetails=$order->ordersDetails;
            try{
                DB::beginTransaction();
                
                
                for($i=0;$i<count($orderDetails);++$i){
    
                $scientific_name=$orderDetails[$i]->scientific_name;
                $trade_name=$orderDetails[$i]->trade_name;
                $orderQuantity=$orderDetails[$i]->quantity;
                $medication = Medication::where('scientific_name', $scientific_name)->where('trade_name',$trade_name)->first();
                
    
                if($orderQuantity >$medication->quantity) {
                    DB::rollBack();
                    throw new \Exception("Insufficient  quantity for $medication->scientific_name - $medication->trade_name ");
                }  
    
                $medication->decrement('quantity',$orderDetails[$i]->quantity);
                }
    
                DB::commit();

                $order->save();
            }
            catch(\Exception $e){
                return response()->json(['error'=>$e->getMessage()],400);//bad request
            }
        }            

        $order->update(['status'=>$request["status"]]);
        return response()->json(['message' => 'Order  status updated successfully',"order"=>$order], 200);

    }
    public function getOrderDetails(Request $request,Order $order){
        return response()->json(["orderDetails"=>$order->ordersDetails]);

    }


}
