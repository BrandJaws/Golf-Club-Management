<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Checkin extends Model
{
    protected $table = 'checkins';
    protected $fillable = [
        'beacon_id',
        'reservation_id',
        'reservation_type',
        'course_id',
        'member_id',
        'checkinTime',
        'action',
        'recordedBy',
        'onTime'
    ];
    public $timestamps = false;
    public static $checkinTimeFloorInSeconds = -900;
    public static $checkinTimeCeilingInSeconds = 900;

    
    public static function memberHasAlreadyRecordedClubEntryForAReservation($reservation_id, $reservation_type, $member){

        $checkinForNextValidReservation = self::where("member_id",$member->id)
                       ->where("reservation_id",$reservation_id)
                       ->where("reservation_type",$reservation_type)
                       ->where("action","=","clubEntry")
                       ->first();

        if($checkinForNextValidReservation){

            return true;

        }else{

            return false;
        }

    }


    
   

    
    
    
    
    
}
