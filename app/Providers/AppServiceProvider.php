<?php

namespace App\Providers;

use App\Http\Models\PushNotification;
use App\Http\Models\ReservationPlayer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		ReservationPlayer::deleted(function ($reservationPlayer) {
			PushNotification::where("message_owner_id",$reservationPlayer->id)
							->where("message_owner_type",ReservationPlayer::class)
							->delete();
		});
	}
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
