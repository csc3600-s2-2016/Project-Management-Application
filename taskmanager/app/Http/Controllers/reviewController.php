<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\User;
use App\LoggedTime;
use App\Project;
use App\Subtask;
use App\Task;
use App\UsersTask;


class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->project = session('project');
    }   

    public function index()
    {
       return view('review');
    }
}