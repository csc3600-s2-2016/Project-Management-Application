<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersTask extends Model
{
    protected $table = 'usersTasks';
    public function user(){
    	return $this->hasOne(User::class, 'user_id');
    } 
    public function task(){
    	return $this->hasOne(Task::class, 'task_id');
    } 
}
