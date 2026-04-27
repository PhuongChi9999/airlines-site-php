<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\FlightsController;
use App\Http\Controllers\Admin\AirportsController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\UserController;

//  Active les routes /login, /register, /logout
Auth::routes();

//  PAGE PRINCIPALE CLIENT – Redirection
Route::get('/', [AccueilController::class, 'index'])->name('home');
Route::get('/recherche', [BookingController::class, 'recherche'])->name('recherche');

// -------------------- CLIENT (accessible sans login) --------------------
Route::get('/flights', [BookingController::class, 'index'])->name('client.flights.index');
Route::post('/flights', [BookingController::class, 'store'])->name('flights.book');
Route::get('/flights/confirmation', [BookingController::class, 'confirmation'])->name('flights.confirmation');

Route::get('/flights/fill-form', [BookingController::class, 'fillForm'])->name('flights.fillForm');
Route::get('/cart', [CartController::class, 'index'])->name('client.cart.index');
Route::post('/cart/confirm', [CartController::class, 'confirm'])->name('client.cart.confirm');
Route::get('/confirmation', [CartController::class, 'confirmation'])->name('client.cart.confirmation');

Route::delete('/cart/{booking}', [CartController::class, 'remove'])->name('client.cart.remove');
Route::get('/cart/edit/{booking}', [CartController::class, 'edit'])->name('cart.edit');
Route::put('/cart/update/{booking}', [CartController::class, 'update'])->name('cart.update');
Route::get('/my-booking', [BookingController::class, 'checkForm'])->name('booking.checkForm');
Route::post('/my-booking', [BookingController::class, 'check'])->name('booking.check');

// -------------------- ADMIN (protégé par auth) --------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // CRUD vols
    Route::resource('flights', FlightsController::class);

    // CRUD aéroports
    Route::resource('airports', AirportsController::class);

    // Dashboard admin
    Route::get('dashboard', [AdminsController::class, 'index'])->name('admin.dashboard');

    // Voir les passagers d’un vol spécifique
    Route::get('flights/{id}/passengers', [FlightsController::class, 'showPassengers'])->name('admin.flights.passengers');
});

Route::get('/my-account', [UserController::class, 'profile'])->middleware('auth')->name('user.profile');
Route::post('/my-account', [UserController::class, 'update'])->middleware('auth')->name('user.profile.update');
