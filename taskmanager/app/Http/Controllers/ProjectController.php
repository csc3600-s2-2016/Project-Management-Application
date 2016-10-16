<?php
/**
 * User: Kyle.Lewer
 */

namespace App\Http\Controllers;


class ProjectController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        return view('projects')
    }
}