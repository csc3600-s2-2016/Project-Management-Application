<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Task;
use App\Subtask;
use App\LoggedTime;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
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
        session(["project" => "testProject"]);
        //for testing
        $redisdata = (["project" => session("project")]);
        $redisdata["user"] = (["id"=>"u" . Auth::user()->id, "name"=>Auth::user()->display_name ]);   //for testing!!

        //generate key for websocket authentication.
        $socketKey = $this->keygen();
        Redis::setEx(  $socketKey, 6000, json_encode( $redisdata )    );




        //return the tasks page with the websocket key as cookie
        return response()
            ->view('tasks')
            ->cookie('uid', "u" . Auth::user()->id, 10, null, null, false, false)
            ->cookie('socketKey', $socketKey, 10, null, null, false, false);
    }

    /**
     *      getAll
     *      gets all project data to load into the tasks page
     */
    public function getAll(){
    	$projectData = new ProjectData();
        $tasks = \App\Task::where([
            ['project', '=', session("project")],
            ['archived', '=' , 0]
            ])->get();
    	foreach ($tasks as $task) {
    		$taskJSON = new TaskJSON();
    		$taskJSON->name = $task->name;
    		$taskJSON->description = $task->description;
    		$taskJSON->dueDate = $task->due_date;
    		$taskJSON->priority = $task->priority;
    		$taskJSON->status = $task->status;
    		$taskJSON->timeEstimated = $task->time_estimated;
    		foreach ($task->subtasks->sortBy('priority') as $subtask) {
    			array_push($taskJSON->subtasks, $subtask);
       		}
       		foreach ($task->loggedTimes->sortBy('start_date_time') as $loggedTime) {
       			array_push($taskJSON->loggedTimeHistory, $loggedTime);
       		}
       		foreach ($task->users as $userTask) {
	       		array_push($taskJSON->assignedUsers, "u".($userTask->id));
	       	}
       		$projectData->tasks["t".$task->id] = $taskJSON;
    	}


    	foreach (\App\User::all() as $user) {                          //todo:  Only get project users!
    		$userJSON = new UserJSON;
    		$userJSON->id = "u".$user->id;
    		$userJSON->displayName = $user->display_name;
    		$projectData->users["u" . $user->id] = $userJSON;
    	}
        $projectData->currentUser = "u" . Auth::user()->id;


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
        $this->updatedBy = Auth::user()->id;
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
        $task->time_estimated = $request->input('newtask.timeEstimated') == 0 ? null : $request->input('newtask.timeEstimated');
        $dueDate = $request->input('newtask.dueDate');
        $task->due_date = $dueDate ? date_create($dueDate) : null;
        $task->created_by = $this->updatedBy;
        $task->project = session('project');
        $task->save();
        $subtasks = $request->input('newtask.subtasks');
        $subtaskIds = array();
        if (! empty($subtasks)){
            for ($i=0; $i < count($subtasks); $i++) { 
                $subtask = new Subtask([
                    "name" => $subtasks[$i]['name'],
                    "priority" => $subtasks[$i]['priority'],
                    "complete" => $subtasks[$i]['complete']
                ]);
                $task->subtasks()->save($subtask);
                $subtaskIds[$subtasks[$i]['tempID']] = $subtask->id;
            }
        }
        $assignedUsers = $request->input('newtask.assignedUsers');
        for ($i=0; $i < count($assignedUsers); $i++) { 
            $userId = $assignedUsers[$i];
            $userId = substr_replace($userId, '', 0, 1);
            $user = User::find($userId);
            $task->users()->attach($user);
        }


        $this->response = json_encode(['newID' => "t".$task->id, 'tempID' => $request->input('tempID'), 'subtaskIds' => $subtaskIds]);
        // $this->response->header('Content-Type', 'application/json');
        $usersDisplayName = User::find($this->updatedBy)->display_name;
        $this->notification->message .= "$usersDisplayName created a new task:\n $task->name";
        $this->notification->project = $this->project;
        $this->notification->data = $request->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->data["id"] = "t".$task->id;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    public function editTask($req){
        $taskId = $req->input("task.id");
        if (strpos($taskId, "t") == 0){
            $taskId = substr_replace($taskId, '', 0, 1);
        }
        $task = Task::find($taskId);
        $task->name = $req->input("task.data.name");
        $task->description = $req->input("task.data.description");
        $task->time_estimated = $req->input("task.data.timeEstimated") == 0 ? null : $req->input("task.data.timeEstimated");
        $dueDate = $req->input('task.data.dueDate');
        $task->due_date = $dueDate ? date_create($dueDate) : null;
        $task->save();

        $editedSubtasks = $req->input('task.data.subtasks');
        $subtasksToKeep = array();
        $newSubtaskIdsToSendToClient = array();

        //update subtasks
        if (! empty($editedSubtasks)){
            for ($i=0; $i < count($editedSubtasks); $i++) { 
                if( array_key_exists("tempID", $editedSubtasks[$i])){  //not yet saved to server
                    $subtask = new Subtask([
                        "name" => $editedSubtasks[$i]['name'],
                        "priority" => $editedSubtasks[$i]['priority'],
                        "complete" => $editedSubtasks[$i]['complete']
                    ]);
                    $task->subtasks()->save($subtask);
                    $newSubtaskIdsToSendToClient[$editedSubtasks[$i]["tempID"]] = $subtask->id;
                } else {                                                    //modify existing subtask
                    $subtask = Subtask::find($editedSubtasks[$i]["id"]);
                    $subtask->name  = $editedSubtasks[$i]['name'];
                    $subtask->priority  =  $editedSubtasks[$i]['priority'];
                    $subtask->complete  =  $editedSubtasks[$i]['complete'];
                    $subtask->save();
                }
                array_push($subtasksToKeep, $subtask);
            }
        } 
        foreach ($task->subtasks as $subtaskInDatabase) {
            $deleteSubtask = true;
            foreach ($subtasksToKeep as $keep) {
                if ($subtaskInDatabase->id == $keep->id){
                    $deleteSubtask = false;
                }
            }
            if ($deleteSubtask){
                $subtaskInDatabase->delete();
            }
        }


        //update users
        $listOfNewUserIds = $req->input('task.data.assignedUsers');
        if (!empty($listOfNewUserIds)){
            for ($i=0; $i < count($listOfNewUserIds); $i++) { 
                $listOfNewUserIds[$i] = substr_replace($listOfNewUserIds[$i], '', 0, 1);
            }
        }

        $newUsers = User::find($listOfNewUserIds);
        $oldUsers = $task->users;


        foreach ($newUsers as $newUser) {               //if newlist user is not in old list, attach
            if (! $newUser->tasks->contains($task)){
                $task->users()->attach($newUser);
            }
        }
        foreach ($oldUsers as $oldUser) {               //if oldlist user is not in new list, detach
            if (! $newUsers->contains("id", $oldUser->id)){
                $task->users()->detach($oldUser);
            }
        };
        
        




        $this->response = json_encode($newSubtaskIdsToSendToClient);
        $usersDisplayName = User::find($this->updatedBy)->display_name;
        $this->notification->message .= "$usersDisplayName modified task:\n $task->name";
        $this->notification->project = $this->project;
        $this->notification->data = $req->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
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
        $usersDisplayName = User::find($this->updatedBy)->display_name;
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
        $usersDisplayName = User::find($this->updatedBy)->display_name;
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
        $user = Auth::user();
        $loggedTime = new LoggedTime;
        $loggedTime->user_id = $user->id; 
        $loggedTime->start_date_time = $log["startDateTime"];
        $loggedTime->time_logged = $log["timeLogged"];
        $loggedTime->notes = $log["notes"];
        $task->loggedTimes()->save($loggedTime);
        $this->response = json_encode(['logId' => $loggedTime->id, 'tempLogId' => '']); //todo:  save id in front end

        $usersDisplayName = User::find($this->updatedBy)->display_name;
        $this->notification->message = "$usersDisplayName logged time for the task \"$task->name\"";
        $this->notification->project = $this->project;
        $this->notification->data = $req->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    public function updateSubtaskPriorites($req){
        $subtaskData = $req->input('subtaskPriorityData.subtasks');
        $taskid = $req->input('subtaskPriorityData.taskId');
        if (strpos($taskid, "t") == 0){
            $taskid = substr_replace($taskid, '', 0, 1);
        }
        $task = Task::find($taskid);
        foreach ($subtaskData as $id => $priority) {
            $subtask = Subtask::find($id);
            $subtask->priority = $priority;
            $subtask->save();
        }

        $usersDisplayName = User::find($this->updatedBy)->display_name;
        $this->notification->message = "$usersDisplayName re-ordered subtasks for \"$task->name\"";
        $this->notification->project = $this->project;
        $this->notification->data = $req->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    public function archiveTask($req){
        $taskId = $req->input("taskId");
        if (strpos($taskId, "t") == 0){
            $taskId = substr_replace($taskId, '', 0, 1);
        }
        $task = Task::find($taskId);
        $task->archived = true;
        $task->save();

        $usersDisplayName = User::find($this->updatedBy)->display_name;
        $this->notification->message = "$usersDisplayName archived task, \"$task->name\"";
        $this->notification->project = $this->project;
        $this->notification->data = $req->all();
        $this->notification->updatedBy = "u".$this->updatedBy;
        $this->notification->updateType = $this->updateType;
        $this->notification->shouldBroadcast = true;
    }

    private function performUpdate($req){
        switch ($this->updateType) {
            case "newTask":                 //still need to create userstasks database records for this action!
                $this->newTask($req);
                break;
            case "changeTaskPrioritys":
                $this->changeTaskPrioritys($req);
                break;
            case "updateSubtaskPriorites":
                $this->updateSubtaskPriorites($req);
                break;
            case "editTask":
                $this->editTask($req);
                break;
            case "completeSubtask":
                $this->completeSubtask($req);
                break;
            case "changeTaskStatus":
                $this->changeTaskStatus($req);
                break;
            case "logTime":
                $this->logTime($req);
                break;
            case "archiveTask":                  //need to implement an archive task button and then only load un-archived tasks
                $this->archiveTask($req);
                break;
            default:
                $this->response = Response("Undefined update type. How the hell are me meant to process that?!", 400);
        }
    }

    


}