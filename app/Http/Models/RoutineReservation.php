<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoutineReservation extends Model
{
    

    protected $fillable = [
        'id',
        'club_id',
        'course_id',
        'gameStatus'
    ];

    public function reservation_time_slots()
    {
        return $this->morphMany("App\Http\Models\ReservationTimeSlot", "reservation");
    }

    public function scores()
    {
        return $this->morphMany("App\Http\Models\Score", "reservation");
    }


    public function reservation_players()
    {
        return $this->morphMany("App\Http\Models\ReservationPlayer", "reservation");
    }

    public function attachPlayers($players, $parent, $confirmAll, $group_size, $reservation_status, $comingOnTime = false)
    {
        if (is_array($players) && !empty ($players)) {
            foreach ($players as $player) {

                $response_status = ($parent && $player == $parent) || $player == "guest" || $confirmAll ? \Config::get('global.reservation.confirmed') : \Config::get('global.reservation.pending');
                $memberId = $player == "guest" ? 0 : $player;
                //if there isn't a parent and player is guest set parent to null
                //if there isn't a parent and member is registered set his own id as parent
                //else set parent to the provided value
                if($parent == 0){
                    if($player == "guest"){
                        $parentId = null;
                    }else{
                        $parentId = $player;
                    }
                }else{
                    $parentId = $parent;
                }


                $playerData = [];
                $playerData["reservation_id"] =$this->id;
                $playerData["reservation_type"] =self::class;
                $playerData["member_id"] = $memberId;
                $playerData["parent_id"] = $parentId;
                $playerData["group_size"] = $group_size;
                $playerData["response_status"] = $response_status;
                $playerData["reservation_status"] =$reservation_status;
                if($comingOnTime){
                    $playerData["comingOnTime"] =$comingOnTime;
                }

                ReservationPlayer::create($playerData);

                
            }

        }



    }

    public function attachTimeSlot($timeSlot)
    {

        ReservationTimeSlot::create([
            'reservation_id' => $this->id,
            'reservation_type' => self::class,
            'time_start' => $timeSlot
        ]);

    }


    /**
     * Updates reservation statuses for a reservation
     * Shrinks group size if the number of players falls below intended group size
     * Changes status from pending to reserved or waiting if the intended group size is matched
     * Promotes from waiting to reserved if the slots on top are available
     *
     *
     */
    public function updateReservationStatusesForAReservation()
    {
        if ($this->reservation_groups) {

            foreach ($this->reservation_groups as $group) {

                //Expand existing reservation if there are new additions
                $newAdditions = $this->playersInAGroupWithReservationStatusNewAddition($group);
                if ($newAdditions && $group->reservation_status == \Config::get('global.reservation.reserved')) {
                    $group->group_size += $newAdditions;
                    $group->reservation_status = \Config::get('global.reservation.pending_reserved');
                    foreach ($group->players as $player) {

                            $player->reservation_status = \Config::get('global.reservation.pending_reserved');
                            $player->group_size = $group->group_size;
                            $player->save();
                  
                    }

                }

                //Shrink group size if number of remaining players is less than group size
                if (count($group->players) < $group->group_size) {
                    $group->group_size = count($group->players);
                    foreach ($group->players as $player) {
                        if ($player->group_size != count($group->players)) {
                            $player->group_size = count($group->players);
                            $player->save();
                        }
                    }

                }

                //change status to reserved or waiting from pending based on the number of members who have accepted
                if ($this->playersInAGroupWithResponseStatusConfirmed($group) >= $group->group_size) {

                        //On group's status change upadte statuses for all the players which will be reflected as
                        //group reservation status
                        if ($group->reservation_status == \Config::get('global.reservation.pending_reserved') ||
                            $group->reservation_status == \Config::get('global.reservation.pending_waiting')) {

                            if($group->reservation_status == \Config::get('global.reservation.pending_reserved')){
                                $group->reservation_status = \Config::get('global.reservation.reserved');
                            }else{
                                $group->reservation_status = \Config::get('global.reservation.waiting');
                            }

                            foreach ($group->players as $index => $player) {

                                //change status from confirmed to dropped if the player index is greater than or equal to
                                //the group size
                                if($index >= $group->group_size && $player->response_status == \Config::get('global.reservation.confirmed')){
                                    $player->response_status = \Config::get('global.reservation.dropped');
                                    $player->save();
                                    $player->dispatchMakeReservationPlayerDecisionJob();
                                }

                                if ($player->reservation_status == \Config::get('global.reservation.pending_reserved')) {
                                    $player->reservation_status = \Config::get('global.reservation.reserved');
                                    $player->save();

                                    //Dispatch Final Cycle Job When the status is reserved
                                    if($player->response_status ==  \Config::get('global.reservation.confirmed')){
                                        $player->dispatchMakeReservationDecisionJobForFinalCycle();
                                    }

                                } else if ($player->reservation_status == \Config::get('global.reservation.pending_waiting')) {
                                    $player->reservation_status = \Config::get('global.reservation.waiting');
                                    $player->save();
                                }
                            }

                         //When group's status has already been changed to confirmed or waiting, change the status of
                         //any other confirmed players to dropped. 

                        } else if ($group->reservation_status == \Config::get('global.reservation.reserved') ||
                                   $group->reservation_status == \Config::get('global.reservation.waiting')) {

                            foreach ($group->players as $index => $player) {

                                    //change status from confirmed to dropped if the player index is greater than or equal to
                                    //the group size
                                    if($index >= $group->group_size && $player->response_status == \Config::get('global.reservation.confirmed')){

                                        $player->response_status = \Config::get('global.reservation.dropped');
                                        $player->save();
                                        $player->dispatchMakeReservationPlayerDecisionJob();

                                    }

                            }

                        }

                }

                //promote group from waiting to reserved if eligible
                $sumOfGroupSizesReserved = $this->sumOfGroupSizes('reserved');

                if (
                     ($group->reservation_status == \Config::get('global.reservation.pending_waiting') ||
                      $group->reservation_status == \Config::get('global.reservation.waiting') ) &&
                      $sumOfGroupSizesReserved < 4 &&
                      $group->group_size <= (4-$sumOfGroupSizesReserved)

                ){
                    if ($group->reservation_status == \Config::get('global.reservation.pending_waiting')) {
                        $group->reservation_status = \Config::get('global.reservation.pending_reserved');

                    } else if ($group->reservation_status == \Config::get('global.reservation.waiting')) {
                        $group->reservation_status = \Config::get('global.reservation.reserved');

                    }
                    foreach ($group->players as $player) {
                        if ($player->reservation_status == \Config::get('global.reservation.pending_waiting')) {
                            $player->reservation_status = \Config::get('global.reservation.pending_reserved');
                            $player->save();
                        } else if ($player->reservation_status == \Config::get('global.reservation.waiting')) {
                            $player->reservation_status = \Config::get('global.reservation.reserved');
                            $player->save();
                            //Dispatch Final Cycle Job When the status is reserved
                            if($player->response_status ==  \Config::get('global.reservation.confirmed')){
                                $player->dispatchMakeReservationDecisionJobForFinalCycle();
                            }
                        }
                    }
                }


            }
            //dd($this->reservation_groups);



        }
    }


    public function playersInAGroupWithResponseStatusConfirmed($group)
    {
        $confirmedPlayers = 0;
        foreach ($group->players as $player) {
            if ($player->response_status == \Config::get('global.reservation.confirmed')) {
                $confirmedPlayers++;
            }
        }

        return $confirmedPlayers;
    }

    public function playersInAGroupWithResponseStatusPending($group)
    {
        $pendingPlayers = 0;
        foreach ($group->players as $player) {
            if ($player->response_status == \Config::get('global.reservation.pending')) {
                $pendingPlayers++;
            }
        }

        return $pendingPlayers;
    }
    public function playersInAGroupWithReservationStatusNewAddition($group)
    {
        $newAdditions = 0;
        foreach ($group->players as $player) {
            if ($player->reservation_status == \Config::get('global.reservation.new_addition')) {
                $newAdditions++;
            }
        }

        return $newAdditions;
    }

    public function getGroupByParentId($parent_id)
    {

        if ($this->reservation_groups) {
            $groupFound = null;
            foreach ($this->reservation_groups as $group) {
                if ($group->parent_id == $parent_id) {
                    $groupFound = $group;
                    break;
                }

            }
            return $groupFound;
        } else {
            return null;
        }
    }


    /**
     * @param $member_id
     * @return ReservationPlayer
     */
    public function getReservationPlayerEntryForAMemberByIdFromReservationGroups($member_id)
    {

        if ($this->reservation_groups) {

            foreach ($this->reservation_groups as $group) {
                foreach($group->players as $player){
                    if($player->member_id == $member_id){

                        return $player;
                    }
                }

            }
            return null;
        } else {
            return null;
        }
    }

    /**
     * @param string $reservation_status
     * @return int|null
     *
     * counts and returns no. of players with a status reserved + groupsizes for groups that are pending reserved
     * i-e method will return a number that will reflect the number of players that will eventually play
     * values for reservation status can be
     * -reserved
     * -both
     */
    public function sumOfGroupSizes($reservation_status)
    {

        if ($this->reservation_groups) {
            $sum = 0;
            foreach ($this->reservation_groups as $group) {
                if ($reservation_status == "reserved") {
                    if ($group->reservation_status == \Config::get('global.reservation.reserved') ||
                        $group->reservation_status == \Config::get('global.reservation.pending_reserved')
                    ) {
                        $sum += $group->group_size;
                    }
                } else if ($reservation_status == "both") {
                    $sum += $group->group_size;
                }

            }
            return $sum;
        } else {
            return null;
        }
    }

    /**
     * @param int $reservation_id
     * @return RoutineReservation
     *
     * finds a reservation by id and then processes it to make groups based on parent, ordering time etc criteria
     * to facilitate the process of reservations
     */
    public static function findAndGroupReservationForReservationProcess($reservation_id)
    {

        $reservation = RoutineReservation::where('id', $reservation_id)->with(['reservation_players' => function ($query) {
            $query->orderBy('parent_id', 'asc');
            $query->orderBy('response_status', 'asc');
            $query->orderBy('updated_at', 'asc');
        }])->first();
        $reservation_groups = [];

        if ($reservation && $reservation->reservation_players) {

            $groupCount = -1;
            $tempParentId = 0;


            foreach ($reservation->reservation_players as $reservationPlayer) {
                if ($tempParentId != $reservationPlayer->parent_id || $reservationPlayer->parent_id == null ) {
                    $groupCount++;
                    $tempParentId = $reservationPlayer->parent_id;
                    $reservation_groups[$groupCount] = new \stdClass();
                    $reservation_groups[$groupCount]->parent_id = $reservationPlayer->parent_id;
                    $reservation_groups[$groupCount]->reservation_status = $reservationPlayer->reservation_status;
                    $reservation_groups[$groupCount]->group_size = $reservationPlayer->group_size;
                    $reservation_groups[$groupCount]->created_at = $reservationPlayer->created_at;
                    $reservation_groups[$groupCount]->players = [];

                }
                $reservation_groups[$groupCount]->players[] = $reservationPlayer;

            }
            usort($reservation_groups, function ($a, $b) {

                return $a->created_at->diffInSeconds($b->created_at);
            });

            $reservation->reservation_groups = $reservation_groups;
        }


        return $reservation;


    }


    public function modifyReservationObjectForReponseOnCRUDOperations()
    {
        $players = [];
        $this->load('reservation_players.member', 'reservation_time_slots');
        foreach ($this->reservation_players as $player) {
            $players[] = ["reservation_player_id" => $player->id,
                "member_id" => $player->member != null ? $player->member->id : 0,
                "member_name" => $player->member != null ? $player->member->firstName . " " . $player->member->lastName : 'Guest'
            ];
        }
        $this->reservation_id = $this->id;
        $this->reservation_type = RoutineReservation::class;
        $this->players = $players;

        $reserved_at = null;
        $timeSlots = [];
        foreach ($this->reservation_time_slots as $timeSlot) {
            if ($reserved_at == null) {
                $reserved_at = Carbon::parse($timeSlot->time_start)->toDateString();
            }
            $timeSlots[] = Carbon::parse($timeSlot->time_start)->format('h:i A');
        }

        $this->reserved_at = $reserved_at;
        $this->timeSlots = $timeSlots;

        unset($this->id);
        unset($this->nextJobToProcess);
        unset($this->reservation_players);


    }


}
