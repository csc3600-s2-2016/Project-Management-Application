<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
	protected $fillable = ['name', 'complete', 'priority'];
	public function task(){
	    $this->belongsTo(Task::class);
	}
}
