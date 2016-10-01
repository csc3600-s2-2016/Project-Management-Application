<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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
        'password', 'remember_token',
    ];


    // public function Tasks()
    // {
    //     return $this->hasMany('App\Task');
    // }

    public function loggedTimes(){
        return $this->hasMany(LoggedTime::class);
    }
    public function tasks(){
        return $this->belongsToMany(Task::class, 'usersTasks', "user_id", "task_id");
    }
}
