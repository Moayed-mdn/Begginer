<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Medication;
use App\Models\FavoriteMedication;
use App\Rules\SyrianPhoneNumber;
class PharmacyController extends Controller
{
    

    public function store(Request $request){

        try{
            $validator= request()->validate([ 
                'phone_number'=>['required'," numeric",'unique:pharmacies,phone_number',new SyrianPhoneNumber], 
                'password'=>['required',"string",'min:3','confirmed'],
                'fcm_token'=>['nullable','string'],
            ],[
                "phone_number.required"=>"phone number is required "#(just for learning)
            ]);
      
            $validator['password']=bcrypt(request('password'));

            $pharmacy =  Pharmacy::create($validator);
            $token=  $pharmacy->createToken('MyApp')->plainTextToken;
            

        return response()->json(["msg"=>"Pharmacy successfully created","user"=>$pharmacy,"token"=>$token],201);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(["error"=>$e->errors()],422);
        }
        catch(\Exception $e){
            return response()->json(['msgg'=>$e->getMessage()],500);
        }
    }
    public function authenticate(){


        try{
            request()->validate([ 
                'phone_number'=>['required'],
                'password'=>['required'],
            ]);
        }
        catch(\Illuminate\Validation\ValidationException $e){
            return response()->json(['msg'=>$e->errors()],422);
        }

        $credentials=request()->only('phone_number','password');
   
        try{


            if(Auth::guard('pharmacy')->attempt($credentials)){/// we have authenticated the user

                /// laravel's authentication system will store the authenticated user'information internally
                // request()->session()->regenerate(); this is a  Session regeneration 
                //  we dont need it in api request , Api authentication works using tokens, not session 
                $user =Auth::guard("pharmacy")->user();
                $token = $user->createToken('auth_token')->plainTextToken;//Undefined method 'createToken'

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

    public function createOrder(Request $request)
    {
        
        $request->validate([
            "is_paid"=>['nullable'],
            "status"=>['nullable'],
            "medicationIdWithQuantity"=>"required"
        ]);
        
      


        try {
            
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'you are working on  laravel 11 , it has some trouble , dont use guard'], 500); 
            }

            $request["pharmacy_id"]=$user->id;

            return $user->createOrder($request);
           

            return response()->json(['message' => 'order created successfully', 'order' => $user->orders->find($order->id)], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500); // Generic error
        }
    }
    
    public function toggleFavorite(Request $request){
        $pharmacy_id=Auth::guard("pharmacy")->user()->id;
        if(!$pharmacy_id){
            return response()->json(['error'=>"dont use guard there is a problem in laravel 11 "],500);
        }

        $request->validate([
            "medication_id"=>"required"
        ]);


        $medication=Medication::find($request->medication_id);
        if(!$medication){
            return response()->json(['error'=>"some thing wrong happened "],500);
        }
        $isAlreadyFavorite=FavoriteMedication::where(['medication_id'=>$medication->id,"pharmacy_id"=>$pharmacy_id])->exists();
        if($isAlreadyFavorite){
            try{
                FavoriteMedication::where(['medication_id'=>$medication->id,"pharmacy_id"=>$pharmacy_id])->delete();
              }
              catch(\Exception $e){
                return response()->json(['error'=>"some thing wrong happened "],500);
              }
                  
            return response()->json(['msg'=>'remove it successfully'],201);
        }
        //else
        $create=FavoriteMedication::create([
            "pharmacy_id"=>$pharmacy_id,
            "medication_id"=>$medication->id
        ]);
        if(!$create){
            return response()->json(['error'=>"some thing wrong happened "],500);
        }
            
        return response()->json(['msg'=>"added it  to favorites successfully"],201);
    }


    public function getOrders(Request $request){
        
        $pharmacy=Auth::guard('pharmacy')->user();
        if(!$pharmacy)
            return response()->json(['error'=>'dont use guard, there is a  problem in laravel 11'],500);

        $orders=$pharmacy->orders->get();
        
        return response()->json(["orders"=>$orders],200);

    }

    public function getOrder(Request $request){

        $request->validate([
            'order_id'=>'required|exists:orders,id',
        ]);

        try{
            $order= Order::findOrFail($request->order_id);

            return response()->json(["order"=>$order],200);

        }
        catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(["error"=>"order not found"],404);
        }
        catch(\Exception $e){ 
            return response()->json(['msg'=>"an unexpected error occurred"],500);
        }
    }

    public function getFavoriteMedications(Request $request){

        $pharmacy=Auth::guard("pharmacy")->user;

        if(!$pharmacy)
            return response()->json(["error"=>"dont use guard, there is an error in laravel 11"],500);

        $favoriteMedications=$pharmacy->favoriteMedications();

        return response()->json(['favoriteMedications'=>$favoriteMedications],200);

    }
    

    public function getMedications(){
        $medications=Medication::all();
        return response()->json(['medications'=>$medications]);

    }


    public function testToken(){
        $pharmacy=Auth::user();
        if($pharmacy)
            return response()->json(['msg'=>"ok"],200);
        
        return response()->json(["error"=>"unAuthorized"],401);
    }


}