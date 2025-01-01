<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WarehouseOwner;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignIdFor(WarehouseOwner::class);
            $table->string('scientific_name');
            $table->string('trade_name');
            $table->string('classification'); 
            $table->string('manufacturer');
            $table->unsignedInteger('quantity');
            $table->decimal('price',8,2);
            $table->date('expiration_date');
            $table->timestamps();

            $table->unique(['scientific_name', 'trade_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
