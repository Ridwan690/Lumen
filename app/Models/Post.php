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

    protected $fillable = array('title', 'status', 'content', 'image', 'user_id', 'author_name', 'is_featured');

    public $timestamps = true;

    // Relationship
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}