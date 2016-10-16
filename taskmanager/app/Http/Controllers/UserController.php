<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Tests\Iterator\RealIteratorTestCase;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('profile');
    }

    public function getAll()
    {
        $userData = new UserProfileJSON();
        $userModel = Auth::user();

        $userData->username = $userModel->display_name;
        $userData->userId = $userModel->id;
        $userData->email = $userModel->email;

        $projectInfo = new UserProjectInfoJSON();

        $loggedHrsNum =  \App\LoggedTime::where('user_id', $userModel->id)->sum('time_logged');
        $loggedTasks = \App\UsersTask::where('user_id', $userModel->id)->count();
        $numProjects = \App\UsersProjects::where('user_id', $userModel->id)->count();

        $projectInfo->numHours = $loggedHrsNum;
        $projectInfo->numTasks = $loggedTasks;
        $projectInfo->numProjects = $numProjects;

        $userData->userProjectInfo = $projectInfo;

        //build project overviews
        $projectInfo->projectOverviews = \App\UsersProjects::where('user_id', $userModel->id)->get()
            ->reduce(function ($carry, $item){
            $attributes = $item->getAttributes();
            $overview = $this->getProjectOverview($item->getAttribute('project_id'),$item->getAttribute('user_id'));
            if(! is_null($overview)) {array_push($carry, $overview);}

            return $carry;
        }, []); // <-- Empty array to initialize reduce

        return new JsonResponse(json_encode($userData));
    }
    private function getProjectOverview($projectId, $userId)
    {
        $projectOverview = new ProjectOverviewJSON();

        $project = \App\Project::where('id', $projectId)->firstOrFail();
        $projectOverview->projectName = $project->name;
        $projectOverview->projectId = $project->id;

        if($project->created_by == $userId)
            $projectOverview->canManage = 1;

        $projectOverview->numMembers = \App\UsersProjects::where('project_id', $projectId)->count();

        //get active members
        $projectOverview->expected = \App\Task::where('project', $projectId)->sum('time_estimated');

        $projectOverview->current = \App\Task::where('project', $projectId)->get()->reduce(function($carry, $item){
            return $carry + $item->loggedTimes()->sum('time_logged');
        }, 0);

        return $projectOverview;
    }


}
class UserProfileJSON {
    public $username;
    public $email;
    public $userId;
    public $userProjectInfo;
}
class UserProjectInfoJSON {
    public $numProjects;
    public $numHours;
    public $numTasks;
    public $projectOverviews = [];
}
class ProjectOverviewJSON {
    public $projectId;
    public $projectName;
    public $numMembers;
    public $expected;
    public $current;
    public $canManage = 0;
}
