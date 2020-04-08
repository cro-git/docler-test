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

Route::get('users','Api\UsersController@index');
Route::post('users/{user}/detail','Api\UsersController@updateUserWithDetail')->name('users_update_with_detail');
Route::get('users/citizenship/{countryIso2}','Api\UsersController@listByCitizenship')->name('users_by_cityzenship');
Route::delete('users/{user}','Api\UsersController@deleteUserWithoutDetail')->name('users_delete_without_detail');

Route::fallback(function(){
    return response()->json([
        'message' => 'API Endpoint not found, check your urls'], 404);
});
