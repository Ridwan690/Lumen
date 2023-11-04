<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $fillable = ['name', 'email', 'username', 'password', 'address', 'phone_number'];

    public $timestamps = true;
}