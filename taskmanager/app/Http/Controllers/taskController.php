<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Task;
use App\Http\Requests;
use Illuminate\Support\Facades\Redis;

class taskController extends Controller
{
	

    public function store(){
    	return view('tasks');
    }

    /**
    *       Fresh load of tasks page.
    *       - generates key to authenticate user for websocket subscription
    */
    public function index(){
        //put some dummy data in session for testing
        session(['project' => 'testProject']);   //for testing!!
        session(['user_id' => 1]);   //for testing!!

        //generate key for websocket authentication.
        $socketKey = $this->keygen();
        Redis::setEx(  $socketKey, 60, json_encode( session()->all() )    );


        //return the tasks page with the websocket key as cookie
        return response()
            ->view('tasks')
            ->cookie('socketKey', $socketKey, 10, null, null, false, false);
    }

    /**
     *      getAll
     *      gets all project data to load into the tasks page
     */
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
        $projectData->currentUser = session('user_id');  //for testing purposes!!


    	return json_encode($projectData);
    }


    /**
     *      updateProject
     *      Saves changes to database and broadcasts changes to other users in project to sync updates.
     */
    public function updateProject(Request $request){

    	$data = new UpdatedData($request);

        if( $data->shouldBroadcast() ) {
            Redis::publish('projectUpdates', json_encode($data->notification));
        }

        return $data->response;

    }



    /**
    *
    *
    *           private helper functions...
    *
    *
    */
    
    private function keygen(){
        $identifierLength = 64;
        $identifier = "";
        $possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for($i = 0; $i < $identifierLength; $i++) {
            $identifier .= $possible{random_int(0, (strlen($possible) - 1))};
        }
        return $identifier;
    }

}




/**
 *
 *  Helper classes to send to front end
 *
 */
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

class Notification {
    public $message;
    public $data;
    public $project;
    public $updateType;
    public $shouldBroadcast = false;
}

class UpdatedData {
    public $updatedBy = '';
    public $project = '';
    public $updateType = '';
    public $response = '';
    public $notification;

    public function __construct($request){
        $this->updatedBy = session('user_id');
        $this->project = session('project');
        $this->updateType = $request->input('updateType');
        $this->notification = new Notification();
        $this->performUpdate($request);
    }

    public function shouldBroadcast(){
        return $this->notification->shouldBroadcast;
    }

    private function newTask($request){
        $task = new Task;

        $task->name = $request->input('newtask.name');
        $task->description = $request->input('newtask.description');
        $task->status = $request->input('newtask.status');
        $task->priority = $request->input('newtask.priority');
        $task->time_estimated = $request->input('newtask.timeEstimated');
        $task->due_date = $request->input('newtask.dueDate');
        $task->created_by = $this->updatedBy;
        $task->save();


        $this->response = json_encode(['newID' => $task->id, 'tempID' => $request->input('tempID')]);
        // $this->response->header('Content-Type', 'application/json');
        $usersDisplayName = User::find($this->updatedBy)->first()->display_name;
        $this->notification->message = "$usersDisplayName created a new task:\n $task->name";
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->data["id"] = "t".$task->id;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function performUpdate($req){
        switch ($this->updateType) {
            case "newTask":
                $this->newTask($req);
                break;
            // case "changeTaskPriority":
            //     $this->changeTaskPriority($req);
            //     break;
            // case "changeSubtaskPriority":
            //     $this->changeSubtaskPriority($req);
            //     break;
            // case "editTask":
            //     $this->editTask($req);
            //     break;
            // case "completeSubtask":
            //     $this->completeSubtask($req);
            //     break;
            // case "changeTaskStatus":
            //     $this->changeTaskStatus($req);
            //     break;
            // case "logTime":
            //     $this->logTime($req);
            //     break;
            // case "archiveTask":
            //     $this->archiveTask($req);
            //     break;
            default:
                $this->Response("Undefined update type. How the hell are me meant to process that?!", 400);
        }
    }

    


}