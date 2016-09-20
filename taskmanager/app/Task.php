<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    public function subtasks(){
    	return $this->hasMany(Subtask::class, 'task');
    } 
    public function users(){
    	return $this->hasMany(UsersTask::class, 'task_id');
    }
    // public function users(){
    // 	return $this->hasManyThrough(User::class, UsersTask::class, 'user_id', 'id', 'task_id');
    // }
    public function loggedTimes(){
    	return $this->hasMany(LoggedTime::class, 'task');
    }
}
