<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'class',
        'total_seats',
        'available_seats',
        'price',
        'code'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function bookedCount()
    {
        return $this->flight->passengers->where('seat_class', $this->class)->count();
    }

}
