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


Route::group(['prefix' => 'users', 'as' => 'users.'],function ()
{
    Route::get('/','Api\UsersController@listUsers')->name('list');
    Route::post('/','Api\UsersController@createUser')->name('create');
    Route::get('{id}','Api\UsersController@getUserDetail')->name('detail');
    Route::put('{id}','Api\UsersController@updateUser')->name('update');
    Route::delete('{id}','Api\UsersController@deleteUser')->name('delete');
});

Route::group(['prefix' => 'taskList', 'as' => 'task_list.'],function ()
{
    Route::get('user/{user_id}','Api\TaskListController@listTaskLists')->name('list');
    Route::post('/','Api\TaskListController@createTaskList')->name('create');
    Route::get('{id}','Api\TaskListController@getTaskListDetail')->name('detail');
    Route::put('{id}','Api\TaskListController@changeNameToTaskList')->name('update');
    Route::delete('{id}','Api\TaskListController@deleteTaskList')->name('delete');
});

Route::group(['prefix' => 'task', 'as' => 'task.'],function ()
{
    Route::get('task_list/{task_list_id}','Api\TaskController@listTask')->name('list');
    Route::post('/','Api\TaskController@createTask')->name('create');
    Route::get('task/{id}','Api\TaskController@taskDetail')->name('detail');
    Route::put('task/{id}','Api\TaskController@updateTask')->name('update');
    Route::delete('task/{id}','Api\TaskController@deleteTask')->name('delete');
});


Route::fallback(function(){
    return response()->json([
        'message' => 'API Endpoint not found, check your urls'], 404);
});
