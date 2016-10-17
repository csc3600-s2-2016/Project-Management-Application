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
// Requests to handle going to the / (landing page)
Route::get('/', 'HomeController@index');

// Requests to handle going to the tasks app
Route::get('/projects', 'taskController@index');
Route::get('/tasks', 'taskController@index' );
Route::get('/taskdata', 'taskController@getAll');
Route::post('/taskdata', 'taskController@updateProject');

//Requests to handle going to the project management app
Route::get('/projects/{id}', 'ProjectController@index');
Route::get('/projects/{id}/archive', 'ProjectController@archive');
Route::get('/projects/{id}/getAll', 'ProjectController@getAll');

//Requests to handle going to the user profile app
Route::get('/profile', 'UserController@index');
Route::post('/profile', 'UserController@postData');
Route::get('/profile/getAll', 'UserController@getAll');
