<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Checkin extends Model
{
    protected $table = 'checkins';
    public $timestamps = false;
    public static $checkinTimeFloorInSeconds = -900;
    public static $checkinTimeCeilingInSeconds = 900;
    public static $timeBeforeANewCheckinIsAllowedInSeconds = 14400;

    public static function clubEntryCheckinIsAllowed($beacon_id,$member_id){

        $date = Carbon::now()->toDateString();
        $dateTime = Carbon::now()->toDateTimeString();




        $validReservationsTodayForMember = RoutineReservation::leftJoin('reservation_players', function ($join) {
                                                                        $join->on('routine_reservations.id', '=','reservation_players.reservation_id');
                                                                         //->where('reservation_players.reservation_type', RoutineReservation::class);
                                                                 })
                                                               ->leftJoin('reservation_time_slots', function ($join) {
                                                                       $join->on('routine_reservations.id', '=', 'reservation_time_slots.reservation_id');
                                                                        //->where('reservation_time_slots.reservation_type', RoutineReservation::class);
                                                               })
                                                               ->where('reservation_players.member_id',$member_id)
                                                               ->whereDate('reservation_time_slots.time_start','=',$date)
                                                               ->where('reservation_time_slots.time_start',">=",$dateTime)
                                                               ->get();


        if($validReservationsTodayForMember->count() == 0 ){
            return false;
        }


        $checkinsForTheDay = self::where("member_id",$member_id)
                       ->where("beacon_id",$beacon_id)
                       ->whereDate("checkinTime","=",$date)
                       ->get();
        $lastClubEntryCheckinTime = null;
        $alreadyCheckedIn = false;
        foreach($checkinsForTheDay as $checkin){
            if($checkin->action == 'clubEntry'){
                $alreadyCheckedIn = true;
                $lastClubEntryCheckinTime = $checkin->checkinTime;

            }else if($checkin->action == 'gameExit'){
                $alreadyCheckedIn = false;

            }

        }

        if($alreadyCheckedIn){
            return false;
        }else if($lastClubEntryCheckinTime !== null && Carbon::now()->diffInSeconds($lastClubEntryCheckinTime) > self::$timeBeforeANewCheckinIsAllowedInSeconds){
            return false;
        }else{
            return true;
        }

    }
    
   

    
    
    
    
    
}
