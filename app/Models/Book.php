<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    protected $fillable = [
        'title',
        'author',
        'genre',
        'publisher',
        'is_available',
        'description'
    ];

}