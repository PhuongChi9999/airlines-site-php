<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Passenger extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username', 'password', 'first_name', 'last_name', 'email', 'flight_id'
    ];

    protected $hidden = ['password'];

    public function flight() {
        return $this->belongsTo(Flight::class);
    }
}
