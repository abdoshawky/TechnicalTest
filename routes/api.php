<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('users',         'API\UsersController@getUsers');
    Route::get('users/{id}',    'API\UsersController@getUser');
    Route::post('users',        'API\UsersController@createUser');
    Route::put('users/{id}',    'API\UsersController@updateUser');
    Route::delete('users/{id}', 'API\UsersController@deleteUser');
});
