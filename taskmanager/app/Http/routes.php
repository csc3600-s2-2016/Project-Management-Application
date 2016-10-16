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

Route::get('/tasks', 'taskController@index' );

Route::get('/taskdata', 'taskController@getAll');

Route::post('/taskdata', 'taskController@updateProject');

Route::get('/projects/{id}', 'ProjectController@project');
Route::get('/create-new-project', 'ProjectController@newProject');
Route::post('/create-new-project', 'ProjectController@create');
Route::get('/projects', 'ProjectController@index');
Route::get('/profile', 'UserController@index');
Route::post('/profile', 'UserController@postData');
Route::get('/profile/getAll', 'UserController@getAll');
Route::get('/projects/{id}/archive', 'ProjectController@archive');
Route::get('/projects/{id}/getAll', 'ProjectController@getAll');
