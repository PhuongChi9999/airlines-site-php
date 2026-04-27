<?php

namespace App\Interfaces;

interface AirportsRepositoryInterface {
	public function getAll();
	public function getById($id);
}