<?php

namespace App\Repository;

use App\Interfaces\FlightsRepositoryInterface;
use App\Models\Flights;

class FlightsRepository implements FlightsRepositoryInterface {
	public function getAll() {
		return Flights::all();
	}
}