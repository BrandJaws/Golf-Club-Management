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


Route::group([
	'middleware'=>'auth.mobile',
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
	Route::delete('/{reservation_id}', [
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
		'uses' => 'Mobile\ReservationsController@deleteReservationPlayer'
	]);

});
