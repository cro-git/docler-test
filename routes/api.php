<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('users','Api\UsersController@listUsers')->name('user__list');
Route::post('users','Api\UsersController@createUser')->name('user__create');
Route::get('users/{id}','Api\UsersController@getUserDetail')->name('user__detail');
Route::put('users/{id}','Api\UsersController@updateUser')->name('user__update');
Route::delete('users/{id}','Api\UsersController@deleteUser')->name('user__delete');


Route::get('taskList/user/{user_id}','Api\TaskListController@listTaskLists')->name('task_list__list');
Route::post('taskList','Api\TaskListController@createTaskList')->name('task_list__create');
Route::get('taskList/{id}','Api\TaskListController@getTaskListDetail')->name('task_list__detail');
Route::put('taskList/{id}','Api\TaskListController@changeNameToTaskList')->name('task_list__update');
Route::delete('taskList/{id}','Api\TaskListController@deleteTaskList')->name('task_list__delete');


Route::fallback(function(){
    return response()->json([
        'message' => 'API Endpoint not found, check your urls'], 404);
});
