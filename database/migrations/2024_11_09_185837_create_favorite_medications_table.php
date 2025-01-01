<?php

use App\Models\Medication;
use App\Models\Pharmacy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorite_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pharmacy::class);
            $table->foreignIdFor(Medication::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_medications');
    }
};
