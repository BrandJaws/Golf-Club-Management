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
             
     });     Route::group(['prefix'=>'segments','as'=>'segments.'],function(){
        Route::get('/',['as'=>'segments','uses'=>'Segments\SegmentsController@index']);
             
     });
     Route::group(['prefix'=>'members','as'=>'members.'],function(){
        Route::get('/',['as'=>'members','uses'=>'Members\MembersController@index']);
             
     });
    Route::group(['prefix'=>'rewards','as'=>'rewards.'],function(){
        Route::get('/',['as'=>'rewards','uses'=>'Rewards\RewardsController@index']);

    });
    Route::group(['prefix'=>'notifications','as'=>'notifications.'],function(){
        Route::get('/',['as'=>'notifications','uses'=>'Notifications\NotificationsController@index']);

    });
    Route::group(['prefix'=>'notifications/create','as'=>'notifications/create.'],function(){
        Route::get('/',['as'=>'notifications/create','uses'=>'Notifications\NotificationsController@create']);

    });
         
});
Auth::routes();

Route::get('/home', 'HomeController@index');
