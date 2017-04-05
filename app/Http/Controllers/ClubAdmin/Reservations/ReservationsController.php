<?php

namespace App\Http\Controllers\ClubAdmin\Reservations;

use App\Http\Controllers\Controller;
use App\Http\Models\ReservationPlayer;
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
        $query  = " CREATE VIEW compound_reservations_aggregated AS ";
        $query .= "     SELECT ";
        $query .= "     course.club_id as club_id, ";
        $query .= "     course.id as course_id, ";
        $query .= "     course.name as course_name, ";
        $query .= "     routine_reservations.id as reservation_id, ";
        $query .= "     reservation_time_slots.reservation_type as reservation_type, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.parent_id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as parent_ids, ";
        $query .= "     reservation_time_slots.time_start as date_time_start, ";
        $query .= "     TIME(reservation_time_slots.time_start) as time_start, ";
        $query .= "     DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= "     GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= "     GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= "     GROUP_CONCAT(IFNULL( member.profilePic,' ') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_profile_pics, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.reservation_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_statuses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.response_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as response_statuses ";
        $query .= "     FROM ";
        $query .= "     routine_reservations ";
        $query .= "     LEFT JOIN course ON routine_reservations.course_id = course.id ";
        $query .= "     LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND reservation_time_slots.reservation_type = '".addslashes(RoutineReservation::class)."' ";
        $query .= "     LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND reservation_players.reservation_type = '".addslashes(RoutineReservation::class)."' ";
        $query .= "     LEFT JOIN member ON reservation_players.member_id = member.id ";
        $query .= "     WHERE ";
        $query .= "     ((reservation_players.reservation_status ='RESERVED' AND reservation_players.response_status ='CONFIRMED') OR  reservation_players.reservation_status ='PENDING RESERVED') ";
        $query .= "     GROUP BY course.id,course.club_id,course.name,routine_reservations.id,reservation_time_slots.time_start,reservation_time_slots.reservation_type ";
        //dd($query);
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
        if (!$request->has('club_id')) {
            $this->error = "mobile_invalid_club_identifire";
            return $this->response();
        }
        $club = Club::find($request->get('club_id'));

        if (is_null($club) && count($club) < 1) {
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
                $players[] = "guest";
            }

        }

        if (count($players) < 1 || count($players) > 4) {
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }


        try {
            \DB::beginTransaction();


            $reservationsOnTimeSlot = $course->getResevationsAtCourseForATimeSlot($startTime);

            if ($reservationsOnTimeSlot->count() >= 1) {
                $this->error = "mobile_slot_already_reserved";
                return $this->response();
            }
            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
            if ($playersWithOtherReservationsInBetween != null) {
                $this->error = "players_already_have_booking";
                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                return $this->response();
            }
            $reservationData ['club_id'] = $course->club_id;
            $reservationData ['course_id'] = $course->id;


            $reservation = RoutineReservation::create($reservationData);

            $reservation->attachPlayers($players, 0, true, 1, \Config::get('global.reservation.reserved'));
            $reservation->attachTimeSlot($startTime);


            $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
            $this->response = $firstReservationsOnTimeSlots;


            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollback();

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
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
        $club = Club::find($reservation->club_id);
        $course = Course::find($reservation->course_id);

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
        $reservationPlayersWithReservationStatusReservedOrPendingReserved = ReservationPlayer::where(function($query){

                                                                                                                            $query->where(function($query){
                                                                                                                                //To exclude dropped players when the status is reserved
                                                                                                                                $query->where("reservation_status",\Config::get('global.reservation.reserved'));
                                                                                                                                $query->where("response_status",\Config::get('global.reservation.confirmed'));
                                                                                                                            });
                                                                                                                            $query->orWhere("reservation_status",\Config::get('global.reservation.pending_reserved'));

                                                                                                                })
                                                                                                                ->where("reservation_id",$request->get('reservation_id'))
                                                                                                                ->where("reservation_type",RoutineReservation::class)
                                                                                                                ->get();

        //In case of group bookings from mobile, group size intended can be smaller than total players sent
        //In that case we cannot update. We will only allow updation if the total players competing for the playable
        //or topmost 4 slots are less than or equal to 4
    
        if($reservationPlayersWithReservationStatusReservedOrPendingReserved->count() > 4){

            $this->error = "reservation_status_not_final";
            return $this->response();
        }

        //Get tennis reservation ids that need not be updated as the player ids they contain have been
        //sent with the updated players array again
        foreach ($reservationPlayersWithReservationStatusReservedOrPendingReserved as $reservationPlayer) {
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


                    if ($new_players_received_including_guests[$x] == "guest") {
                        $reservation_players_to_be_updated_or_deleted[0]->member_id = 0;

                    } else {
                        $reservation_players_to_be_updated_or_deleted[0]->member_id = $new_players_received_including_guests[$x];

                    }
                    $reservation_players_to_be_updated_or_deleted[0]->parent_id = $reservation_players_to_be_updated_or_deleted[0]->member_id == "guest" ? null : $reservation_players_to_be_updated_or_deleted[0]->member_id;
                    $reservation_players_to_be_updated_or_deleted[0]->response_status = \Config::get('global.reservation.confirmed');
                    $reservation_players_to_be_updated_or_deleted[0]->reservation_status = \Config::get('global.reservation.reserved');
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

                $reservation->attachPlayers($new_players_received_including_guests, 0, true, 1, \Config::get('global.reservation.reserved'));
            }


//            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($request->get('reservation_id'));
//            if($reservation->reservation_players->count() > 0){
//
//                $reservation->updateReservationStatusesForAReservation();
//
//            }else{
//                foreach ($reservation->reservation_time_slots as $timeSlot) {
//                    $timeSlot->delete();
//                }
//
//                $reservation->delete();
//            }

            $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
            $this->response = $firstReservationsOnTimeSlots;
            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
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

        $reservation = RoutineReservation::where("id",$reservation_id)->with("reservation_players")->first();
       // dd($reservation);
        if ($reservation == null) {

            $this->error = "invalid_reservation";
            return $this->response();
        } else {

            try {
                \DB::beginTransaction();

                foreach ($reservation->reservation_players as $player) {
                    if($player->reservation_status == \Config::get('global.reservation.reserved') || $player->reservation_status == \Config::get('global.reservation.pending_reserved')){
                        $player->delete();
                    }

                }

                $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_id);
                if($reservation->reservation_players->count() > 0){

                    $reservation->updateReservationStatusesForAReservation();

                }else{
                    foreach ($reservation->reservation_time_slots as $timeSlot) {
                        $timeSlot->delete();
                    }

                    $reservation->delete();
                }

                //dd($reservationResponseIfSucceeds);
                $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
                $this->response = $firstReservationsOnTimeSlots;
                \DB::commit();

            } catch (\Exception $e) {
                dd($e);
                \Log::info(__METHOD__, [
                    'error' => $e->getMessage()
                ]);
                $this->error = "exception";
            }
        }
        return $this->response();
    }


}
