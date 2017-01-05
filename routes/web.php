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
	 
	 Route::group(['prefix'=>'member','as'=>'member.'],function(){
	 	Route::get('/',['as'=>'index','uses'=>'Admin\MemberController@index']);		
	 });
     Route::group(['prefix'=>'reservations','as'=>'reservations.'],function(){
        Route::get('/',['as'=>'reservations','uses'=>'Reservations\ReservationsController@index']);
             
     });
         
});
Auth::routes();

Route::get('/home', 'HomeController@index');
