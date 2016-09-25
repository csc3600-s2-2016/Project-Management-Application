<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Task;
use App\Subtask;
use App\LoggedTime;
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
        $projectData->currentUser = 'u'.session('user_id');  //for testing purposes!!


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
    public $updatedBy;
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
        $subtasks = $request->input('newtask.subtasks');
        $subtaskIds = array();
        for ($i=0; $i < count($subtasks); $i++) { 
            $subtask = new Subtask([
                "name" => $subtasks[$i]['name'],
                "priority" => $subtasks[$i]['priority'],
                "complete" => $subtasks[$i]['complete']
            ]);
            $task->subtasks()->save($subtask);
            $subtaskIds[$subtasks[$i]['tempID']] = $subtask->id;
        }


        $this->response = json_encode(['newID' => $task->id, 'tempID' => $request->input('tempID'), 'subtaskIds' => $subtaskIds]);
        // $this->response->header('Content-Type', 'application/json');
        $usersDisplayName = User::find($this->updatedBy)->first()->display_name;
        $this->notification->message = "$usersDisplayName created a new task:\n $task->name";
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->data["id"] = "t".$task->id;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function changeTaskPrioritys($request){
        $priorities = $request->input('priorities');
        for ($i=0; $i < count($priorities); $i++) { 
            $taskid = $priorities[$i];
            $taskid = substr_replace($taskid, '', 0, 1);
            $task = Task::find($taskid);
            $task->priority = $i;
            $task->save();
            

        }

        $this->notification->message = null;
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function changeTaskStatus($request){
        $statusData = $request->input('statusData');
        $taskid = substr_replace($statusData['taskid'], '', 0, 1);
        $task = Task::find($taskid);
        $task->status = $statusData['newStatus'];
        $task->save();
        $usersDisplayName = User::find($this->updatedBy)->first()->display_name;
        $this->notification->message = "$usersDisplayName moved task from \"" . $statusData['oldStatus'] . "\" to \"" . $statusData['newStatus'] . "\".";
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function completeSubtask($request){
        $subtaskId = $request->input('data.subtaskId');
        $subtask = Subtask::find($subtaskId);
        $subtask->complete = $request->input('data.complete');
        $subtask->save();
        $completionAction = $request->input('data.complete') ? "completed" : "un-completed";
        $usersDisplayName = User::find($this->updatedBy)->first()->display_name;
        $this->notification->message = "$usersDisplayName $completionAction subtask \"$subtask->name\" from the task \"";
        $this->notification->message .= Task::find($request->input('data.taskId'))->name . "\"";
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function logTime($req){
        $log = $req->input('logData.log');
        $taskid = substr_replace(($req->input('logData.taskId')), '', 0, 1);
        $task = Task::find($taskid);
        $userId = $log["user"]["id"];
        if (strpos($userId, "u") == 0){
            $userId = substr_replace($userId, '', 0, 1);
        }
        $user = User::find($userId);
        $loggedTime = new LoggedTime;
        $loggedTime->user_id = $user->id; //TODO:   CHECK THIS!!!!
        // $user->loggedTimes()->associate($loggedTime); //TODO:   CHECK THIS!!!!
        $loggedTime->start_date_time = $log["startDateTime"];
        $loggedTime->time_logged = $log["timeLogged"];
        $loggedTime->notes = $log["notes"];
        $task->loggedTimes()->save($loggedTime);
        $this->response = json_encode(['logId' => $loggedTime->id, 'tempLogId' => '']); //todo:  save id in front end

        $usersDisplayName = User::find($this->updatedBy)->first()->display_name;
        $this->notification->message = "$usersDisplayName logged time for the task \"$task->name\"";
        $this->notification->project = $this->project;
        $this->notification->data = $req->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function performUpdate($req){
        switch ($this->updateType) {
            case "newTask":
                $this->newTask($req);
                break;
            case "changeTaskPrioritys":
                $this->changeTaskPrioritys($req);
                break;
            // case "changeSubtaskPriority":
            //     $this->changeSubtaskPriority($req);
            //     break;
            // case "editTask":
            //     $this->editTask($req);
            //     break;
            case "completeSubtask":
                $this->completeSubtask($req);
                break;
            case "changeTaskStatus":
                $this->changeTaskStatus($req);
                break;
            case "logTime":
                $this->logTime($req);
                break;
            // case "archiveTask":
            //     $this->archiveTask($req);
            //     break;
            default:
                $this->Response("Undefined update type. How the hell are me meant to process that?!", 400);
        }
    }

    


}