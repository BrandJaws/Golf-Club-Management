<?php

namespace App\Http\Controllers\Mobile;

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
    use \PushNotification;
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

            $result = $this->addNewGroupToExistingReservation($request, $reservation, $course, $players, $parent_id, $startTime);


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

            // Send message and Dispatch job to assess reservation status after given time delay
            foreach($reservation->reservation_players as $reservation_player){
                if(in_array($reservation_player->member_id,$players)){
                    $reservation_player->sendNotificationToPlayerForReservationConfirmation($startTime, Auth::user(),$course->name);
                    $reservation_player->dispatchMakeReservationPlayerDecisionJob();
                }
            }


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

    private function addNewGroupToExistingReservation(Request $request, $reservation, $course, $players, $parent_id, $startTime)
    {

        try {
            \DB::beginTransaction();

            $reservation->attachPlayers($players, $parent_id, false, $request->get('group_size'), \Config::get('global.reservation.pending_waiting'));
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation->id);
            $reservation->updateReservationStatusesForAReservation();

            // Send message and Dispatch job to assess reservation status after given time delay
            foreach($reservation->reservation_players as $reservation_player){

                if(in_array($reservation_player->member_id,$players)){
                    $reservation_player->sendNotificationToPlayerForReservationConfirmation($startTime, Auth::user(),$course->name);
                    $reservation_player->dispatchMakeReservationPlayerDecisionJob();
                }

            }

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
        $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($request->get('reservation_id'));

        if (!$reservation || $reservation->reservation_players == null) {

            $this->error = "invalid_reservation";
            return $this->response();
        }


        $parent_id = Auth::user()->id;
        $group = $reservation->getGroupByParentId($parent_id);
        if(!$group){
            $this->error = "user_not_parent";
            return $this->response();
        }

        if($group->reservation_status !== \Config::get('global.reservation.reserved')){
            $this->error = "reservation_status_not_final";
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

        $players = array_unique($players);
        $club = \App\Http\models\Club::find($reservation->club_id);
        $course = \App\Http\models\Course::find($reservation->course_id);

        //add number of guests as separate values to the players array
        if ($request->has('guests') && $request->get('guests') > 0) {
            for ($x = 0; $x < $request->get('guests'); $x++) {
                $players[] = "guest";
            }

        }

        $newGroupSize = $group->group_size+count($players);
        if( $newGroupSize > 4){
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }

        $sumOfGroupSizesReserved = $reservation->sumOfGroupSizes('reserved');

        if(($sumOfGroupSizesReserved+count($players)) > 4){
            $this->error = "mobile_not_enough_slots_remaining";
            return $this->response();
        }

        $startTime = $reservation->reservation_time_slots->first()->time_start;
        $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime , $players);
        if ($playersWithOtherReservationsInBetween != null) {
            $this->error = "players_already_have_booking";
            $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
            return $this->response();
        }

        try {
            \DB::beginTransaction();

            $reservation->attachPlayers($players, $parent_id, false, $newGroupSize, \Config::get('global.reservation.new_addition'));
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($request->get('reservation_id'));
            $reservation->updateReservationStatusesForAReservation();

            // Dispatch job to assess reservation status after given time delay
            foreach($reservation->reservation_players as $reservation_player){
                if(in_array($reservation_player->member_id,$players)){
                    $reservation_player->sendNotificationToPlayerForReservationConfirmation($startTime, Auth::user(),$course->name);
                    $reservation_player->dispatchMakeReservationPlayerDecisionJob();
                }

            }

            $this->response = "mobile_reservation_successfull";

            \DB::commit();
        } catch (\Exception $e) {

            \DB::rollback();
            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
    }

    public function delete(Request $request)
    {
        if (!$request->has('reservation_player_id')) {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        $reservation_player = ReservationPlayer::find($request->get('reservation_player_id'));
        $member_id = Auth::user()->id;
        if(!$reservation_player){
            $this->error = 'reservation_not_found';
            return $this->response();
        }
        if($reservation_player->member_id !== $member_id){
            $this->error = 'user_not_parent';
            return $this->response();
        }

        try {
            \DB::beginTransaction();

            $reservation_player->delete();
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
            $reservation->updateReservationStatusesForAReservation();

            $this->response = "cancel_reservation_success";
            \DB::commit();


        } catch (\Exception $e) {

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
    }

    public function acceptReservationRequest($reservation_player_id){

        $reservation_player = ReservationPlayer::find($reservation_player_id);
        $member_id = Auth::user()->id;
        if(!$reservation_player){
            $this->error = 'reservation_not_found';
            return $this->response();
        }
        if($reservation_player->member_id !== $member_id){
            $this->error = 'user_not_parent';
            return $this->response();
        }


        try {
            \DB::beginTransaction();
            //If reservation is yet pending decision i-e number of players accepted is yet less than the group_size
            //Simply confirm the reservation
            if($reservation_player->response_status == \Config::get('global.reservation.pending')) {

                if (
                    $reservation_player->reservation_status == \Config::get('global.reservation.pending_waiting') ||
                    $reservation_player->reservation_status == \Config::get('global.reservation.pending_reserved')
                ) {
                    $reservation_player->response_status = \Config::get('global.reservation.confirmed');
                    $reservation_player->save();
                    $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
                    $reservation->updateReservationStatusesForAReservation();

                    $this->response = "success_accept";
                } else {
                    $reservation_player->response_status = \Config::get('global.reservation.dropped');
                    $reservation_player->save();




                    $this->error = "group_already_complete";

                }

            }else if($reservation_player->response_status == \Config::get('global.reservation.dropped')){
                $reservation_player->parent_id = $member_id;
                $reservation_player->response_status = \Config::get('global.reservation.confirmed');
                $reservation_player->reservation_status = \Config::get('global.reservation.waiting');
                $reservation_player->group_size = 1;
                $reservation_player->save();
                $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
                $reservation->updateReservationStatusesForAReservation();



                $this->response = "success_accept";
            }

            \DB::commit();
            return $this->response();



        } catch (\Exception $e) {

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
    }

    public function declineReservationRequest($reservation_player_id){
        $reservation_player = ReservationPlayer::find($reservation_player_id);
        //Block to run only when Auth:user() is found i-e when request is explicitly from the decline request route
        //In other cases when we want to cancel a reservation such as from the queue job, same method can be used but
        //will ignore this part when Auth::user() returns null
        if($user = Auth::user()){
            $member_id = $user->id;
            if(!$reservation_player){
                $this->error = 'reservation_not_found';
                return $this->response();
            }
            if($reservation_player->member_id !== $member_id){
                $this->error = 'user_not_parent';
                return $this->response();
            }
        }




        try {
            \DB::beginTransaction();

            $reservation_player->delete();
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
            $reservation->updateReservationStatusesForAReservation();

            $this->response = "success_decline";
            \DB::commit();
            //Send message to parent
            $reservation_player->sendNotificationToParentOnRequestDeclinedByPlayer();

        } catch (\Exception $e) {

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        return $this->response();
    }


}
