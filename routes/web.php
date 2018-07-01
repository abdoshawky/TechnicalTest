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

Route::get('/', 'HomeController@index');

//Route::get('/home', 'HomeController@index');

Route::get('/login',    'Dashboard\UsersController@loginView')->name('login');
Route::post('/login',   'Dashboard\UsersController@login');

Route::get('/register',             'Dashboard\UsersController@registerView');
Route::post('/register',            'Dashboard\UsersController@register');
Route::get('users/verify/{code}',   'Dashboard\UsersController@verifyUser');

Route::post('/logout', 'Dashboard\UsersController@logout');

Route::group(['middleware'  => 'auth:web'], function(){
    Route::get('users/account',     'Dashboard\UsersController@getAccount');
    Route::put('users/account',     'Dashboard\UsersController@updateAccount');
    Route::delete('users/account',  'Dashboard\UsersController@closeAccount');

    Route::post('posts',                    'Dashboard\PostsController@store');
    Route::put('posts/{id}',                'Dashboard\PostsController@update');
    Route::delete('posts/{id}',             'Dashboard\PostsController@destroy');
    Route::post('posts/{id}/comments',      'Dashboard\PostsController@storeComment');
    Route::delete('posts/{postId}/comments/{commentId}', 'Dashboard\PostsController@destroyComment');
});