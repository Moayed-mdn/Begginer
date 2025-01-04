<?php

namespace Database\Seeders;

use App\Models\Medication;
use App\Models\Order;
use App\Models\User;
use App\Models\WarehouseOwner;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        if($user= WarehouseOwner::find(1))
            $user->delete();

        WarehouseOwner::create(["id"=>"1",'username'=>"admin@gmail.com",'password'=>"123456"]);        
        Medication::factory(5)->create();
    }
}
