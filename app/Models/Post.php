<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // protected $fillable = [
    //     'title',
    //     'status',
    //     'content',
    //     'user_id',
    // ];

    protected $fillable = array('tittle', 'content', 'status', 'user_id');
}