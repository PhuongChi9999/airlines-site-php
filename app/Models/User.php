<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['surname', 'name','email','password'];

    public function isAdmin(){
        return $this->is_admin;
    }
}
