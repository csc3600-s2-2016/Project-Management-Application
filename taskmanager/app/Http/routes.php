<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Redis;

Route::auth();

Route::get('/', 'HomeController@index');

Route::get('/projects', 'taskController@index');

Route::get('/tasks', 'taskController@index' );

Route::get('/taskdata', 'taskController@getAll');

Route::post('/taskdata', 'taskController@updateProject');


Route::get('/tasks', function () {
    return view('tasks');
});

Route::get('/project', function () {
	return view('project');
});

Route::get('/profile', function () {
   return view('profile');
});

Route::get('/project', function() {
   return view('project');
});
