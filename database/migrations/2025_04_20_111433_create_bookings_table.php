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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_id');
            $table->string('status')->default('cart');
            $table->decimal('totalPrice', 10, 2)->default(0);
            $table->timestamp('booked_at')->useCurrent();
            $table->timestamps();

            // Relations
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
