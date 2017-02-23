<?php

namespace App\Http\Controllers\ClubAdmin\Reservations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Http\Models\Club;
use App\Http\Models\Course;

class ReservationsController extends Controller {
	public function index() {
		return view ( 'admin.reservations.reservations' );
	}
        
        public function store(Request $request) {
		// dd(Carbon::now()->toDateTimeString());
            
		if (! $request->has ( 'club_id' )) {
			$this->error = "mobile_invalid_club_identifire";
			return $this->response ();
		}
                $club = Club::find ( $request->get ( 'club_id' ) );
		if (is_null ( $club ) && count ( $club ) < 1) {
			$this->error = "mobile_invalid_club";
			return $this->response ();
		}
		if (! $request->has ( 'course_id' )) {
			$this->error = "mobile_invalid_course_identifire";
			return $this->response ();
		}
                $course = Course::getCourseByClubId ( $request->get ( 'course_id' ), $club->id );
                if (is_null ( $course ) && count ( $course ) < 1) {
				$this->error = "mobile_invalid_court";
				return $this->response ();
		}
                
                if (! $request->has ( 'reserved_at' )) {
			
			$reservedAt = Carbon::today ()->format ( 'Y-m-d' );
		} else {
			$reservedAt = Carbon::parse ( $request->get ( 'reserved_at' ) )->format ( 'Y-m-d' );
		}
                
		if (! $request->has ( 'time' )) {
			$this->error = "mobile_reservation_time_missing";
			return $this->response ();
                }
                
               
                //Calculate start and end times based on duration of booking
                $startTime = Carbon::parse ( $request->get ( 'time' ) );
                //$totalDurationOfReservations = $numberOfBookings * $course->bookingDuration;
                //$endTime = $startTime->copy ()->addMinutes ( $totalDurationOfReservations );
                $startTime = Carbon::parse ( $reservedAt . " " . $startTime->toTimeString () );
                //$endTime = Carbon::parse ( $reservedAt . " " . $endTime->toTimeString () );
                
                if (! $request->has ( 'player' ) || (is_array ( $request->get ( 'player' ) ) && empty ( $request->get ( 'player' ) ))) {
                   
                    if(!$request->has ( 'guests' )){
                        $this->error = "player_missing";
                        return $this->response ();
                    }else if((int)$request->get ( 'guests' ) <= 0){
                        $this->error = "player_missing";
                        return $this->response ();
                    }
                   $players = [];
                }else{
                    $players = $request->get ( 'player' );
                }
               
              
		//$players = array_filter ( $request->get ( 'player' ) );
                $players = array_filter ( $players,function($val){
                    if($val == 0 || trim($val) == ""){
                        return false;
                    }else{
                        return true;
                    }
                } );
                //array_unshift($players,Auth::user ()->id);
		$players = array_unique ( $players );
                
                //add number of guests as separate values to the players array 
                if($request->has('guests') && $request->get('guests') > 0){
                    for($x=0; $x < $request->get('guests'); $x++){
                        $players[] = "guest";
                    }
                    
                }
                    
		if (count ( $players ) < 2 || count ( $players ) > 4) {
			$this->error = "mobile_players_are_not_enough";
			return $this->response ();
		}
                
               
                
		try {
			\DB::beginTransaction ();
			
			$reservation = new TennisReservation ();
                        
                        $bookingsFoundOnTimeSlotForRequestedCourse = [];
                        
                        //Get existing bookings data on timeslot if numberOfBookings is 1.
                        //This data will be used to decide if the booking status needs to be reserved or waiting
                        //In case of multiple booking it has to be reserved else it cant be booked
                        
                        if($numberOfBookings == 1){
                            $bookingsFoundOnTimeSlotIndependentOfCourse = $reservation->getReservationsAgainstTimeSlotIndependentOfCourse( $startTime->toDateTimeString(),$endTime->toDateTimeString(),$request->get( 'club_id'));
                            
                            foreach($bookingsFoundOnTimeSlotIndependentOfCourse as $booking){
                                if($booking->course_id == $request->get('course_id')){
                                    $bookingsFoundOnTimeSlotForRequestedCourse[] = $booking;
                                }
                            }
                            
                            foreach ( $bookingsFoundOnTimeSlotIndependentOfCourse as $pastReservation ) {

                                    if ($pastReservation ["parent_id"] == Auth::user ()->id) {
                                       
                                            $this->error = "already_made_a_reservation";
                                            return $this->response ();
                                    }
                            }
                        }
                      
			if (count ( $bookingsFoundOnTimeSlotForRequestedCourse ) >= 1) {
				$this->error = "mobile_slot_already_reserved";
				return $this->response ();
			}
			
			$reservationData ['club_id'] = $course->club_id;
			$reservationData ['course_id'] = $course->id;
			$reservationData ['time_start'] = $startTime->toDateTimeString ();
			$reservationData ['time_end'] = $endTime->toDateTimeString ();
			$reservationData ['parent_id'] = Auth::user ()->id;
			
                        //Check if there are already bookings on timeslot. If not this one goes to reservation
                        //otherwise it goes to waiting
			//if (count ( $bookingsFoundOnTimeSlotForRequestedCourse ) == 0) {
                           //Check if there are any guests along with parent
                           //If so parent + any guests fulfill the criteria for confirmed reservation
                           
                        //    if($request->has('guests') && (int)$request->get('guests') > 0){
                                 
                                $reservationData ['status'] = \Config::get ( 'global.reservation.reserved' );
                        //    }else{
                        //        $reservationData ['status'] = \Config::get ( 'global.reservation.pending_reserved' );
                        //    }
				
			//} else {
                            //Check if there are any guests along with parent
                            //If so parent + any guests fulfill the criteria for confirmed waiting
                        //     if($request->has('guests') && (int)$request->get('guests') > 0){
                        //        $reservationData ['status'] = \Config::get ( 'global.reservation.waiting' );
                        //    }else{
                        //        $reservationData ['status'] = \Config::get ( 'global.reservation.pending_waiting' );
                        //    }
				
			//}
			
			$reservation->populate ( $reservationData )->save ();
			
			$reservation->attachPlayers ( $players, $reservationData ['parent_id'], true );
			
			// Send push notifications to players associated with the reservation
			//$playersToNotify = TennisReservationPlayer::returnTennisReservationPlayersFromPlayerIdsArray ( $reservation->id, $players, true );
			
                        //$parent = Member::find ( $reservation->parent_id );
			
			//foreach ( $playersToNotify as $player ) {
			//	$player->sendNotificationToPlayerForReservationConfirmation ( $reservation, $parent, $course->name );
			//}
			
                        //$players_confirmed = $reservation->number_of_confirm_players();
                        //if($players_confirmed >=2){
                        //    $reservation->status = \Config::get ( 'global.reservation.reserved' );
                        //}
			// Dispatch job to assess reservation status after given time delay
			//$reservation->dispatchMakeReservationDecisionJob ();
			
			// Unset properties not meant to be sent to the user
                    
                        $reservation->players = $reservation->getTennisReservationPlayersWithNameByReservationId ();
                           
			foreach ( $reservation->players as $index => $player ) {
				unset ( $reservation->players [$index]->device_registeration_id );
				unset ( $reservation->players [$index]->device_type );
			}
			unset ( $reservation->tennis_reservation_players );
			
			$reservation->date = Carbon::parse ( $reservation->time_start )->format ( 'm/d/Y' );
			$reservation->time_start = Carbon::parse ( $reservation->time_start )->format ( 'h:i A' );
			
			$this->response = $reservation;
                        
                        
			\DB::commit ();
		} catch ( \Exception $e ) {
                        //dd($e);
			\DB::rollback ();
                       
			\Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
			$this->error = "exception";
		}
       
		return $this->response ();
	}
        
          public function update(Request $request) {
             try{
            
		if (! $request->has ( 'tennis_reservation_id' )) {
			
			$this->error = "tennis_reservation_id_missing";
			return $this->response ();
		}
		$reservation = TennisReservation::find ( $request->get ( 'tennis_reservation_id' ) );
		
		
		if (!$reservation || $reservation->tennis_reservation_players == null) {
			
			$this->error = "invalid_reservation";
			return $this->response ();
		}
                
                if (! $request->has ( 'parent_id' )) {
			$this->error = "reservation_parent_missing";
			return $this->response ();
		}
		if (! $request->has ( 'player' ) || (is_array ( $request->get ( 'player' ) ) && empty ( $request->get ( 'player' ) ))) {
                   
                    if(!$request->has ( 'guests' )){
                        $this->error = "player_missing";
                        return $this->response ();
                    }else if((int)$request->get ( 'guests' ) <= 0){
                        $this->error = "player_missing";
                        return $this->response ();
                    }
                   $players = [];
                }else{
                    $players = $request->get ( 'player' );
                }
               
              
		//$players = array_filter ( $request->get ( 'player' ) );
                $players = array_filter ( $players,function($val){
                    if($val == 0 || trim($val) == ""){
                        return false;
                    }else{
                        return true;
                    }
                } );
		//array_unshift($players,Auth::user ()->id);
		$players = array_unique ( $players );
                
                //add number of guests as separate values to the players array 
                if($request->has('guests') && $request->get('guests') > 0){
                    for($x=0; $x < $request->get('guests'); $x++){
                        $players[] = "guest";
                    }
                    
                }
                    
		if (count ( $players ) < 2 || count ( $players ) > 4) {
			$this->error = "mobile_players_are_not_enough";
			return $this->response ();
		}
		//Check if the players are not over their allocated time allowance
                $totalDurationOfReservations = \Config::get ( 'global.reservation.timeForReservationInSeconds' );
                $reservedAt = $reservation->time_start;
                $result = $this->validateTimeAllowanceRemainingForReservationPlayers($players,$reservedAt,$totalDurationOfReservations);
                if($result != null){
                     $this->error = "players_over_allowed_limit";
                     $this->responseParameters["player_names"] = $result;
                     return $this->response();
                }
                $existing_player_ids_to_be_kept = [];
                $new_players_received_including_guests = [];
                $tennis_reservation_players_to_be_updated_or_deleted = [];
                $newly_added_tennis_reservation_players =[];
                //Get tennis reservation ids that need not be updated as the player ids they contain have been 
                //sent with the updated players array again
                foreach($reservation->tennis_reservation_players as $tennisReservationPlayer){
                    if(in_array((string)$tennisReservationPlayer->player_id, $players) ){
                        
                        $existing_player_ids_to_be_kept[] = $tennisReservationPlayer->player_id;
                    }else{
                        $tennis_reservation_players_to_be_updated_or_deleted[] = $tennisReservationPlayer;
                    }
                    
                }
               
                foreach($players as $playerReceived){
                    if(!in_array((string)$playerReceived, $existing_player_ids_to_be_kept)){
                        $new_players_received_including_guests[] = $playerReceived;
                    }
                }
                
		try {
			\DB::beginTransaction ();
                      $reservation->parent_id = $request->get('parent_id');
                      $reservation->save();
            //    iterate through new players array and check if there are any elements left
            //    in tennis reservation players to update or delete array. If found any, update
            //    that element with the element from new players array and unset both elements 
            //    from both array since they are dealt with. At the end of this iteration if there 
            //    is something left in $tennis_reservation_players_to_be_updated_or__deleted array, 
            //    it needs to be deleted since all new players received have been added If there is 
            //    something left in the new players received array it needs to be entered as new tennis 
            //    reservation player since all the existing entries that could be updated have been updated 
            //    If both have a count of zero that means both arrays have been balanced off against each other   
                        
                        for($x = 0; $x >count($new_players_received_including_guests); $x++){
                            if(count($tennis_reservation_players_to_be_updated_or_deleted) > 0){
                              
                                $useCase =  \Config::get ( 'global.pushNotificationsUseCases.reservation_cancelled_by_parent' );
                                $msgTitle = "Reservation Cancelled";
                                $msgBody = sprintf(trans('message.pushNotificationMessageBodies.reservation_cancelled_by_parent'),Carbon::parse($reservation->time_start)->format('h:i A'));
                                $tennis_reservation_players_to_be_updated_or_deleted[0]->sendNotificationToPlayerGeneral($useCase,$msgTitle,$msgBody);
                                
                                if($new_players_received_including_guests[$x] == "guest"){
                                    $tennis_reservation_players_to_be_updated_or_deleted[0]->player_id = 0;
                                    
                                }else{
                                    $tennis_reservation_players_to_be_updated_or_deleted[0]->player_id = $new_players_received_including_guests[$x];
                                   
                                }
                                $tennis_reservation_players_to_be_updated_or_deleted[0]->status = \Config::get ( 'global.reservation.confirm' );
                                $tennis_reservation_players_to_be_updated_or_deleted[0]->save();
                                $newly_added_tennis_reservation_players[] = $tennis_reservation_players_to_be_updated_or_deleted[0];
                                
                                //Remove both array entries since they have been processed against each other
                                unset($tennis_reservation_players_to_be_updated_or_deleted[0]);
                                unset($new_players_received_including_guests[$x]);
                                $tennis_reservation_players_to_be_updated_or_deleted = array_values($tennis_reservation_players_to_be_updated_or_deleted);
                               
                            }else{
                                break;
                            }
                            
                        }
                     
                      
                        if(count($tennis_reservation_players_to_be_updated_or_deleted)){
                            foreach($tennis_reservation_players_to_be_updated_or_deleted as $reservationPlayer){
                                $reservationPlayer->delete();
                            }
                        }
                        
                        if(count($new_players_received_including_guests)){
                            foreach($new_players_received_including_guests as $player){
                                $reservationPlayer = new TennisReservationPlayer();
                                if($player == "guest"){
                                    $reservationPlayer->player_id = 0;
                                    
                                }else{
                                   $reservationPlayer->player_id = $player;
                                  
                                }
                                $reservationPlayer->status = \Config::get ( 'global.reservation.confirm' );
                                $reservation->tennis_reservation_players()->save($reservationPlayer);
                                $newly_added_tennis_reservation_players[] = $reservationPlayer;
                            }
                        }
                        
                        
//                        $remainingConfirmedPlayers = $reservation->number_of_confirm_players();
//                        if($remainingConfirmedPlayers < 2){
//                            //reset status of reservation if the changes have reduced remaining confirmed players
//                            //below the threshold
//                            if($reservation->status == \Config::get ( 'global.reservation.reserved' )){
//                                $reservation->status =  \Config::get ( 'global.reservation.pending_reserved' );
//                            }else if($reservation->status == \Config::get ( 'global.reservation.waiting' )){
//                                $reservation->status =  \Config::get ( 'global.reservation.pending_waiting' );
//                            }
//                            $reservation->save();
//
//
//                        }
                        //Send messages to newly added players
//                        $parent = Member::find ( $reservation->parent_id );
//			$course = Course::find ( $reservation->course_id );
//			foreach ( $reservation->tennis_reservation_players as $player ) {
//				$player->sendNotificationToPlayerForReservationConfirmation ( $reservation, $parent, $course->name );
//			}
                        
                        // Dispatch job to assess reservation status after given time delay
//			$reservation->dispatchMakeReservationDecisionJob ();
//			$this->response = "reservation_updated_successfuly";
                        
                        $playersWithNames = $reservation->getTennisReservationPlayersWithNameByReservationId ();
                       
                        
			
                        
			// Send push notifications to players associated with the reservation
			// $this->sendNotificationToMembersForReservation("Title Of Message", "Body of Message",$reservation->players);
			
			// Unset properties not meant to be sent to the user
			foreach ( $playersWithNames as $index => $player ) {
				unset ( $playersWithNames [$index]->device_registeration_id );
				unset ( $playersWithNames [$index]->device_type );
                                //Push parent to first place
                                if($player->player_id == $reservation->parent_id){
                                    $playersWithNames->prepend($player);
                                    unset($playersWithNames[$index]);
                                }
                                
			}
			unset ( $reservation->tennis_reservation_players );
			$reservation->players = array_values($playersWithNames->toArray());
			$reservation->date = Carbon::parse ( $reservation->time_start )->format ( 'm/d/Y' );
			$reservation->time_start = Carbon::parse ( $reservation->time_start )->format ( 'h:i A' );
			
			$this->response = $reservation;
			\DB::commit ();
		} catch ( \Exception $e ) {
                        dd($e);
			\DB::rollback ();
			\Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
			$this->error = "exception";
		}
             }catch(\Exception $e){
                $this->response = ['errorMessage'=>$e];
                return $this->response ();
             }
		return $this->response ();
	}
	
	public function delete($tennis_reservation_id) {
		if (! isset ( $tennis_reservation_id ) || ( int ) $tennis_reservation_id === 0) {
			
			$this->error = "tennis_reservation_id_missing";
			return $this->response ();
		}
		$tennisReservation = TennisReservation::find ( $tennis_reservation_id );
		$tennisReservationResponseIfSucceeds = $tennisReservation;
		$tennisReservationResponseIfSucceeds->players = $tennisReservationResponseIfSucceeds->getTennisReservationPlayersWithNameByReservationId ();
		$tennisReservationResponseIfSucceeds->date = Carbon::parse ( $tennisReservationResponseIfSucceeds->time_start )->format ( 'm/d/Y' );
		// dd($reservationPlayersAgainstReservation);
		if ($tennisReservation == null) {
			
			$this->error = "invalid_reservation";
			return $this->response ();
		} else {
			try {
				
				// Send push notifications to players associated with the reservation
				// $this->sendNotificationToMembersForReservation("Title Of Message", "Body of Message",$tennisReservationResponseIfSucceeds->players);
				
				// Unset properties not meant to be sent to the user
				unset ( $tennisReservation->players );
				$tennisReservation->delete ();
				
				$this->response = $tennisReservationResponseIfSucceeds;
			} catch ( \Exception $e ) {
				
				\Log::info ( __METHOD__, [ 
						'error' => $e->getMessage () 
				] );
				$this->error = "exception";
			}
		}
		return $this->response ();
	}
     
}
