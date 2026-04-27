<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('client.user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->input('first_name');
        $user->surname = $request->input('last_name');
        $user->email = $request->input('email');
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Profil mis à jour avec succès.');
    }
}

