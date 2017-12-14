<?php

namespace App\Http\Controllers\Mobile;

use App\Collection\AdminNotificationEventsManager;
use App\Http\Controllers\Controller;
use App\Http\Models\EntityBasedNotification;
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
        $course = Course::where("club_id",Auth::user()->club_id) ->first();
        if(!$course){
            //probably return with an error
        }

        $reservations = Course::getReservationsForACourseByIdForADateRange($course, $dayToday, $fourDaysFromNow);
        return json_decode(json_encode($reservations),true);
//        $this->response = json_decode(json_encode($reservations),true);
//        return $this->response();
    }

    public function getReservationByDate($date)
    {
        $course = Course::where("club_id",Auth::user()->club_id) ->first();
        if(!$course){
            //probably return with an error
        }
        $reservations = Course::getReservationsForACourseByIdForADateRange($course, $date, $date);
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

        //return with error if group size > 4 or guests are greater than group size -1 i-e Parent + Guests cannot
        //exceed the group size since guests are confirmed by default. Guests + Parent total more than group size
        //means we will end up with more confirmed players than the group size intended
        if ($request->get('group_size') > 4 || $request->get('guests') > ($request->get('group_size')-1) ){
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

            //Disabled on request by IOS App developer. To be enabled when he is done with testing
//            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
//            if ($playersWithOtherReservationsInBetween != null) {
//                $this->error = "players_already_have_booking";
//                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
//                return $this->response();
//            }

            $result = $this->addNewGroupToExistingReservation($request, $reservation, $course, $players, $parent_id, $startTime);


        } else {

            //Disabled on request by IOS App developer. To be enabled when he is done with testing
//            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
//            if ($playersWithOtherReservationsInBetween != null) {
//                $this->error = "players_already_have_booking";
//                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
//                return $this->response();
//            }

            $result = $this->createNewReservationAndGroup($request, $course, $players, $parent_id, $startTime);


        }
        if ($result > 0) {

            //Make entry to the entity based notifications and fire event for admin notification

            EntityBasedNotification::create([
                "club_id"=>$course->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$result,
                "entity_type"=>RoutineReservation::class
            ]);
            AdminNotificationEventsManager::broadcastReservationUpdationEvent($course->club_id);

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
            return $reservation->id;
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
            return $reservation->id;
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

        array_unshift($players, $parent_id);


        $club = Club::find($reservation->club_id);
        $course = Course::find($reservation->course_id);



        $newGroupSize = count($players) + $request->get('guests');
        if( $newGroupSize > 4){
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }

        //Validate if the player has not removed any previously added players
        $guestsPreviouslyReserved = 0;
        $newPlayers = $players  ;
        foreach ($group->players as $reservedPlayer){
            $receivedPreviouslyAddedPlayer = false;
            foreach($players as $player){
                if($reservedPlayer->member_id == $player){
                    $receivedPreviouslyAddedPlayer = true;
                    if(array_search($player,$newPlayers) !== false){
                        unset($newPlayers[array_search($player,$newPlayers)]);
                    }

                    break;
                }else if($reservedPlayer->member_id == 0){
                    $guestsPreviouslyReserved++;
                    $receivedPreviouslyAddedPlayer = true;
                    break;
                }
            }
            if(!$receivedPreviouslyAddedPlayer){

                $this->error = "removing_players_not_allowed";
                return $this->response();
            }

        }
        $newPlayers = array_values($newPlayers);
        $sumOfGroupSizesReserved = ($reservation->sumOfGroupSizes('reserved') - $group->group_size);

        if(($sumOfGroupSizesReserved+$newGroupSize) > 4){
            $this->error = "mobile_not_enough_slots_remaining";
            return $this->response();
        }

        $startTime = $reservation->reservation_time_slots->first()->time_start;
        $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime , $newPlayers);
        if ($playersWithOtherReservationsInBetween != null) {
            $this->error = "players_already_have_booking";
            $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
            return $this->response();
        }

        try {
            \DB::beginTransaction();

            //Add new guests to new players array if any
            if($guestsPreviouslyReserved < $request->get('guests')){

                for($guestCount = 0; $guestCount < ($request->get('guests') - $guestsPreviouslyReserved) ; $guestCount++){
                    array_unshift($newPlayers, "guest");
                }

            //else remove guests from previous reservation if number of guests requested is less than previous number
            }else if($guestsPreviouslyReserved > $request->get('guests')){
                $removedGuestCount = 0;
                $numberOfGuestsToRemove = $guestsPreviouslyReserved - $request->get('guests');
                foreach($group->players as $reservedPlayer){
                    if($reservedPlayer->member_id == 0){
                        $reservedPlayer->delete();
                        $removedGuestCount++;
                        if($removedGuestCount >= $numberOfGuestsToRemove){
                            break;
                        }
                    }
                }
            }

            $reservation->attachPlayers($newPlayers, $parent_id, false, $newGroupSize, \Config::get('global.reservation.new_addition'));
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($request->get('reservation_id'));
            $reservation->updateReservationStatusesForAReservation();

            // Dispatch job to assess reservation status after given time delay
            foreach($reservation->reservation_players as $reservation_player){
                if(in_array($reservation_player->member_id,$players)){
                    $reservation_player->sendNotificationToPlayerForReservationConfirmation(Carbon::parse($startTime), Auth::user(),$course->name);
                    $reservation_player->dispatchMakeReservationPlayerDecisionJob();
                }

            }

            $this->response = "mobile_reservation_successfull";

            //Make entry to the entity based notifications and fire event for admin notification

            EntityBasedNotification::create([
                "club_id"=>$course->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservation->id,
                "entity_type"=>RoutineReservation::class
            ]);
            AdminNotificationEventsManager::broadcastReservationUpdationEvent($course->club_id);


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
            //Remove any guests which have the deleted player as their parent
            foreach ($reservation->reservation_players as $player){
                if($player->parent_id == $member_id && $player->member_id == 0){
                    $player->delete();
                }
            }

            //reload reservation to reflect current state of record after deletions
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
            if($reservation->reservation_players->count() > 0){

                $reservation->updateReservationStatusesForAReservation();
                //Make entry to the entity based notifications and fire event for admin notification

                EntityBasedNotification::create([
                    "club_id"=>$reservation->club_id,
                    "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                    "entity_id"=>$reservation->id,
                    "entity_type"=>get_class($reservation)
                ]);

            }else{
                foreach ($reservation->reservation_time_slots as $timeSlot) {
                    $timeSlot->delete();
                    EntityBasedNotification::create([
                        "club_id"=>$reservation->club_id,
                        "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                        "entity_id"=>$reservation->id,
                        "entity_type"=>get_class($reservation),
                        "deleted_entity"=>json_encode(Course::generateBlankReservationForATimeSlot($timeSlot->time_start,$reservation->course_id))
                    ]);
                }

                $reservation->delete();
            }

            AdminNotificationEventsManager::broadcastReservationUpdationEvent($reservation->club_id);

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

    public function acceptReservationRequest(Request $request,$reservation_player_id){

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

        if($reservation_player->process_type == \Config::get('global.reservationsProcessTypes.final') ){
            if(!$request->has('comingOnTime') ){
                $this->error = 'must_notify_if_on_time';
                return $this->response();
            }else if( $request->get('comingOnTime') != "yes" && $request->get('comingOnTime') != "no"){
                $this->error = 'must_notify_if_on_time';
                return $this->response();
            }
        }



        try {
            \DB::beginTransaction();
            //If reservation is yet pending decision i-e number of players accepted is yet less than the group_size
            //Simply confirm the reservation
            if($reservation_player->response_status == \Config::get('global.reservation.pending')) {

                    $reservation_player->response_status = \Config::get('global.reservation.confirmed');

                    //Must set the comingOnTime flag if the process is final
                    if($reservation_player->process_type == \Config::get('global.reservationsProcessTypes.final') ){
                        if($request->get('comingOnTime') == "yes"){
                            $reservation_player->comingOnTime = \Config::get('global.comingOnTime.yes');
                        }else if($request->get('comingOnTime') == "no"){
                            $reservation_player->comingOnTime = \Config::get('global.comingOnTime.no');
                        }


                    }

                    $reservation_player->save();
                    $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
                    $reservation->updateReservationStatusesForAReservation();
                    $reservationPlayerInGroup = $reservation->getReservationPlayerEntryForAMemberByIdFromReservationGroups($reservation_player->member_id);

                    if($reservationPlayerInGroup->response_status == \Config::get('global.reservation.confirmed')){
                        $this->response = "success_accept";
                    }else if($reservationPlayerInGroup->response_status == \Config::get('global.reservation.dropped')){
                        $this->error = "group_already_complete";
                    }



            }else if($reservation_player->response_status == \Config::get('global.reservation.dropped')){
                $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
                //return with error if sum of group sizes + 1 i-e the groups already reserved + the player here to accept
                // and attempting for a reservation on his own is greater than 16
                if (($reservation->sumOfGroupSizes('both') + 1) > 16) {
                    $this->error = "mobile_slot_already_reserved";
                    return $this->response();
                }

                //continue with his reservation otherwise
                $reservation_player->parent_id = $member_id;
                $reservation_player->response_status = \Config::get('global.reservation.confirmed');
                $reservation_player->reservation_status = \Config::get('global.reservation.waiting');
                $reservation_player->group_size = 1;
                $reservation_player->save();
                $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
                $reservation->updateReservationStatusesForAReservation();



                $this->response = "mobile_reservation_successfull";
            }else{
                $this->response = "already_accepted";
            }

            //Make entry to the entity based notifications and fire event for admin notification

            EntityBasedNotification::create([
                "club_id"=>$reservation->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservation->id,
                "entity_type"=>get_class($reservation)
            ]);
            AdminNotificationEventsManager::broadcastReservationUpdationEvent($reservation->club_id);

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
            //Remove any guests which have the deleted player as their parent
            foreach ($reservation->reservation_players as $player){
                if($player->parent_id == $member_id && $player->member_id == 0){
                    $player->delete();
                }
            }

            //reload reservation to reflect current state of record after deletions
            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_player->reservation_id);
            if($reservation->reservation_players->count() > 0){

                $reservation->updateReservationStatusesForAReservation();

                //Create Entity based notification entry for the reservation from which players were moved and still has some players left
                EntityBasedNotification::create([
                    "club_id"=>$reservation->club_id,
                    "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                    "entity_id"=>$reservation->id,
                    "entity_type"=>get_class($reservation)
                ]);

            }else{
                foreach ($reservation->reservation_time_slots as $timeSlot) {
                    $timeSlot->delete();
                    //Create Entity based notification entry for the reservation from which players were moved and has to be
                    //deleted since no players are left in the reservation
                    EntityBasedNotification::create([
                        "club_id"=>$reservation->club_id,
                        "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                        "entity_id"=>$reservation->id,
                        "entity_type"=>get_class($reservation),
                        "deleted_entity"=>json_encode(Course::generateBlankReservationForATimeSlot($timeSlot->time_start,$reservation->course_id))
                    ]);
                }

                $reservation->delete();
            }


            $this->response = "success_decline";


            AdminNotificationEventsManager::broadcastReservationUpdationEvent($reservation->club_id);
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
