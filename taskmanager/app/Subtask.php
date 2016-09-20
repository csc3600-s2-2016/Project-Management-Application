<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
	public function task(){
	    $this->belongsTo(Task::class);
	}
}
