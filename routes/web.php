<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'admin','as'=>'admin.'],function(){
	 Route::get('/login',['as'=>'login','uses'=>'Admin\AdminController@showLoginForm']);
	 Route::post('/login',['as'=>'login','uses'=>'Admin\AdminController@login']);
	 Route::get('/dashboard',['as'=>'dashboard','uses'=>'Admin\AdminController@dashboard']);
});
Auth::routes();

Route::get('/home', 'HomeController@index');
