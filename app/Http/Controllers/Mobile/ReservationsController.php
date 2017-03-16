<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Http\Models\Club;
use App\Http\Models\Course;
use App\Http\Models\RoutineReservation;
use Carbon\Carbon;

class ReservationsController extends Controller
{
    public function index()
    {
        $dayToday = Carbon::today()->toDateString();
        $fourDaysFromNow = Carbon::today()->addDays(3)->toDateString();
        $reservations = Course::getReservationsForACourseByIdForADateRange(1, $dayToday, $fourDaysFromNow);
        return view('admin.reservations.reservations', ["reservations" => json_encode($reservations)]);
    }

    public function getReservationByDate($date)
    {

        $reservations = Course::getReservationsForACourseByIdForADateRange(1, $date, $date);
        return json_encode($reservations);
    }

    public function store(Request $request)
    {

        // dd(Carbon::now()->toDateTimeString());

        if (!$request->has('club_id')) {
            $this->error = "mobile_invalid_club_identifire";
            return $this->response();
        }
        $club = Club::find($request->get('club_id'));

        if (!$club) {
            $this->error = "mobile_invalid_club";
            return $this->response();
        }
        if (!$request->has('course_id')) {
            $this->error = "mobile_invalid_course_identifire";
            return $this->response();
        }
        $course = Course::getCourseByClubId($request->get('course_id'), $club->id);
        if (is_null($course) && count($course) < 1) {
            $this->error = "mobile_invalid_court";
            return $this->response();
        }

        if (!$request->has('reserved_at')) {

            $reservedAt = Carbon::today()->format('Y-m-d');
        } else {
            $reservedAt = Carbon::parse($request->get('reserved_at'))->format('Y-m-d');
        }

        if (!$request->has('time')) {
            $this->error = "mobile_reservation_time_missing";
            return $this->response();
        }


        //Calculate start and end times based on duration of booking
        $startTime = Carbon::parse($request->get('time'));
        $startTime = Carbon::parse($reservedAt . " " . $startTime->toTimeString());


        if (!$request->has('player') || (is_array($request->get('player')) && empty ($request->get('player')))) {

            $players = [];
        } else {
            $players = $request->get('player');
        }

        $parent_id = Auth::user()->id;


        $players = array_filter($players, function ($val) {
            if ($val == 0 || trim($val) == "") {
                return false;
            } else {
                return true;
            }
        });

        $players = array_unique($players);

        //add number of guests as separate values to the players array
        if ($request->has('guests') && $request->get('guests') > 0) {
            for ($x = 0; $x < $request->get('guests'); $x++) {
                array_unshift($players, "guest");
            }

        }

        if ($request->get('group_size') > 4 || $request->get('guests') > 3) {
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }
        array_unshift($players, $parent_id);

        if(count($players) < $request->get('group_size')){
            $this->error = "players_less_than_group_size";
            return $this->response();
        }

        $reservationIdOnTimeSlot = $course->validateIfAllowedRoutineReservationAndReturnIdIfAlreadyExists($startTime);
        if ($reservationIdOnTimeSlot === false) {

            $this->error = "mobile_slot_already_reserved";
            return $this->response();
        }


        if ($reservationIdOnTimeSlot > 0) {
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservationIdOnTimeSlot);
            if (($reservation->sumOfGroupSizes('both') + $request->group_size) > 16) {
                $this->error = "mobile_slot_already_reserved";
                return $this->response();
            }
            $reservationWithSelfAsParent = $reservation->getGroupByParentId(Auth::user()->id);
            if ($reservationWithSelfAsParent) {
                $this->error = "you_already_have_booking";
                return $this->response();
            }

            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
            if ($playersWithOtherReservationsInBetween != null) {
                $this->error = "players_already_have_booking";
                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                return $this->response();
            }

            $result = $this->addNewGroupToExistingReservation($request, $reservation, $players, $parent_id);


        } else {

            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
            if ($playersWithOtherReservationsInBetween != null) {
                $this->error = "players_already_have_booking";
                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                return $this->response();
            }

            $result = $this->createNewReservationAndGroup($request, $course, $players, $parent_id, $startTime);


        }
        if ($result == "success") {
            $this->response = "mobile_reservation_successfull";
        } else {
            $this->error = $result;
        }


        return $this->response();

    }

    private function createNewReservationAndGroup(Request $request, $course, $players, $parent_id, $startTime)
    {


        $reservationData ['club_id'] = $course->club_id;
        $reservationData ['course_id'] = $course->id;

        try {
            \DB::beginTransaction();
            $reservation = RoutineReservation::create($reservationData);
            $reservation->attachPlayers($players, $parent_id, false, $request->get('group_size'), \Config::get('global.reservation.pending_waiting'));
            $reservation->attachTimeSlot($startTime);
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation->id);
            $reservation->updateReservationStatusesForAReservation();

            // Dispatch job to assess reservation status after given time delay
            //$reservation->dispatchMakeReservationDecisionJob ();


            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            return "exception";
        }

    }

    private function addNewGroupToExistingReservation(Request $request, $reservation, $players, $parent_id)
    {

        try {
            \DB::beginTransaction();

            $reservation->attachPlayers($players, $parent_id, false, $request->get('group_size'), \Config::get('global.reservation.pending_waiting'));
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation->id);
            $reservation->updateReservationStatusesForAReservation();

            // Dispatch job to assess reservation status after given time delay
            //$reservation->dispatchMakeReservationDecisionJob ();

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollback();

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            return "exception";
        }

    }

    public function update(Request $request)
    {


        if (!$request->has('reservation_id')) {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        $reservation = RoutineReservation::find($request->get('reservation_id'));


        if (!$reservation || $reservation->reservation_players == null) {

            $this->error = "invalid_reservation";
            return $this->response();
        }

        if (!$request->has('player') || (is_array($request->get('player')) && empty ($request->get('player')))) {

            if (!$request->has('guests')) {
                $this->error = "player_missing";
                return $this->response();
            } else if ((int)$request->get('guests') <= 0) {
                $this->error = "player_missing";
                return $this->response();
            }
            $players = [];
        } else {
            $players = $request->get('player');
        }

        if (!$request->has('parent_id') || ($request->get('parent_id') == 0)) {
            //$this->error = "reservation_parent_missing";
            //return $this->response ();
        } else {
            $players[] = $request->get('parent_id');
        }


        //$players = array_filter ( $request->get ( 'player' ) );
        $players = array_filter($players, function ($val) {
            if ($val == 0 || trim($val) == "") {
                return false;
            } else {
                return true;
            }
        });
        //array_unshift($players,Auth::user ()->id);
        $players = array_unique($players);
        $club = \App\Http\models\Club::find($reservation->club_id);
        $course = \App\Http\models\Course::find($reservation->course_id);

        //add number of guests as separate values to the players array
        if ($request->has('guests') && $request->get('guests') > 0) {
            for ($x = 0; $x < $request->get('guests'); $x++) {
                $players[] = "guest";
            }

        }

        if (count($players) < 1 || count($players) > 4) {
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }

        $existing_player_ids_to_be_kept = [];
        $new_players_received_including_guests = [];
        $reservation_players_to_be_updated_or_deleted = [];
        $newly_added__reservation_players = [];
        //Get tennis reservation ids that need not be updated as the player ids they contain have been
        //sent with the updated players array again
        foreach ($reservation->reservation_players as $reservationPlayer) {
            if (in_array((string)$reservationPlayer->member_id, $players)) {

                $existing_player_ids_to_be_kept[] = $reservationPlayer->member_id;
            } else {
                $reservation_players_to_be_updated_or_deleted[] = $reservationPlayer;
            }

        }

        foreach ($players as $playerReceived) {
            if (!in_array((string)$playerReceived, $existing_player_ids_to_be_kept)) {
                $new_players_received_including_guests[] = $playerReceived;
            }
        }

        $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $reservation->reservation_time_slots[0]->time_start, $new_players_received_including_guests);
        if ($playersWithOtherReservationsInBetween != null) {
            $this->error = "players_already_have_booking";
            $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
            return $this->response();
        }

        try {
            \DB::beginTransaction();
            if ($request->get('parent_id')) {
                $reservation->parent_id = $request->get('parent_id');

            } else {
                $reservation->parent_id = null;
            }
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

            for ($x = 0; $x < count($new_players_received_including_guests); $x++) {
                if (count($reservation_players_to_be_updated_or_deleted) > 0) {

//                                $useCase =  \Config::get ( 'global.pushNotificationsUseCases.reservation_cancelled_by_parent' );
//                                $msgTitle = "Reservation Cancelled";
//                                $msgBody = sprintf(trans('message.pushNotificationMessageBodies.reservation_cancelled_by_parent'),Carbon::parse($reservation->time_start)->format('h:i A'));
//                                $reservation_players_to_be_updated_or_deleted[0]->sendNotificationToPlayerGeneral($useCase,$msgTitle,$msgBody);
//                                
                    if ($new_players_received_including_guests[$x] == "guest") {
                        $reservation_players_to_be_updated_or_deleted[0]->member_id = 0;

                    } else {
                        $reservation_players_to_be_updated_or_deleted[0]->member_id = $new_players_received_including_guests[$x];

                    }
                    $reservation_players_to_be_updated_or_deleted[0]->status = \Config::get('global.reservation.confirmed');
                    $reservation_players_to_be_updated_or_deleted[0]->save();
                    $newly_added__reservation_players[] = $reservation_players_to_be_updated_or_deleted[0];

                    //Remove both array entries since they have been processed against each other
                    unset($reservation_players_to_be_updated_or_deleted[0]);
                    unset($new_players_received_including_guests[$x]);
                    $reservation_players_to_be_updated_or_deleted = array_values($reservation_players_to_be_updated_or_deleted);

                } else {
                    break;
                }

            }


            if (count($reservation_players_to_be_updated_or_deleted)) {
                foreach ($reservation_players_to_be_updated_or_deleted as $reservationPlayer) {
                    $reservationPlayer->delete();
                }
            }

            if (count($new_players_received_including_guests)) {
                $reservation->attachPlayers($new_players_received_including_guests, $request->get('parent_id'), true);
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

            //$playersWithNames = $reservation->getTennisReservationPlayersWithNameByReservationId ();


            // Send push notifications to players associated with the reservation
            // $this->sendNotificationToMembersForReservation("Title Of Message", "Body of Message",$reservation->players);

            // Unset properties not meant to be sent to the user
//			foreach ( $playersWithNames as $index => $player ) {
//				unset ( $playersWithNames [$index]->device_registeration_id );
//				unset ( $playersWithNames [$index]->device_type );
//                                //Push parent to first place
//                                if($player->player_id == $reservation->parent_id){
//                                    $playersWithNames->prepend($player);
//                                    unset($playersWithNames[$index]);
//                                }
//                                
//			}
//			unset ( $reservation->tennis_reservation_players );
//			$reservation->players = array_values($playersWithNames->toArray());
//			$reservation->date = Carbon::parse ( $reservation->time_start )->format ( 'm/d/Y' );
//			$reservation->time_start = Carbon::parse ( $reservation->time_start )->format ( 'h:i A' );
//			
            //$reservation->modifyReservationObjectForReponseOnCRUDOperations();
            $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
            //dd($firstReservationsOnTimeSlots);
            $this->response = $firstReservationsOnTimeSlots;
            \DB::commit();
        } catch (\Exception $e) {
            //dd($e);
            \DB::rollback();
            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
    }

    public function delete($reservation_id)
    {
        if (!isset ($reservation_id) || ( int )$reservation_id === 0) {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        $reservation = RoutineReservation::find($reservation_id);
        $reservationResponseIfSucceeds = $reservation;
        //$reservationResponseIfSucceeds->players = $reservationResponseIfSucceeds->getTennisReservationPlayersWithNameByReservationId ();
        //$reservationResponseIfSucceeds->date = Carbon::parse ( $reservationResponseIfSucceeds->time_start )->format ( 'm/d/Y' );
        // dd($reservationPlayersAgainstReservation);
        if ($reservation == null) {

            $this->error = "invalid_reservation";
            return $this->response();
        } else {

            try {
                \DB::beginTransaction();
                foreach ($reservation->reservation_players as $player) {
                    $player->delete();
                }
                foreach ($reservation->reservation_time_slots as $timeSlot) {
                    $timeSlot->delete();
                }
                // Send push notifications to players associated with the reservation
                // $this->sendNotificationToMembersForReservation("Title Of Message", "Body of Message",$tennisReservationResponseIfSucceeds->players);

                // Unset properties not meant to be sent to the user
                unset ($reservation->players);
                $reservation->delete();
                //dd($reservationResponseIfSucceeds);
                $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);

                $this->response = $firstReservationsOnTimeSlots;
                \DB::commit();

            } catch (\Exception $e) {

                \Log::info(__METHOD__, [
                    'error' => $e->getMessage()
                ]);
                $this->error = "exception";
            }
        }
        return $this->response();
    }


}
