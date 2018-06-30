<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index');

Route::get('/login',    'Dashboard\UsersController@loginView');
Route::post('/login',   'Dashboard\UsersController@login');

Route::get('/register',             'Dashboard\UsersController@registerView');
Route::post('/register',            'Dashboard\UsersController@register');
Route::get('users/verify/{code}',   'Dashboard\UsersController@verifyUser');

Route::post('/logout', 'Dashboard\UsersController@logout');