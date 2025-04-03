<?php

namespace App\Repository;

use App\Interfaces\AirportsRepositoryInterface;
use App\Models\Airports;

class AirportsRepository implements AirportsRepositoryInterface {
	public function getAll() {
		return Airports::all();
	}

	public function getById($id) {
		return Airports::find($id);
	}
}