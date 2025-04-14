<?php

use App\Http\Controllers\AirportsController;
use App\Http\Controllers\FlightsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PassengerAuthController;


Route::get('/', function () {
	return view('main-page');
});

Route::get('airports', [AirportsController::class, 'index']);
Route::get('flights', [FlightsController::class, 'index']);




Route::get('/register', [PassengerAuthController::class, 'showRegister']);
Route::post('/register', [PassengerAuthController::class, 'register']);

Route::get('/login', [PassengerAuthController::class, 'showLogin']);
Route::post('/login', [PassengerAuthController::class, 'login']);
Route::post('/logout', [PassengerAuthController::class, 'logout']);
