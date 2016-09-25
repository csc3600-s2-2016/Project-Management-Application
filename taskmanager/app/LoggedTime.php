<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoggedTime extends Model
{
    protected $table = 'loggedTime';
    protected $fillable = ['notes', 'time_logged', 'start_date_time'];
	public function user(){
	    $this->belongsTo(User::class, "user_id");
	}
	public function task(){
	    $this->belongsTo(Task::class, "task");
	}

}
