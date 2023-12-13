<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'summary',
        'image',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}