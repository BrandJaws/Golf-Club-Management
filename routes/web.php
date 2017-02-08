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
     Route::group(['prefix'=>'segments','as'=>'segments.'],function(){
        Route::get('/',['as'=>'segments','uses'=>'Segments\SegmentsController@index']);
             
     });
    Route::group(['prefix'=>'segments/create','as'=>'segments/create.'],function(){
        Route::get('/',['as'=>'segments/create','uses'=>'Segments\SegmentsController@create']);

    });
     Route::group(['prefix'=>'members','as'=>'members.'],function(){
        Route::get('/',['as'=>'members','uses'=>'Members\MembersController@index']);
             
     });
    Route::group(['prefix'=>'members/add','as'=>'members/add.'],function(){
        Route::get('/',['as'=>'members/add','uses'=>'Members\MembersController@add']);

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
    Route::group(['prefix'=>'profile','as'=>'profile.'],function(){
        Route::get('/',['as'=>'profile','uses'=>'Profile\ProfileController@index']);

    });
    Route::group(['prefix'=>'profile/edit','as'=>'profile/edit.'],function(){
        Route::get('/',['as'=>'profile/edit','uses'=>'Profile\ProfileController@edit']);

    });
    Route::group(['prefix'=>'rewards/create','as'=>'rewards/create.'],function(){
        Route::get('/',['as'=>'rewards/create','uses'=>'Rewards\RewardsController@create']);

    });
    Route::group(['prefix'=>'social','as'=>'social.'],function(){
        Route::get('/',['as'=>'social','uses'=>'Social\SocialController@index']);

    });
    Route::group(['prefix'=>'social/create','as'=>'social/create.'],function(){
        Route::get('/',['as'=>'social/create','uses'=>'Social\SocialController@create']);

    });
    Route::group(['prefix'=>'staff','as'=>'staff.'],function(){
        Route::get('/',['as'=>'staff','uses'=>'Staff\StaffController@index']);

    });
    Route::group(['prefix'=>'staff/create','as'=>'staff/create.'],function(){
        Route::get('/',['as'=>'staff/create','uses'=>'Staff\StaffController@create']);

    });
         
});
Auth::routes();

Route::get('/home', 'HomeController@index');
