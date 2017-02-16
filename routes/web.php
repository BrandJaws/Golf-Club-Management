<?php

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | This file is where you may define all of the routes that are handled
 * | by your application. Just tell Laravel the URIs it should respond
 * | to using a Closure or controller method. Build something great!
 * |
 */
Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::get('/login', [
        'as' => 'login',
        'uses' => 'ClubAdmin\Admin\AdminController@showLoginForm'
    ]);
    Route::post('/login', [
        'as' => 'login',
        'uses' => 'ClubAdmin\Admin\AdminController@login'
    ]);
    Route::group([
        'middleware' => 'auth'
    ], function () {
        Route::get('/dashboard', [
            'as' => 'dashboard',
            'uses' => 'ClubAdmin\Admin\AdminController@dashboard'
        ]);
        Route::group([
            'prefix' => 'reservations',
            'as' => 'reservations.'
        ], function () {
            Route::get('/', [
                'as' => 'reservations',
                'uses' => 'ClubAdmin\Reservations\ReservationsController@index'
            ]);
        });
        /**
         * Routes related to segments
         */
        Route::group([
            'prefix' => 'segments',
            'as' => 'segments.'
        ], function () {
            Route::get('/', [
                'as' => 'segments',
                'uses' => 'ClubAdmin\Segments\SegmentsController@index'
            ]);
            Route::get('/', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Segments\SegmentsController@create'
            ]);
        });
        /**
         * Routes related to members
         */
        Route::group([
            'prefix' => 'member',
            'as' => 'member.'
        ], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ClubAdmin\Members\MembersController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Members\MembersController@create'
            ]);
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Members\MembersController@store'
            ]);
            Route::get('/edit/{member_id}', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Members\MembersController@edit'
            ]);
            Route::put('/{member_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Members\MembersController@update'
            ]);
            Route::delete('/{member_id}', [
                'as' => 'distroy',
                'uses' => 'ClubAdmin\Members\MembersController@distroy'
            ]);
            Route::get('/search-list', [
                'as' => 'search-list',
                'uses' => 'ClubAdmin\Members\MembersController@searchListMembers'
            ]);
        });
        /**
         * reoutes related to rewards
         */
        Route::group([
            'prefix' => 'rewards',
            'as' => 'rewards.'
        ], function () {
            Route::get('/', [
                'as' => 'rewards',
                'uses' => 'ClubAdmin\Rewards\RewardsController@index'
            ]);
        });
        /**
         * Routes releated to notificaitons
         */
        Route::group([
            'prefix' => 'notifications',
            'as' => 'notifications.'
        ], function () {
            Route::get('/', [
                'as' => 'notifications',
                'uses' => 'ClubAdmin\Notifications\NotificationsController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Notifications\NotificationsController@create'
            ]);
        });
        Route::group([
            'prefix' => 'profile',
            'as' => 'profile.'
        ], function () {
            Route::get('/', [
                'as' => 'profile',
                'uses' => 'ClubAdmin\Profile\ProfileController@index'
            ]);
            Route::get('/edit', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Profile\ProfileController@edit'
            ]);
        });
        /**
         * Routes related to rewards
         */
        Route::group([
            'prefix' => 'rewards',
            'as' => 'rewards.'
        ], function () {
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Rewards\RewardsController@create'
            ]);
        });
        Route::group([
            'prefix' => 'social',
            'as' => 'social.'
        ], function () {
            Route::get('/', [
                'as' => 'social',
                'uses' => 'ClubAdmin\Social\SocialController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Social\SocialController@create'
            ]);
        });
        /**
         * Routes related to staff
         */
        Route::group([
            'prefix' => 'staff',
            'as' => 'staff.'
        ], function () {
            
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ClubAdmin\Staff\StaffController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Staff\StaffController@create'
            ]);
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Staff\StaffController@store'
            ]);
            Route::get('/edit/{staff_id}', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Staff\StaffController@edit'
            ]);
            Route::put('/{staff_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Staff\StaffController@update'
            ]);
            Route::delete('/{staff_id}', [
                'as' => 'delete',
                'uses' => 'ClubAdmin\Staff\StaffController@destroy'
            ]);
        });
        /**
         * Routes related to shop
         */
        Route::group([
            'prefix' => 'shop',
            'as' => 'shop.'
        ], function () {
            Route::get('/', [
                'as' => 'shop',
                'uses' => 'ClubAdmin\Shop\ShopController@index'
            ]);
        });
        
        /**
         * Routes related to coaches
         */
        Route::group([
            'prefix' => 'coaches',
            'as' => 'coaches.'
        ], function () {
            Route::get('/', [
                'as' => 'coaches',
                'uses' => 'ClubAdmin\Coaches\CoachesController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Coaches\CoachesController@create'
            ]);
        });
        /**
         * routes related to beacons
         */
        Route::group([
            'prefix' => 'beacon',
            'as' => 'beacon.'
        ], function () {
            Route::get('/', [
                'as' => 'beacon',
                'uses' => 'ClubAdmin\Beacon\BeaconController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Beacon\BeaconController@create'
            ]);
        });
    });
});
Route::get('/home', 'HomeController@index');
