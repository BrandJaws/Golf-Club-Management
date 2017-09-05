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
                Route::get('/starter', [
                   'as' => 'starter',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@starter'
                ]);
                Route::post('/move-players', [
                    'as' => 'movePlayers',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@movePlayers'
                ]);
                Route::post('/swap-timeslots', [
                    'as' => 'swapTimeSlots',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@swapTimeSlots'
                ]);
                Route::post('/mark-as-started', [
                    'as' => 'markAsStarted',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@markGameStatusAsStarted'
                ]);
                Route::post('/checkin-player', [
                    'as' => 'checkinPlayer',
                    'uses' => 'ClubAdmin\Reservations\ReservationsController@checkinPlayer'
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
            Route::post('/categories', [
              'as' => 'create_category',
              'uses' => 'ClubAdmin\Shop\ShopController@createNewCategory'
            ]);
            Route::delete('/categories/{category_id}', [
              'as' => 'delete_category',
              'uses' => 'ClubAdmin\Shop\ShopController@deleteCategory'
            ]);
            Route::put('/categories', [
              'as' => 'update_category',
              'uses' => 'ClubAdmin\Shop\ShopController@updateCategory'
            ]);


            Route::get('/products/new', [
              'as' => 'create_product',
              'uses' => 'ClubAdmin\Shop\ShopController@showNewProductForm'
            ]);

            Route::post('/products', [
              'as' => 'store_product',
              'uses' => 'ClubAdmin\Shop\ShopController@saveNewProduct'
            ]);

            Route::get('/products/{product_id}/edit', [
              'as' => 'edit_product',
              'uses' => 'ClubAdmin\Shop\ShopController@showEditProductForm'
            ]);
            Route::post('/products/{product_id}', [
              'as' => 'update_product',
              'uses' => 'ClubAdmin\Shop\ShopController@updateProduct'
            ]);
            Route::delete('/products/{product_id}', [
              'as' => 'delete_product',
              'uses' => 'ClubAdmin\Shop\ShopController@deleteProduct'
            ]);
            Route::get('/products/by-category', [
              'as' => 'products_by_category',
              'uses' => 'ClubAdmin\Shop\ShopController@getProductsByCategoryIdPaginated'
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
        /**
         * Routes related to notifications to client
         */
        Route::group([
            'prefix' => 'live-notifications',
            'as' => 'live-notifications.'
        ], function() {
            Route::post('/reservation-updation', [
                'as' => 'reservation-updation',
                'uses' => 'ClubAdmin\AdminNotifications\AdminNotificationsController@reservationUpdation'
            ]);

        });
        /**
         * Routes related to events
         */
        Route::group([
          'prefix' => 'events',
          'as' => 'events.'
        ], function () {
            Route::get('/', [
              'as' => 'index',
              'uses' => 'ClubAdmin\Events\EventsController@index'
            ]);
            Route::get('create', [
              'as' => 'create',
              'uses' => 'ClubAdmin\Events\EventsController@create'
            ]);
            Route::get('edit/{event_id}', [
              'as' => 'edit',
              'uses' => 'ClubAdmin\Events\EventsController@edit'
            ]);
            Route::post('/', [
              'as' => 'store',
              'uses' => 'ClubAdmin\Events\EventsController@store'
            ]);
            Route::put('/{event_id}', [
              'as' => 'update',
              'uses' => 'ClubAdmin\Events\EventsController@update'
            ]);
            Route::delete('/{event_id}', [
              'as' => 'destroy',
              'uses' => 'ClubAdmin\Events\EventsController@destroy'
            ]);
            Route::get('/{event}/players', [
              'as' => 'players',
              'uses' => 'ClubAdmin\Events\EventsController@playersForEventPaginated'
            ]);
            Route::delete('/{event_id}/players', [
              'as' => 'deleteplayer',
              'uses' => 'ClubAdmin\Events\EventsController@cancelPlaceForReservation'
            ]);
            Route::post('/{event_id}/players', [
              'as' => 'addPlayer',
              'uses' => 'ClubAdmin\Events\EventsController@reservePlaceForAEvent'
            ]);
        });
    });
});
Route::get('/home', 'HomeController@index');
