<?php
/**
 * User: Kyle.Lewer
 */

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\Tests\Iterator\RealIteratorTestCase;
use App\Project;
use Illuminate\Http\Response;




class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('projects');
    }

    public function project($id)
    {

        $project = $this->getProject($id);	
        return view('projects.project',['id' => $id]);
    }

    public function newProject(){
    	return view('projects.create-new-project');
    }

    public function create(Request $request){
    	if(is_null($request))
        {
            return response(422);
        }

        $user = Auth::user();
        $project = new Project();
        $project->name= $request->input("name");
        $project->description= $request->input("description");
        $project->col1Name= $request->input("col1");
        $project->col2Name= $request->input("col2");
        $project->col3Name= $request->input("col3");
        $project->col4Name= $request->input("col4");
        $project->created_by = $user->id;
        $project->save();

        $user->projects()->attach($project);

        return view("projects");


    }

    public function invite($request){
    	//todo: add userProject entry
    }
    public function acceptInvite($request){
    	//todo: mark userProject entry as inite accepted
    }

    public function getAll($id)
    {
        if(! $this->authedUserIsMember($id))
        {
            return response()->setStatusCode(403);
        }
        $b = 2; //making stuff work
        $project = $this->getProject($id);
        $proInfo = new ProjectDataJSON();
        $proInfo->numMembers = \App\UsersProjects::where('project_id', $project->id)->count();
        $proInfo->projectName = $project->name;
        $proInfo->projectId = $project->id;
        $proInfo->archived = ! $project->active;
        $proInfo->numTasks = \App\Task::where('project', $project->id)->count();
        $proInfo->openTasks = \App\Task::where([['project', $project->id],['archived', 1]])->count();

        // $proInfo->projectMembers = \App\UsersProjects::where('project_id',$project->id)->get()->reduce(function($carry, $item){
        //     $user = \App\User::where('id', $item->getAttribute('id'))->first();

        //     $projectMem = new ProjectMemberJSON();
        //     $projectMem->taskscreated = \App\Task::where('created_by', $user['id'])->count();
        //     $projectMem->userid = $user['id'];
        //     $projectMem->username = $user['display_name'];

        //     return array_push($carry, $projectMem);

        // }, []);


        // $proInfo->projectMembers = [ ["tasksCreated" => \App\Task::where('created_by', Auth::user()->id)->count(),
        //     "userid" => Auth::user()->id,
        //     "username" => Auth::user()->display_name]]; // demo data

        foreach ($project->users as $user) {
        	$projectMem = new ProjectMemberJSON();
        	$projectMem->taskscreated = \App\Task::where('created_by', $user->id)->count();
        	$projectMem->userid = $user->id;
        	$projectMem->username = $user->display_name;
        	array_push($proInfo->projectMembers, $projectMem);
        }

        return new JsonResponse(json_encode($proInfo));
    }

    private function getProject($id)
    {
        return \App\Project::where('id', $id)->firstOrFail();
    }

    private function authedUserIsMember($id)
    {
        return Auth::user()->projects->contains($id);
    }
    public function archive($id)
    {
        $project = $this->getProject($id);
        $userModel = Auth::user();

        if($project->created_by === $userModel->id)
        {
            $project->active = false;
            $project->save();
            return response(200);
        }
        return response(403);
    }
}
class ProjectDataJSON {
    public $projectName;
    public $projectId;
    public $numMembers;
    public $projectMembers =[];
}
class ProjectMemberJSON {
    public $username;
    public $userid;
    public $taskscreated;
    public $numTasks;
    public $openTasks;
}