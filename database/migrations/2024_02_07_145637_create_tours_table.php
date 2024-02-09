<?php

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
        Schema::create('api_tours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('travelId');
            $table->string('name');
            $table->date('startingDate');
            $table->date('endingDate');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('travelId')->references('id')->on('api_travels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tours');
    }
};
