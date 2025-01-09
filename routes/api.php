<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PharmacyController;
use App\Http\Controllers\API\warehouseOwnerController;



Route::post('/pharmacy/register',[PharmacyController::class,'store']);
Route::post('/pharmacy/login',[PharmacyController::class,'authenticate']);
Route::controller(PharmacyController::class)->middleware('auth:sanctum')->group(function(){
    Route::post('/pharmacy/add-order',"createOrder");
    Route::get('/pharmacy/orders','getOrders');
    Route::post('/pharmacy/order',"getOrder");
    Route::get('/pharmacy/{pharmacy}/favorite/medications',"getFavoriteMedications");
    Route::post('/pharmacy/{pharmacy}/favorite/medication','toggleFavorite');
    Route::get('/pharmacy/medications','getMedications');
});


Route::post('/login',[warehouseOwnerController::class,'authenticate']);
Route::post('/update',[warehouseOwnerController::class,'update']);
Route::controller(warehouseOwnerController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/medication','getMedications');
    Route::get('/expiredMedication','getExpiredMedications');
    Route::post('/medication','addMedication');
    Route::get('/orders','getOrders');
    Route::get('/orders/{order}/orderDetails','getOrderDetails');
    Route::put('/orders/{order}/pay','updatePay');
    Route::put('/orders/{order}/status','updateStatus');
});

