<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminsController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->is_admin, 403);
        return view('admin.dashboard');
    }
    
}
