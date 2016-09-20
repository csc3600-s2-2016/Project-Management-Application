<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class taskController extends Controller
{
	

    public function store(){
    	return view('tasks');
    }
    public function index(){
    	return view('tasks');
    }
    public function getAll(){
    	$projectData = new ProjectData();
    	foreach (\App\Task::all() as $task) {
    		$taskJSON = new TaskJSON();
    		$taskJSON->name = $task->name;
    		$taskJSON->description = $task->description;
    		$taskJSON->dueDate = $task->due_date;
    		$taskJSON->priority = $task->priority;
    		$taskJSON->status = $task->status;
    		$taskJSON->timeEstimated = $task->time_estimated;


    		foreach ($task->subtasks as $subtask) {
    			array_push($taskJSON->subtasks, $subtask);
       		}

       		foreach ($task->loggedTimes as $loggedTime) {
       			array_push($taskJSON->loggedTimeHistory, $loggedTime);
       		}

       		foreach ($task->users as $userTask) {
	       		array_push($taskJSON->assignedUsers, "u".($userTask->user_id));
	       	}

       		$projectData->tasks["t".$task->id] = $taskJSON;
    	}

    	foreach (\App\User::all() as $user) {
    		$userJSON = new UserJSON;
    		$userJSON->id = "u" . $user->id;
    		$userJSON->displayName = $user->display_name;
    		$projectData->users["u" . $user->id] = $userJSON;
    	}



    	return json_encode($projectData);

    }

}
class TaskJSON {
	public $name = "";
	public $description = "";
	public $dueDate = "";
	public $loggedTimeHistory = array();
	public $assignedUsers = array();
	public $priority = "";
	public $status ="";
	public $subtasks = array();
	public $timeEstimated = "";
}

class UserJSON{
	public $id ="";
	public $displayName = "";
}

class ProjectData {
	public $tasks = array();
	public $users = array();
	public $currentUser = "";
}