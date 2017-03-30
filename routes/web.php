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
        

        Route::get('/logout', [
            'as' => 'logout',
            'uses' => 'ClubAdmin\Admin\AdminController@logout'
        ]);
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
                Route::post('/', [
                    'as' => 'store',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@store'
                ]);
                Route::put('/', [
                    'as' => 'store',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@update'
                ]);
                Route::delete('/{reservation_id}', [
                    'as' => 'delete',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@delete'
                ]);
                Route::get('/date/{date}', [
                    'as' => 'date',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@getReservationByDate'
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
                'as' => 'index',
                'uses' => 'ClubAdmin\Segments\SegmentsController@index'
            ]);
            Route::get('/create', [
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
                'as' => 'destroy',
                'uses' => 'ClubAdmin\Members\MembersController@destroy'
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
            Route::get('/view', [
                'as' => 'view',
                'uses' => 'ClubAdmin\Notifications\NotificationsController@view'
            ]);
            Route::get('/edit', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Notifications\NotificationsController@edit'
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
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Coaches\CoachesController@store'
            ]);
            Route::get('/edit/{coach_id}', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Coaches\CoachesController@edit'
            ]);
            Route::put('/{coach_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Coaches\CoachesController@update'
            ]);
            Route::delete('/{coach_id}', [
                'as' => 'delete',
                'uses' => 'ClubAdmin\Coaches\CoachesController@destroy'
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
                'as' => 'index',
                'uses' => 'ClubAdmin\Beacon\BeaconController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Beacon\BeaconController@create'
            ]);
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Beacon\BeaconController@store'
            ]);
            Route::get('/edit/{beacon_id}', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Beacon\BeaconController@edit'
            ]);
            Route::put('/{beacon_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Beacon\BeaconController@update'
            ]);
            Route::delete('/{beacon_id}', [
                'as' => 'delete',
                'uses' => 'ClubAdmin\Staff\BeaconController@destroy'
            ]);
        });
        /**
         * Routes related to warnings
         */
        Route::group([
            'prefix' => 'warnings',
            'as' => 'warnings.'
        ], function () {
            Route::get('/', [
                'as' => 'warnings',
                'uses' => 'ClubAdmin\Warnings\WarningsController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Warnings\WarningsController@create'
            ]);
        });
        /**
         * Routes related to trainings/lessons
         */
        Route::group([
            'prefix' => 'trainings',
            'as' => 'trainings.'
        ], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@index'
            ]);
            Route::get('create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@create'
            ]);
            Route::get('edit/{training_id}', [
               'as' => 'edit',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@edit'
            ]);
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@store'
            ]);
            Route::put('/{training_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@update'
            ]);
            Route::delete('/{training_id}', [
                'as' => 'destroy',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@destroy'
            ]);
            Route::get('/{training}/players', [
                'as' => 'players',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@playersForTrainingPaginated'
            ]);
            Route::delete('/{training_id}/players', [
                'as' => 'deleteplayer',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@cancelPlaceForReservation'
            ]);
            Route::post('/{training_id}/players', [
                'as' => 'addPlayer',
                'uses' => 'ClubAdmin\Trainings\TrainingsController@reservePlaceForATraining'
            ]);
        });
        /**
         * Routes related to courses
         */
        Route::group([
            'prefix' => 'courses',
            'as' => 'courses.'
        ], function() {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'ClubAdmin\Courses\CoursesController@index'
            ]);
            Route::get('/create', [
                'as' => 'create',
                'uses' => 'ClubAdmin\Courses\CoursesController@create'
            ]);
            Route::get('/edit/{course_id}', [
                'as' => 'edit',
                'uses' => 'ClubAdmin\Courses\CoursesController@edit'
            ]);
            Route::post('/', [
                'as' => 'store',
                'uses' => 'ClubAdmin\Courses\CoursesController@store'
            ]);
            Route::put('/{course_id}', [
                'as' => 'update',
                'uses' => 'ClubAdmin\Courses\CoursesController@update'
            ]);
            Route::delete('/{course_id}', [
                'as' => 'delete',
                'uses' => 'ClubAdmin\Courses\CoursesController@destroy'
            ]);
        });
    });
});
Route::get('/home', 'HomeController@index');
