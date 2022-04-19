<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const STATUSES = ['online', 'away', 'busy', 'invisible'];

    public function status()
    {
        return $this->hasOne('App\Models\Status');
    }

//    public function createdLessons()
//    {
//        return $this->hasMany('App\Models\Lesson');
//    }

    public function lessons()
    {
        return $this->belongsToMany('App\Models\Lesson');
    }

    public function createdLessons()
    {
        return $this->belongsToMany('App\Models\Lesson')->wherePivot('created', true);
    }

    public function joinedLessons()
    {
        return $this->belongsToMany('App\Models\Lesson')->wherePivot('created', false);
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task');
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];
}
