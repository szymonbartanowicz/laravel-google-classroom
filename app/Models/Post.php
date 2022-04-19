<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['body', 'lesson_id', 'user_id'];

    protected $hidden = ['lesson_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
