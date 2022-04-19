<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['user_id', 'key', 'name', 'subject', 'number', 'section', 'background'];

    protected $hidden = ['pivot'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('role', 'created');
    }

    public function teachers()
    {
        return $this->belongsToMany('App\User')->wherePivot('role', 'teacher')->wherePivot('created', false)->withPivot('role', 'created');
    }

    public function students()
    {
        return $this->belongsToMany('App\User')->wherePivot('role', 'student')->withPivot('role', 'created');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }
}
