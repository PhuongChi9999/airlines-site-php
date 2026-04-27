<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'type'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class)->withPivot('is_booker', 'seat_class');
    }

}
