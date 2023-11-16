<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    protected $fillable = [
        'title',
        'author',
        'genre',
        'user_id',
        'publisher',
        'is_available',
    ];

    public $timestamps = true;
}