<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['flight_id', 'status', 'totalPrice'];

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class)->withPivot('is_booker', 'seat_class');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
