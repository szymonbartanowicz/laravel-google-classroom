<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['lesson_id', 'title', 'instructions', 'points', 'access', 'deadline'];

    protected $hidden = ['pivot'];

    public function students()
    {
        return $this->belongsToMany('App\User');
    }

    public function scopeByAccess($query, $access)
    {
        return $query->where('access', $access);
    }
}
