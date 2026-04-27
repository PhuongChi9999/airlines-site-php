<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique();

            $table->foreignId('departure_airport_id')
									->constrained('airports')
									->onDelete('cascade');
									
            $table->foreignId('arrival_airport_id')
									->constrained('airports')
									->onDelete('cascade');

            $table->dateTime('departure_date');
            $table->dateTime('arrival_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
