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
        Schema::create('flights', function (Blueprint $table) {
					$table->id();
					$table->string('flight_number')->unique();
					$table->foreignId('departure_airport_id')->constrained('airports');
					$table->foreignId('arrival_airport_id')->constrained('airports');
					$table->dateTime('departure_date');
					$table->dateTime('arrival_date');
					$table->integer('available_seats');
					$table->decimal('price', 10, 2);
					$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
