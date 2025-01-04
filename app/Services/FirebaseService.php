<?php

namespace App\Services; 
   

use App\Models\Table;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $messaging;
    public function __construct()
    {
        // $serviceAccountPath = storage_path('app/medicationswarehouse-firebase-adminsdk-ink.json');
        // $factory = (new Factory)->withServiceAccount($serviceAccountPath);
        // $this->messaging = $factory->createMessaging();
    }

   
    public function sendNotification($title, $body, $token, $data = [])
    {
        // $message = CloudMessage::withTarget('token', $token)
        //     ->withNotification(['title' => $title, 'body' => $body])
        //     ->withData($data);

        // try {
        //     $this->messaging->send($message);
        // } catch (\Kreait\Firebase\Exception\Messaging\InvalidMessage $e) {
        //     Log::warning("Invalid FCM token : $token - Error: " . $e->getMessage());
        //     return false;
        // } catch (\Exception $e) {
        //     Log::error(" Error in seending the notification  FCM token: $token - Error: " . $e->getMessage());
        //     return false;
        // }
    }
}