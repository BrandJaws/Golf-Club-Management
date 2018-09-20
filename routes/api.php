<?php
use Illuminate\Http\Request;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */

Route::get ( '/user', function (Request $request) {
	return $request->user ();
} )->middleware ( 'auth:api' );

Route::post('/login', '\App\Http\Controllers\Mobile\MembersController@login');
Route::post('/password/forgot', '\App\Http\Controllers\Mobile\MembersController@forgotPassword');
Route::post('/password/change', '\App\Http\Controllers\Mobile\MembersController@changePassword');

Route::group([
	'middleware'=>'auth.mobile'

], function () {
	Route::group(['prefix' => 'club', 'as' => 'club.'], function() {
		Route::get('/{club_id}/members', ['as' => 'list', 'uses' => '\App\Http\Controllers\Mobile\MembersController@getClubMembers']);
	});

	Route::group([
		'prefix' => 'reservations',
		'as' => 'reservations.',

	], function () {
		Route::get('/', [
			'as' => 'reservations',
			'uses' => 'Mobile\ReservationsController@index'
		]);
		Route::post('/', [
			'as' => 'store',
			'uses' => 'Mobile\ReservationsController@store'
		]);
		Route::put('/', [
			'as' => 'update',
			'uses' => 'Mobile\ReservationsController@update'
		]);
		Route::delete('/', [
			'as' => 'delete',
			'uses' => 'Mobile\ReservationsController@delete'
		]);
		Route::get('/date/{date}', [
			'as' => 'bydate',
			'uses' => 'ClubAdmin\Reservations\ReservationsController@getReservationByDate'
		]);
		Route::get('/accept/{reservation_player_id}', [
			'as' => 'accept',
			'uses' => 'Mobile\ReservationsController@acceptReservationRequest'
		]);
		Route::get('/decline/{reservation_player_id}', [
			'as' => 'decline',
			'uses' => 'Mobile\ReservationsController@declineReservationRequest'
		]);


	});

	Route::group(['prefix' => 'member', 'as' => 'member.'], function() {
		//Route::get('/', ['as' => 'profile', 'uses' => '\App\Http\Controllers\Mobile\MembersController@show']);
		Route::post('/', ['as' => 'profile', 'uses' => '\App\Http\Controllers\Mobile\MembersController@update']);
		Route::post('/friends/add', ['as' => 'addToFavorites', 'uses' => '\App\Http\Controllers\Mobile\MembersController@addMemberToFriends']);
		Route::post('/friends/remove', ['as' => 'removeFromFavorites', 'uses' => '\App\Http\Controllers\Mobile\MembersController@removeMemberFromFriends']);
		Route::get('/friends/', ['as' => 'getFavorites', 'uses' => '\App\Http\Controllers\Mobile\MembersController@getFriends']);
		Route::get('/friends/groups/', ['as' => 'listGroups', 'uses' => '\App\Http\Controllers\Mobile\MembersController@listAllGroups']);
		Route::post('/friends/groups/add', ['as' => 'addGroup', 'uses' => '\App\Http\Controllers\Mobile\MembersController@addNewFriendsGroup']);
		Route::post('/friends/groups/update', ['as' => 'updateGroup', 'uses' => '\App\Http\Controllers\Mobile\MembersController@updateFriendsGroupInfo']);
		Route::post('/friends/groups/add-more-friends', ['as' => 'addFriendsToGroup', 'uses' => '\App\Http\Controllers\Mobile\MembersController@addMoreFriendsToAnExistingGroup']);
		Route::post('/friends/groups/remove-friends', ['as' => 'removeFriendsFromGroup', 'uses' => '\App\Http\Controllers\Mobile\MembersController@removeFriendsFromGroup']);
		Route::post('/friends/groups/delete', ['as' => 'deleteGroup', 'uses' => '\App\Http\Controllers\Mobile\MembersController@deleteGroup']);
		Route::get('/notifications', ['as' => 'list', 'uses' => '\App\Http\Controllers\Mobile\MembersController@getPushNotificationsForMemberById']);
		Route::get('/notifications/delete/{notification_id}', ['as' => 'list', 'uses' => '\App\Http\Controllers\Mobile\MembersController@deletePushNotificationForMemberById']);
		Route::get('/notifications/delete-all', ['as' => 'deleteAllNotificatoions', 'uses' => '\App\Http\Controllers\Mobile\MembersController@deleteAllPushNotificationForMember']);
		Route::get('/reservations', ['as' => 'getReservations', 'uses' => '\App\Http\Controllers\Mobile\MembersController@getReservationsForMember']);
	});

	Route::group(['prefix' => 'trainings', 'as' => 'trainings.'], function() {
		Route::get('/', ['as' => 'list', 'uses' => '\App\Http\Controllers\Mobile\TrainingsController@index']);
		Route::get('/{training_id}', ['as' => 'show', 'uses' => '\App\Http\Controllers\Mobile\TrainingsController@show']);
		Route::post('/', ['as' => 'reserve', 'uses' => '\App\Http\Controllers\Mobile\TrainingsController@reservePlaceForATraining']);
		Route::delete('/', ['as' => 'reserve', 'uses' => '\App\Http\Controllers\Mobile\TrainingsController@cancelPlaceForReservation']);


	});

	Route::group(['prefix' => 'beacon', 'as' => 'beacon.'], function() {

		Route::post('/validate', ['as' => 'validate', 'uses' => '\App\Http\Controllers\Mobile\BeaconController@validateTrustedbeacon']);
		Route::post('/perform-action', ['as' => 'perform_action', 'uses' => '\App\Http\Controllers\Mobile\BeaconController@performAppropriateAction']);

	});

	Route::group(['prefix' => 'events', 'as' => 'events.'], function() {
		Route::get('/', ['as' => 'list', 'uses' => '\App\Http\Controllers\Mobile\EventsController@index']);
		Route::get('/{event_id}', ['as' => 'show', 'uses' => '\App\Http\Controllers\Mobile\EventsController@show']);
		Route::post('/', ['as' => 'reserve', 'uses' => '\App\Http\Controllers\Mobile\EventsController@reservePlaceForAEvent']);
		Route::delete('/', ['as' => 'reserve', 'uses' => '\App\Http\Controllers\Mobile\EventsController@cancelPlaceForReservation']);


	});

	Route::group(['prefix' => 'score', 'as' => 'score.'], function() {
		
		Route::post('/', ['as' => 'store', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@store']);
		//Route::delete('/', ['as' => 'destroy', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@destroy']);
		Route::put('/', ['as' => 'update', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@update']);
		Route::post('/record-score', ['as' => 'recordScore', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@recordScoreForHoles']);
		Route::post('/group-score', ['as' => 'groupScore', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@getGroupScoreDetailed']);
		Route::post('/single-score', ['as' => 'groupScore', 'uses' => '\App\Http\Controllers\Mobile\ScoresController@getSinglePlayerScoreDetailed']);

	});

	Route::group(['prefix' => 'chat', 'as' => 'chat.'], function() {
		Route::group(['prefix' => 'reservation', 'as' => 'reservation.'], function() {

			//Routes For Chat related to reservations
			Route::post('/', ['as' => 'store', 'uses' => '\App\Http\Controllers\Mobile\ReservationChatController@store']);
			Route::post('/messages', ['as' => 'store', 'uses' => '\App\Http\Controllers\Mobile\ReservationChatController@getMessagesForChatByReservation']);

		});
	});

	/**
	 * Routes related to shop
	 */
	Route::group([
		'prefix' => 'shop',
		'as' => 'shop.'
	], function () {

		Route::get('/categories', [
			'as' => 'all_categories',
			'uses' => '\App\Http\Controllers\Mobile\ShopController@getAllCategories'
		]);

		Route::get('/categories/{category_id}/products', [
			'as' => 'products_by_category',
			'uses' => '\App\Http\Controllers\Mobile\ShopController@getProductsByCategory'
		]);
		Route::get('/products/{product_id}', [
			'as' => 'product_by_id',
			'uses' => '\App\Http\Controllers\Mobile\ShopController@getProductById'
		]);
	});

	/**
	 * Routes related to Restaurant
	 */
	Route::group([
		'prefix' => 'restaurant',
		'as' => 'restaurant.'
	], function () {

		Route::get('/categories', [
			'as' => 'all_categories',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getAllCategories'
		]);

		Route::get('/sub-categories/{category_id}/products', [
			'as' => 'products_by_category',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getProductsBySubCategory'
		]);

//		Route::get('/main-categories/{category_id}/products', [
//			'as' => 'products_by_category',
//			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getProductsByMainCategory'
//		]);
		Route::get('/products/{product_id}', [
			'as' => 'product_by_id',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getProductById'
		]);

		Route::get('/orders', [
			'as' => 'list_orders',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getOrdersForMember'
		]);

		Route::get('/orders/{order_id}', [
			'as' => 'single_order',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@getSingleOrder'
		]);

		Route::post('/orders', [
			'as' => 'new_order',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@placeNewOrder'
		]);

		Route::put('/orders', [
			'as' => 'update_order',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@updateOrder'
		]);

		Route::delete('/orders', [
			'as' => 'delete_order',
			'uses' => '\App\Http\Controllers\Mobile\RestaurantController@deleteOrder'
		]);

	});


});
