<?php
/**
 * User: Kyle.Lewer
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Http\Requests;
use Illuminate\Http\Request;
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
        return view('projects.project');
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

        return view("projects");



    }
}