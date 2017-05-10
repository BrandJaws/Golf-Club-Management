<?php

namespace App\Http\Controllers\ClubAdmin\Reservations;

use App\Collection\AdminNotificationEventsManager;
use App\Http\Controllers\Controller;
use App\Http\Models\Checkin;
use App\Http\Models\EntityBasedNotification;
use App\Http\Models\ReservationPlayer;
use App\Http\Models\ReservationTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Http\Models\Club;
use App\Http\Models\Course;
use App\Http\Models\RoutineReservation;
use Carbon\Carbon;

class ReservationsController extends Controller
{
    public function index(Request $request)
    {
        AdminNotificationEventsManager::broadcastReservationUpdationEvent();
        if ($request->has('course_id'))
        {
            $course = Course::find($request->get('course_id'));
        } else
        {
            $course = Course::where("club_id",Auth::user()->club_id) ->first();

        }

        if(!$course){
            //probably return with an error
        }
        $dayToday = Carbon::today()->toDateString();
        $fourDaysFromNow = Carbon::today()->addDays(3)->toDateString();
        $reservations = Course::getReservationsForACourseByIdForADateRange($course, $dayToday, $fourDaysFromNow);



        if ($request->ajax())
        {
            return json_encode($reservations);
        } else
        {
            $coursesList = Course::where("club_id", Auth::user()->club_id)->select("id", "name")->get();
            $maxEntityBasedNotificationId = EntityBasedNotification::max('id');
            $entity_based_notification_id = $maxEntityBasedNotificationId ? $maxEntityBasedNotificationId : 0;
            return view('admin.reservations.reservations', ["reservations" => json_encode($reservations),
                                                            "courses" => $coursesList,
                                                            "entity_based_notification_id"=>$entity_based_notification_id]);
        }

    }

    public function starter(Request $request)
    {
        if ($request->has('course_id'))
        {
            $course = Course::find($request->get('course_id'));
        } else
        {
            $course = Course::where("club_id",Auth::user()->club_id) ->first();

        }

        if(!$course){
            //probably return with an error
        }
        $dayToday = Carbon::today()->toDateString();
        $reservations = Course::getReservationsForACourseByIdForADateRange($course, $dayToday, $dayToday);


        if ($request->ajax())
        {
            return json_encode($reservations);
        } else
        {
            $coursesList = Course::where("club_id", Auth::user()->club_id)->select("id", "name")->get();
            $maxEntityBasedNotificationId = EntityBasedNotification::max('id');
            $entity_based_notification_id = $maxEntityBasedNotificationId ? $maxEntityBasedNotificationId : 0;
            return view('admin.reservations.starter', ['reservations' => json_encode($reservations),
                                                       "courses" => $coursesList,
                                                       "entity_based_notification_id"=>$entity_based_notification_id]);
        }

    }

    public function getReservationByDate(Request $request, $date)
    {

        if ($request->has('course_id'))
        {
            $course = Course::find($request->get('course_id'));
        } else
        {
            $course = Course::where("club_id",Auth::user()->club_id) ->first();

        }

        if(!$course){
            //probably return with an error
        }
        $reservations = Course::getReservationsForACourseByIdForADateRange($course, $date, $date);
        return json_encode($reservations);
    }

    public function store(Request $request)
    {
        if (!$request->has('club_id'))
        {
            $this->error = "mobile_invalid_club_identifire";
            return $this->response();
        }
        $club = Club::find($request->get('club_id'));

        if (is_null($club) && count($club) < 1)
        {
            $this->error = "mobile_invalid_club";
            return $this->response();
        }
        if (!$request->has('course_id'))
        {
            $this->error = "mobile_invalid_course_identifire";
            return $this->response();
        }
        $course = Course::getCourseByClubId($request->get('course_id'), $club->id);
        if (is_null($course) && count($course) < 1)
        {
            $this->error = "mobile_invalid_court";
            return $this->response();
        }

        if (!$request->has('reserved_at'))
        {

            $reservedAt = Carbon::today()->format('Y-m-d');
        } else
        {
            $reservedAt = Carbon::parse($request->get('reserved_at'))->format('Y-m-d');
        }

        if (!$request->has('time'))
        {
            $this->error = "mobile_reservation_time_missing";
            return $this->response();
        }


        //Calculate start and end times based on duration of booking
        $startTime = Carbon::parse($request->get('time'));
        $startTime = Carbon::parse($reservedAt . " " . $startTime->toTimeString());


        if (!$request->has('player') || (is_array($request->get('player')) && empty ($request->get('player'))))
        {

            if (!$request->has('guests'))
            {
                $this->error = "player_missing";
                return $this->response();
            } else if ((int)$request->get('guests') <= 0)
            {
                $this->error = "player_missing";
                return $this->response();
            }
            $players = [];
        } else
        {
            $players = $request->get('player');
        }

        $players = array_filter($players, function ($val)
        {
            if ($val == 0 || trim($val) == "")
            {
                return false;
            } else
            {
                return true;
            }
        });

        $players = array_unique($players);

        //add number of guests as separate values to the players array
        if ($request->has('guests') && $request->get('guests') > 0)
        {
            for ($x = 0; $x < $request->get('guests'); $x++)
            {
                $players[] = "guest";
            }

        }

        if (count($players) < 1 || count($players) > 4)
        {
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }


        try
        {
            \DB::beginTransaction();


            $reservationsOnTimeSlot = $course->getResevationsAtCourseForATimeSlot($startTime);

            if ($reservationsOnTimeSlot->count() >= 1)
            {
                $this->error = "mobile_slot_already_reserved";
                return $this->response();
            }
            $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startTime, $players);
            if ($playersWithOtherReservationsInBetween != null)
            {
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

            //Make entry to the entity based notifications and fire event for admin notification

            EntityBasedNotification::create([
                "club_id"=>$course->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservation->id,
                "entity_type"=>get_class($reservation)
            ]);
            AdminNotificationEventsManager::broadcastReservationUpdationEvent();

            \DB::commit();
        } catch (\Exception $e)
        {
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


        if (!$request->has('reservation_id'))
        {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        $reservation = RoutineReservation::find($request->get('reservation_id'));


        if (!$reservation || $reservation->reservation_players == null)
        {

            $this->error = "invalid_reservation";
            return $this->response();
        }

        if (!$request->has('player') || (is_array($request->get('player')) && empty ($request->get('player'))))
        {

            if (!$request->has('guests'))
            {
                $this->error = "player_missing";
                return $this->response();
            } else if ((int)$request->get('guests') <= 0)
            {
                $this->error = "player_missing";
                return $this->response();
            }
            $players = [];
        } else
        {
            $players = $request->get('player');
        }

        //$players = array_filter ( $request->get ( 'player' ) );
        $players = array_filter($players, function ($val)
        {
            if ($val == 0 || trim($val) == "")
            {
                return false;
            } else
            {
                return true;
            }
        });
        //array_unshift($players,Auth::user ()->id);
        $players = array_unique($players);
        $club = Club::find($reservation->club_id);
        $course = Course::find($reservation->course_id);

        //add number of guests as separate values to the players array
        if ($request->has('guests') && $request->get('guests') > 0)
        {
            for ($x = 0; $x < $request->get('guests'); $x++)
            {
                $players[] = "guest";
            }

        }

        if (count($players) < 1 || count($players) > 4)
        {
            $this->error = "mobile_players_are_not_enough";
            return $this->response();
        }

        $existing_player_ids_to_be_kept = [];
        $new_players_received_including_guests = [];
        $reservation_players_to_be_updated_or_deleted = [];
        $newly_added__reservation_players = [];
        $reservationPlayersWithReservationStatusReservedOrPendingReserved = ReservationPlayer::where(function ($query)
        {

            $query->where(function ($query)
            {
                //To exclude dropped players when the status is reserved
                $query->where("reservation_status", \Config::get('global.reservation.reserved'));
                $query->where("response_status", \Config::get('global.reservation.confirmed'));
            });
            $query->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));

        })
            ->where("reservation_id", $request->get('reservation_id'))
            ->where("reservation_type", RoutineReservation::class)
            ->get();

        //In case of group bookings from mobile, group size intended can be smaller than total players sent
        //In that case we cannot update. We will only allow updation if the total players competing for the playable
        //or topmost 4 slots are less than or equal to 4

        if ($reservationPlayersWithReservationStatusReservedOrPendingReserved->count() > 4)
        {

            $this->error = "reservation_status_not_final";
            return $this->response();
        }

        //Get tennis reservation ids that need not be updated as the player ids they contain have been
        //sent with the updated players array again
        foreach ($reservationPlayersWithReservationStatusReservedOrPendingReserved as $reservationPlayer)
        {
            if (in_array((string)$reservationPlayer->member_id, $players))
            {

                $existing_player_ids_to_be_kept[] = $reservationPlayer->member_id;
            } else
            {
                $reservation_players_to_be_updated_or_deleted[] = $reservationPlayer;
            }

        }

        foreach ($players as $playerReceived)
        {
            if (!in_array((string)$playerReceived, $existing_player_ids_to_be_kept))
            {
                $new_players_received_including_guests[] = $playerReceived;
            }
        }

        $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $reservation->reservation_time_slots[0]->time_start, $new_players_received_including_guests);
        if ($playersWithOtherReservationsInBetween != null)
        {
            $this->error = "players_already_have_booking";
            $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
            return $this->response();
        }

        try
        {
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

            for ($x = 0; $x < count($new_players_received_including_guests); $x++)
            {
                if (count($reservation_players_to_be_updated_or_deleted) > 0)
                {


                    if ($new_players_received_including_guests[$x] == "guest")
                    {
                        $reservation_players_to_be_updated_or_deleted[0]->member_id = 0;

                    } else
                    {
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

                } else
                {
                    break;
                }

            }


            if (count($reservation_players_to_be_updated_or_deleted))
            {
                foreach ($reservation_players_to_be_updated_or_deleted as $reservationPlayer)
                {
                    $reservationPlayer->delete();
                }
            }

            if (count($new_players_received_including_guests))
            {

                $reservation->attachPlayers($new_players_received_including_guests, 0, true, 1, \Config::get('global.reservation.reserved'));
            }

            // To update groupsizes possibly changed due to updation

            $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($request->get('reservation_id'));
            $reservation->updateReservationStatusesForAReservation();

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

            //Make entry to the entity based notifications and fire event for admin notification

            EntityBasedNotification::create([
                "club_id"=>$course->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservation->id,
                "entity_type"=>get_class($reservation)
            ]);
            AdminNotificationEventsManager::broadcastReservationUpdationEvent();


            \DB::commit();
        } catch (\Exception $e)
        {
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
        if (!isset ($reservation_id) || ( int )$reservation_id === 0)
        {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }

        $reservation = RoutineReservation::where("id", $reservation_id)->with("reservation_players")->first();
        // dd($reservation);
        if ($reservation == null)
        {

            $this->error = "invalid_reservation";
            return $this->response();
        } else
        {

            try
            {
                \DB::beginTransaction();

                foreach ($reservation->reservation_players as $player)
                {
                    if ($player->reservation_status == \Config::get('global.reservation.reserved') || $player->reservation_status == \Config::get('global.reservation.pending_reserved'))
                    {
                        $player->delete();
                    }

                }

                $reservation = RoutineReservation::findAndGroupReservationForReservationProcess($reservation_id);
                if ($reservation->reservation_players->count() > 0)
                {

                    $reservation->updateReservationStatusesForAReservation();

                    //Make entry to the entity based notifications and fire event for admin notification

                    EntityBasedNotification::create([
                        "club_id"=>$reservation->club_id,
                        "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                        "entity_id"=>$reservation->id,
                        "entity_type"=>get_class($reservation)
                    ]);


                } else
                {
                    foreach ($reservation->reservation_time_slots as $timeSlot)
                    {
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

                AdminNotificationEventsManager::broadcastReservationUpdationEvent();
                //dd($reservationResponseIfSucceeds);
                $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
                $this->response = $firstReservationsOnTimeSlots;
                \DB::commit();

            } catch (\Exception $e)
            {
                dd($e);
                \Log::info(__METHOD__, [
                    'error' => $e->getMessage()
                ]);
                $this->error = "exception";
            }
        }
        return $this->response();
    }

    public function movePlayers(Request $request)
    {

        //return ($request->all());
        if (

            !$request->has('reservationPlayerIdsToBeMoved') ||
            !is_array($request->get('reservationPlayerIdsToBeMoved')) ||
            (is_array($request->get('reservationPlayerIdsToBeMoved')) && empty ($request->get('reservationPlayerIdsToBeMoved')))

        )
        {

            $this->error = "player_missing";
            return $this->response();

        }

        $reservationPlayers = ReservationPlayer::whereIn('id', $request->get('reservationPlayerIdsToBeMoved'))->get();

        if ($reservationPlayers->count() == 0)
        {
            $this->error = "player_missing";
            return $this->response();
        }
        $memberIdsForPlayers = [];
        foreach ($reservationPlayers as $reservationPlayer)
        {
            $memberIdsForPlayers[] = $reservationPlayer->member_id;
        }

        //Following Variables Will be used at the end of the process when we would have possibly changes the reservation_id
        //for the player. We will then have no other way to refer to the reservation from which the player has been moved

        $reservationIdFromWhichPlayersHaveToBeMoved = $reservationPlayers[0]->reservation_id;
        $timeSlotsForReservationFromWhichPlayersHaveToBeMoved = ReservationTimeSlot::where('reservation_id', $reservationIdFromWhichPlayersHaveToBeMoved)
            ->where("reservation_type", RoutineReservation::class)
            ->get();
        if (!$request->has('course_id'))
        {
            $this->error = "mobile_invalid_course_identifire";
            return $this->response();
        }

        $course = Course::find($request->get('course_id'));

        if (!$course)
        {
            $this->error = "mobile_invalid_court";
            return $this->response();
        }

        DB::beginTransaction();

        //If we have the reservation id, we can find the reservation and proceed with the process
        if ($request->has('reservationIdToMoveTo'))
        {


            $reservationToMoveTo = RoutineReservation::where("id", $request->get('reservationIdToMoveTo'))->with('reservation_time_slots')->first();
            if (!$reservationToMoveTo)
            {
                $this->error = "invalid_reservation";
                return $this->response();
            }
            $course = Course::where("id", $reservationToMoveTo->course_id)->with("club")->first();

            $playersWithOtherReservationsInBetween = $course->club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $reservationToMoveTo->reservation_time_slots[0]->time_start, $memberIdsForPlayers, [$reservationPlayers[0]->reservation_id]);

            if ($playersWithOtherReservationsInBetween != null)
            {
                $this->error = "players_already_have_booking";
                $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                return $this->response();
            }

            $reservedOrPendingReservedCountForReservation = ReservationPlayer::where("reservation_id", $reservationToMoveTo->id)
                ->where("reservation_type", RoutineReservation::class)
                ->where(function ($query)
                {
                    $query->where("reservation_status", \Config::get('global.reservation.reserved'))
                        ->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));

                })
                ->count('group_size');
            if ($reservedOrPendingReservedCountForReservation + $reservationPlayers->count() > 4)
            {
                $this->error = "mobile_slot_already_reserved";
                return $this->response();
            }

            //Proceed to move players
            foreach ($reservationPlayers as $reservationPlayer)
            {
                $reservationPlayer->reservation_id = $reservationToMoveTo->id;
                $reservationPlayer->parent_id = $reservationPlayer->member_id === 0 ? null : $reservationPlayer->member_id;
                $reservationPlayer->save();
            }


            $timeSlotsForBothReservations = $reservationToMoveTo->reservation_time_slots->merge($timeSlotsForReservationFromWhichPlayersHaveToBeMoved);


            //Else we need to find a reservation for the timeslot or create a new reservation at that timeslot if none found
        } else
        {

            if (!$request->has('reservationTimeSlotToMoveTo'))
            {

                $this->error = "mobile_reservation_time_missing";
                return $this->response();
            }

            if (!$request->has('reservationDateToMoveTo'))
            {

                $this->error = "date_time_not_found";
                return $this->response();
            }

            $startDateTime = Carbon::parse(Carbon::parse($request->get('reservationDateToMoveTo'))->format('Y-m-d') . " " . Carbon::parse($request->get('reservationTimeSlotToMoveTo'))->toTimeString());
            $reservationsOnTimeSlot = $course->getResevationsAtCourseForATimeSlot($startDateTime);

            if ($reservationsOnTimeSlot->count() >= 1)
            {
                if ($reservationsOnTimeSlot[0]->reservation_type != RoutineReservation::class)
                {
                    $this->error = "cant_move_to_different_type_of_reservation";
                    return $this->response();
                } else
                {
                    $reservationToMoveTo = new RoutineReservation();
                    $reservationToMoveTo->id = $reservationsOnTimeSlot[0]->reservation_id;
                    $reservationToMoveTo->course_id = $reservationsOnTimeSlot[0]->course_id;
                    $reservationToMoveTo->club_id = $reservationsOnTimeSlot[0]->club_id;

                }

                $reservedOrPendingReservedCountForReservation = ReservationPlayer::where("reservation_id", $reservationsOnTimeSlot[0]->reservation_id)
                    ->where("reservation_type", RoutineReservation::class)
                    ->where(function ($query)
                    {
                        $query->where("reservation_status", \Config::get('global.reservation.reserved'))
                            ->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));

                    })
                    ->count('group_size');


                if ($reservedOrPendingReservedCountForReservation + $reservationPlayers->count() > 4)
                {
                    $this->error = "mobile_slot_already_reserved";
                    return $this->response();
                }

                //Proceed to move players
                foreach ($reservationPlayers as $reservationPlayer)
                {
                    $reservationPlayer->reservation_id = $reservationToMoveTo->id;
                    $reservationPlayer->parent_id = $reservationPlayer->member_id === 0 ? null : $reservationPlayer->member_id;
                    $reservationPlayer->save();
                }

                $timeSlotsForBothReservations = $reservationToMoveTo->reservation_time_slots->merge($timeSlotsForReservationFromWhichPlayersHaveToBeMoved);

            } else
            {
                if (!$request->has('club_id'))
                {
                    $this->error = "mobile_invalid_club_identifire";
                    return $this->response();
                }

                $club = Club::find($request->get('club_id'));

                if (is_null($club) && count($club) < 1)
                {
                    $this->error = "mobile_invalid_club";
                    return $this->response();
                }


                $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startDateTime, $memberIdsForPlayers, [$reservationPlayers[0]->reservation_id]);
                if ($playersWithOtherReservationsInBetween != null)
                {
                    $this->error = "players_already_have_booking";
                    $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                    return $this->response();
                }
                $reservationData ['club_id'] = $course->club_id;
                $reservationData ['course_id'] = $course->id;

                $reservationToMoveTo = RoutineReservation::create($reservationData);
                $reservationToMoveTo->attachTimeSlot($startDateTime);

                //Proceed to move players
                foreach ($reservationPlayers as $reservationPlayer)
                {
                    $reservationPlayer->reservation_id = $reservationToMoveTo->id;
                    $reservationPlayer->parent_id = $reservationPlayer->member_id === 0 ? null : $reservationPlayer->member_id;
                    $reservationPlayer->save();
                }


                $timeSlotsForBothReservations = $reservationToMoveTo->reservation_time_slots->merge($timeSlotsForReservationFromWhichPlayersHaveToBeMoved);


            }

        }
        //Create Entity based notification entry for the reservation to which players were moved
        EntityBasedNotification::create([
            "club_id"=>$reservationToMoveTo->club_id,
            "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
            "entity_id"=>$reservationToMoveTo->id,
            "entity_type"=>get_class($reservationToMoveTo)
        ]);

        $reservationFromWhichPlayerWasMoved = RoutineReservation::findAndGroupReservationForReservationProcess($reservationIdFromWhichPlayersHaveToBeMoved);
        if ($reservationFromWhichPlayerWasMoved->reservation_players->count() > 0)
        {

            $reservationFromWhichPlayerWasMoved->updateReservationStatusesForAReservation();
            //Create Entity based notification entry for the reservation from which players were moved and still has some players left
            EntityBasedNotification::create([
                "club_id"=>$reservationFromWhichPlayerWasMoved->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservationFromWhichPlayerWasMoved->id,
                "entity_type"=>get_class($reservationFromWhichPlayerWasMoved)
            ]);


        } else
        {
            foreach ($reservationFromWhichPlayerWasMoved->reservation_time_slots as $timeSlot)
            {
                $timeSlot->delete();
                //Create Entity based notification entry for the reservation from which players were moved and has to be
                //deleted since no players are left in the reservation
                EntityBasedNotification::create([
                    "club_id"=>$reservationFromWhichPlayerWasMoved->club_id,
                    "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                    "entity_id"=>$reservationFromWhichPlayerWasMoved->id,
                    "entity_type"=>get_class($reservationFromWhichPlayerWasMoved),
                    "deleted_entity"=>json_encode(Course::generateBlankReservationForATimeSlot($timeSlot->time_start,$reservationFromWhichPlayerWasMoved->course_id))
                ]);
            }

            $reservationFromWhichPlayerWasMoved->delete();
        }

        DB::commit();

        $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($course->id, $timeSlotsForBothReservations);
        $this->response = $firstReservationsOnTimeSlots;

        AdminNotificationEventsManager::broadcastReservationUpdationEvent();

        return $this->response();
    }

    public function swapTimeSlots(Request $request)
    {

        if (!$request->has('reservationIdFirst'))
        {

            $this->error = "player_missing";
            return $this->response();

        }

        $reservationFirst = RoutineReservation::where('id', $request->get('reservationIdFirst'))->with('reservation_time_slots')->first();
        if (!$reservationFirst)
        {
            $this->error = "invalid_reservation";
            return $this->response();

        }


        $reservationPlayersFirst = ReservationPlayer::where('reservation_id', $reservationFirst->id)
            ->where("reservation_type", RoutineReservation::class)
            ->where(function ($query)
            {
                $query->where("reservation_status", \Config::get('global.reservation.reserved'))
                    ->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));
            })
            ->get();
        $memberIdsFirst = [];
        foreach ($reservationPlayersFirst as $reservationPlayer)
        {
            $memberIdsFirst[] = $reservationPlayer->member_id;
        }

        if (!$request->has('course_id'))
        {
            $this->error = "mobile_invalid_course_identifire";
            return $this->response();
        }

        $course = Course::find($request->get('course_id'));

        if (!$course)
        {
            $this->error = "mobile_invalid_court";
            return $this->response();
        }
        if (!$request->has('club_id'))
        {
            $this->error = "mobile_invalid_club_identifire";
            return $this->response();
        }

        $club = Club::find($request->get('club_id'));

        if (!$club)
        {
            $this->error = "mobile_invalid_club";
            return $this->response();
        }

        try
        {


            DB::beginTransaction();

            //If we have the reservation id, we can find the reservation and proceed with the process
            if ($request->has('reservationIdSecond'))
            {


                $reservationSecond = RoutineReservation::where("id", $request->get('reservationIdSecond'))->with('reservation_time_slots')->first();
                if (!$reservationSecond)
                {
                    $this->error = "invalid_reservation";
                    return $this->response();
                }
                $secondReservationIsNewReservation = false;
                $reservationPlayersSecond = ReservationPlayer::where('reservation_id', $reservationSecond->id)
                    ->where("reservation_type", RoutineReservation::class)
                    ->where(function ($query)
                    {
                        $query->where("reservation_status", \Config::get('global.reservation.reserved'))
                            ->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));
                    })
                    ->get();
                $memberIdsSecond = [];
                foreach ($reservationPlayersSecond as $reservationPlayer)
                {
                    $memberIdsSecond[] = $reservationPlayer->member_id;
                }
                $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $reservationFirst->reservation_time_slots[0]->time_start, $memberIdsFirst,[$reservationFirst->id]);
                if ($playersWithOtherReservationsInBetween != null) {
                    $this->error = "players_already_have_booking";
                    $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                    return $this->response();
                }

                //Proceed to swap

                foreach ($reservationPlayersFirst as $reservationPlayer)
                {
                    $reservationPlayer->reservation_id = $reservationSecond->id;
                    $reservationPlayer->save();
                }
                foreach ($reservationPlayersSecond as $reservationPlayer)
                {
                    $reservationPlayer->reservation_id = $reservationFirst->id;
                    $reservationPlayer->save();
                }


                //Else we need to find a reservation for the timeslot or create a new reservation at that timeslot if none found
            } else
            {

                if (!$request->has('reservationTimeSlotSecond'))
                {

                    $this->error = "mobile_reservation_time_missing";
                    return $this->response();
                }

                if (!$request->has('reservationDateSecond'))
                {

                    $this->error = "date_time_not_found";
                    return $this->response();
                }

                $startDateTime = Carbon::parse(Carbon::parse($request->get('reservationDateSecond'))->format('Y-m-d') . " " . Carbon::parse($request->get('reservationTimeSlotSecond'))->toTimeString());
                $reservationsOnTimeSlot = $course->getResevationsAtCourseForATimeSlot($startDateTime);

                if ($reservationsOnTimeSlot->count() >= 1)
                {
                    if ($reservationsOnTimeSlot[0]->reservation_type != RoutineReservation::class)
                    {
                        $this->error = "cant_move_to_different_type_of_reservation";
                        return $this->response();
                    } else
                    {
                        $reservationSecond = new RoutineReservation();
                        $reservationSecond->id = $reservationsOnTimeSlot[0]->reservation_id;
                        $reservationSecond->course_id = $reservationsOnTimeSlot[0]->course_id;
                        $secondReservationIsNewReservation = false;
                    }

                    $reservationPlayersSecond = ReservationPlayer::where('reservation_id', $reservationSecond->id)
                        ->where("reservation_type", RoutineReservation::class)
                        ->where(function ($query)
                        {
                            $query->where("reservation_status", \Config::get('global.reservation.reserved'))
                                ->orWhere("reservation_status", \Config::get('global.reservation.pending_reserved'));
                        })
                        ->get();
                    $memberIdsSecond = [];
                    foreach ($reservationPlayersSecond as $reservationPlayer)
                    {
                        $memberIdsSecond[] = $reservationPlayer->member_id;
                    }
                    $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startDateTime, $memberIdsFirst,[$reservationFirst->id]);
                    if ($playersWithOtherReservationsInBetween != null) {
                        $this->error = "players_already_have_booking";
                        $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                        return $this->response();
                    }

                    //Proceed to swap
                    foreach ($reservationPlayersFirst as $reservationPlayer)
                    {
                        $reservationPlayer->reservation_id = $reservationSecond->id;
                        $reservationPlayer->save();
                    }
                    foreach ($reservationPlayersSecond as $reservationPlayer)
                    {
                        $reservationPlayer->reservation_id = $reservationFirst->id;
                        $reservationPlayer->save();
                    }


                } else
                {

                    $playersWithOtherReservationsInBetween = $club->getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($course, $startDateTime, $memberIdsFirst,[$reservationFirst->id]);
                    if ($playersWithOtherReservationsInBetween != null) {
                        $this->error = "players_already_have_booking";
                        $this->responseParameters["player_names"] = $playersWithOtherReservationsInBetween;
                        return $this->response();
                    }
                    $reservationData ['club_id'] = $course->club_id;
                    $reservationData ['course_id'] = $course->id;

                    $reservationSecond = RoutineReservation::create($reservationData);
                    $reservationSecond->attachTimeSlot($startDateTime);

                    $secondReservationIsNewReservation = true;

                    //Proceed to swap
                    foreach ($reservationPlayersFirst as $reservationPlayer)
                    {
                        $reservationPlayer->reservation_id = $reservationSecond->id;
                        $reservationPlayer->save();
                    }


                }

            }
            $timeSlotsForBothReservations = $reservationSecond->reservation_time_slots->merge($reservationFirst->reservation_time_slots);
            $reservationFirst = RoutineReservation::findAndGroupReservationForReservationProcess($reservationFirst->id);
            if ($reservationFirst->reservation_players->count() > 0)
            {

                $reservationFirst->updateReservationStatusesForAReservation();
                //Create Entity based notification entry for the reservation from which players were moved and still has some players left
                EntityBasedNotification::create([
                    "club_id"=>$reservationFirst->club_id,
                    "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                    "entity_id"=>$reservationFirst->id,
                    "entity_type"=>get_class($reservationFirst)
                ]);

            } else
            {
                foreach ($reservationFirst->reservation_time_slots as $timeSlot)
                {
                    $timeSlot->delete();
                    //Create Entity based notification entry for the first reservation if no players are left
                    EntityBasedNotification::create([
                        "club_id"=>$reservationFirst->club_id,
                        "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                        "entity_id"=>$reservationFirst->id,
                        "entity_type"=>get_class($reservationFirst),
                        "deleted_entity"=>json_encode(Course::generateBlankReservationForATimeSlot($timeSlot->time_start,$reservationFirst->course_id))
                    ]);
                }

                $reservationFirst->delete();
            }

            //Process of reviewing for second reservation only if its not a newly created one since if its new
            //the only players it has are the ones that have been transferred from the first reservation
            if (!$secondReservationIsNewReservation)
            {
                $reservationSecond = RoutineReservation::findAndGroupReservationForReservationProcess($reservationSecond->id);
                $reservationSecond->updateReservationStatusesForAReservation();

            }

            //Create Entity based notification entry for the second reservation
            EntityBasedNotification::create([
                "club_id"=>$reservationSecond->club_id,
                "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                "entity_id"=>$reservationSecond->id,
                "entity_type"=>get_class($reservationSecond)
            ]);

            DB::commit();
        } catch (\Exception $e)
        {
            DB::rollBack();
            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error = "exception";
        }

        $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($course->id, $timeSlotsForBothReservations);
        $this->response = $firstReservationsOnTimeSlots;

        AdminNotificationEventsManager::broadcastReservationUpdationEvent();

        return $this->response();
    }

    public function markGameStatusAsStarted(Request $request)
    {
        if (!$request->has('reservation_id'))
        {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        $reservation = RoutineReservation::find($request->get('reservation_id'));


        if (!$reservation)
        {

            $this->error = "invalid_reservation";
            return $this->response();
        }

        if ($reservation->game_status != \Config::get('global.gameStatuses.not_started'))
        {
            $this->error = "game_already_started";
            return $this->response();
        }

        DB::beginTransaction();

        $reservation->game_status = \Config::get('global.gameStatuses.started');
        $reservation->save();

        //Create Entity based notification entry for the reservation
        EntityBasedNotification::create([
            "club_id"=>$reservation->club_id,
            "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
            "entity_id"=>$reservation->id,
            "entity_type"=>get_class($reservation)
        ]);

        DB::commit();

        $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation->course_id, $reservation->reservation_time_slots);
        $this->response = $firstReservationsOnTimeSlots;
        AdminNotificationEventsManager::broadcastReservationUpdationEvent();
        return $this->response();

    }

    public function checkinPlayer(Request $request){

        if (!$request->has('reservationPlayerId')) {

            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }

        $reservation_player = ReservationPlayer::find($request->get('reservationPlayerId'));

        if(!$reservation_player){
            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }
        if($reservation_player->member_id == 0){
            $this->error = "guests_cant_checkin";
            return $this->response();
        }

        if(!$request->has('onTime') ){
            $this->error = 'must_notify_if_on_time';
            return $this->response();
        }else if( $request->get('onTime') != "1" && $request->get('onTime') != "0"){
            $this->error = 'must_notify_if_on_time';
            return $this->response();
        }


        if(!Checkin::memberHasAlreadyRecordedClubEntryForAReservation($reservation_player->reservation_id,$reservation_player->reservation_type, $reservation_player->member_id)){
            try{
                DB::beginTransaction();
                Checkin::create([
                    'beacon_id'=>0,
                    'reservation_id'=>$reservation_player->reservation_id,
                    'reservation_type'=>$reservation_player->reservation_type,
                    'member_id'=>$reservation_player->member_id,
                    'checkinTime'=>Carbon::now()->toDateTimeString(),
                    'action'=>\Config::get ( 'global.beacon_actions.clubEntry' ),
                    'recordedBy'=>"admin",
                    'onTime'=>(int)$request->get('onTime'),
                ]);
                //Create Entity based notification entry for the reservation from which players were moved and still has some players left
                EntityBasedNotification::create([
                    "club_id"=>$reservation_player->reservation->club_id,
                    "event"=>AdminNotificationEventsManager::$ReservationUpdationEvent,
                    "entity_id"=>$reservation_player->reservation_id,
                    "entity_type"=>$reservation_player->reservation_type,
                ]);
                AdminNotificationEventsManager::broadcastReservationUpdationEvent();


                DB::commit();

                $firstReservationsOnTimeSlots = Course::getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($reservation_player->reservation->course_id, $reservation_player->reservation->reservation_time_slots);
                $this->response = $firstReservationsOnTimeSlots;



            }catch(\Exception $e){
                dd( $e);
                DB::rollBack();
            }

        }else{

            $this->error = "already_checked_in";


        }

        return $this->response();
    }

}
