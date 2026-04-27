<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class AccueilController extends Controller
{
    public function index()
    {
        $aeroports = Airport::all();
        return view('client.home', compact('aeroports'));
    }
}
