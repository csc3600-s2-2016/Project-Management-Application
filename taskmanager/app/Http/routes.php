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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', 'taskController@index' );

Route::get('/taskdata', 'taskController@getAll');

Route::post('/taskdata', function(){
	return '{"tasks":{"t01":{"name":"hello"}},"users":{
                "u1": {"id": "u1", "displayName":"John"},
                "u2": {"id": "u2", "displayName":"Sarah"},
                "u3": {"id": "u3", "displayName":"Tony"},
                "u4": {"id": "u4", "displayName":"Jill"},
                "u5": {"id": "u5", "displayName":"Anontio"},
                "u6": {"id": "u6", "displayName":"Emma"}
            }}';
});