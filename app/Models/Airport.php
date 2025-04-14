<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;
    protected $table = 'airports';

    public function departures() {
        return $this->hasMany(Flight::class, 'aeroport_depart_id');
    }

    public function arrivals() {
        return $this->hasMany(Flight::class, 'aeroport_arrivee_id');
    }
}


