<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    public function users(){
    	return $this->belongsToMany(User::class, 'usersProjects', "project_id", "user_id")->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'tasks', 'project_id');
    }

}
