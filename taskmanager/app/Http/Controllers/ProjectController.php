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


class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $project = $this->getProject($id);


        return view('project',['id' => $id]);
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

        $proInfo->projectMembers = \App\UsersProjects::where('project_id',$project->id)->get()->reduce(function($carry, $item){
            $user = \App\User::where('id', $item->getAttribute('id'))->first();

            $projectMem = new ProjectMemberJSON();
            $projectMem->taskscreated = \App\Task::where('created_by', $user['id'])->count();
            $projectMem->userid = $user['id'];
            $projectMem->username = $user['display_name'];

            return array_push($carry, $projectMem);

        }, []);
        $proInfo->projectMembers = [ ["tasksCreated" => \App\Task::where('created_by', Auth::user()->id)->count(),
            "userid" => Auth::user()->id,
            "username" => Auth::user()->display_name]]; // demo data

        return new JsonResponse(json_encode($proInfo));
    }

    private function getProject($id)
    {
        return \App\Project::where('id', $id)->firstOrFail();
    }

    private function authedUserIsMember($id)
    {
        $user = Auth::user();
        $project = $this->getProject($id);

        if($project->created_by === $user->id) return true;

        $data = \App\UsersProjects::where(function ($query) use ($user, $project){
           $query->where('user_id',$user->id)->andWhere('project_id', $project->id);
        })->firstOrFail();
        if($data) return true;

        return false;
    }
    public function archive($id)
    {
        $project = $this->getProject($id);
        $userModel = Auth::user();

        if($project->created_by === $userModel->id)
        {
            $project->archived = 1;
            $project->save();
            return response()->setStatusCode(200);
        }
        return response()->setStatusCode(403);
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