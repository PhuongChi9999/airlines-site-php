<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Passenger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PassengerAuthController extends Controller
{


public function showRegister() {
    return view('auth.passenger_register');
}

public function register(Request $request) {
    $request->validate([
        'username' => 'required|unique:passengers',
        'password' => 'required|min:6',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email'
    ]);

    $passenger = Passenger::create([
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
    ]);

    Auth::guard('passenger')->login($passenger);

    return redirect('/flights');
}

}



