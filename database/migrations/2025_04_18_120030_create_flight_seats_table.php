<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained('flights')->onDelete('cascade');
            $table->string('class');
            $table->string('code')->nullable();
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_seats');
    }
};
