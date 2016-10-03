<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Task extends Model
{

    public function subtasks(){
    	return $this->hasMany(Subtask::class, 'task');
    } 
    public function users(){
    	return $this->belongsToMany(User::class, 'usersTasks', "task_id", "user_id")->withTimestamps();
    }
    public function loggedTimes(){
    	return $this->hasMany(LoggedTime::class, 'task');
    }
    public function project(){
        return $this->belongsTo(Project::class, 'project');
    }

    public function setDueDateAttribute($value){
        $this->attributes['due_date'] = empty($value) ? null : $value;
    }

}
