<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    public function airportDepart() {
        return $this->belongsTo(Airport::class, 'aeroport_depart_id');
    }

    public function airportArrivee() {
        return $this->belongsTo(Airport::class, 'aeroport_arrivee_id');
    }

    public function passengers() {
        return $this->hasMany(Passenger::class);
    }
}

