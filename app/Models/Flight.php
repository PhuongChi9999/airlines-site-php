<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FlightSeat;


class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_date',
        'arrival_date',
        'available_seats'
    ];

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }    

    public function seats()
    {
        return $this->hasMany(FlightSeat::class);
    }

}
