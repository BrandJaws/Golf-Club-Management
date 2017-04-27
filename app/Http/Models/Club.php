<?php

namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class Club extends Model {
	protected $table = 'club';
	protected $fillable = [ 
			'name',
			'address',
			'logo',
			'opening',
			'closing' 
	];
	public function populate($data = []) {
		if (array_key_exists ( 'club_name', $data )) {
			$this->name = $data ['club_name'];
		}
		if (array_key_exists ( 'address', $data )) {
			$this->address = $data ['address'];
		}
		if (array_key_exists ( 'logo', $data )) {
			$this->logo = $data ['logo'];
		}
		if (array_key_exists ( 'opening', $data )) {
			$this->opening = $data ['opening'];
		}
		if (array_key_exists ( 'closing', $data )) {
			$this->closing = $data ['closing'];
		}
		return $this;
	}
	public function court() {
		return $this->hasMany ( '\App\Http\Models\Court' )->where ( 'status', '=', 'OPEN' );
	}
	public function member() {
		return $this->hasMany ( '\App\Http\Models\Member' );
	}
	public function listPlayers() {
		return $this->hasMany ( '\App\Http\Models\Member' )->where ( 'member.id', '!=', Auth::user ()->id );
	}
        
        /**
         * 
         * @param int $clubId
         * @param array $players
         * @param array $reservationIdsToExcludeTest
         *
         *
         * checks and returns an array of players who already have bookings between requested reservation
         * timeslot and a normal reservation's duration
         * reservationIdsToExcludeTest argument takes an array of reservation ids which will be excluded from the test
         * In most cases we wont need it. A special case where it might be used is such as moving a player to another
         * reservation. In that case before moving the player will be present in the previous reservation and will
         * therefor fail the test if the reservation to which the player is being moved to is less than a game's duration
         * away. In this case we need to perform the check on every reservation but the one in which the player currently is
         *
         */
        public function getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($courseRequested,$startDateTime,$players,$reservationIdsToExcludeTest = []){
            $playersWithReservations = [];
            $playersNamesWithReservations = "";
            $endDateTime = Carbon::parse($startDateTime)->addMinutes($courseRequested->bookingDuration)->toDateTimeString();
            //What actually needs to be checked is the intersection of times i-e 4 cases: 
            //  - The requested start time falls between the duration of another reservation for the same player
            //  - Or The requested end time falls between the duration of another reservation for the same player
            //  Or
            //  - Any existing reservation's start time falls between the duration of requested reservation for the same player
            //  - Or Any existing reservation's end time falls between the duration of requested reservation for the same player
        
            
            $playersFound = DB::table('compound_reservations')
                        ->select('member_name')
                        ->where("club_id",$this->id)
                        ->where ( function ($query) use ($startDateTime, $endDateTime) {

                                $query->where ( function ($query) use ($startDateTime,$endDateTime) {
                                            $query->where ( function ($query) use ($startDateTime) {

                                                $query->where ( 'date_time_start', '<=', $startDateTime )
                                                      ->whereRaw ( "TIMESTAMPADD(MINUTE,bookingDuration,date_time_start) > '".$startDateTime."'" );

                                            } )
                                            ->orWhere ( function ($query) use ($endDateTime) {

                                                    $query->where ( 'date_time_start', '<', $endDateTime )
                                                          ->whereRaw ( "TIMESTAMPADD(MINUTE,bookingDuration,date_time_start) >= '". $endDateTime."'" );

                                            });

                                        } )
                                      ->orWhere ( function ($query) use ($startDateTime,$endDateTime) {
                                            $query->where ( function ($query) use ($startDateTime,$endDateTime) {

                                                $query->where ( 'date_time_start', '>=', $startDateTime)
                                                      ->where ( 'date_time_start', ' <', $endDateTime);

                                            } )
                                            ->orWhere ( function ($query) use ($startDateTime,$endDateTime) {

                                                    $query->whereRaw ( "TIMESTAMPADD(MINUTE,bookingDuration,date_time_start) >'". $startDateTime ."'")
                                                          ->whereRaw ( "TIMESTAMPADD(MINUTE,bookingDuration,date_time_start) <= '". $endDateTime."'" );

                                            });

                                        });

                        })
                        ->whereIn('member_id',$players)
                        ->where(function($query) use($reservationIdsToExcludeTest){
                            if($reservationIdsToExcludeTest){
                                $query->whereNotIn('reservation_id',$reservationIdsToExcludeTest);
                            }

                        })
                        ->get();
            foreach($playersFound as $index=>$playerFound){
                if(strpos($playersNamesWithReservations,$playerFound->member_name) === false){
                   
                        if($playersNamesWithReservations == ""){
                            $playersNamesWithReservations .= $playerFound->member_name;
                        }else if($index < ($playersFound->count() - 2)){
                            $playersNamesWithReservations .= ", ".$playerFound->member_name;
                        }else{
                            $playersNamesWithReservations .= " and ".$playerFound->member_name;
                        }

                   
                }
                
            }
            
            if($playersNamesWithReservations != ""){
                return $playersNamesWithReservations;

            }else{
                return null;
            }
            
            
        }

    public function returnNextValidReservationForAMemberForCheckin($member_id){

        $date = Carbon::now()->toDateString();
        $todayAsDateTime = Carbon::today()->toDateTimeString();
        $dateTime = Carbon::now()->addMinutes(10)->toDateTimeString();

//        $nextValidTrainingForPlayerToday = Training::select("training.id as id", "reservation_players.reservation_type", DB::raw("'$todayAsDateTime' as dateTime"))
//            ->leftJoin('reservation_players', function ($join) {
//                $join->on('training.id', '=','reservation_players.reservation_id')
//                    ->where('reservation_players.reservation_type', Training::class);
//            })
//            ->where('training.club_id',$club_id)
//            ->where('reservation_players.member_id',$member_id)
//            ->whereDate('training.startDate','<=',$date)
//            ->whereDate('training.endDate','>=',$date)
//            ->orderBy('dateTime','DESC');

        // Needs to be modified to accomodate for other reservation types such as Training and leagues
        $nextValidRoutineReservationForPlayerToday = RoutineReservation::select("routine_reservations.id as id", "reservation_players.reservation_type", "reservation_time_slots.time_start","course.name as course_name")
            ->leftJoin('reservation_players', function ($join) {
                $join->on('routine_reservations.id', '=','reservation_players.reservation_id')
                    ->where('reservation_players.reservation_type', RoutineReservation::class);
            })
            ->leftJoin('reservation_time_slots', function ($join) {
                $join->on('routine_reservations.id', '=', 'reservation_time_slots.reservation_id')
                    ->where('reservation_time_slots.reservation_type', RoutineReservation::class);
            })
            ->leftJoin('course','routine_reservations.course_id','=','course.id')
            ->where('routine_reservations.club_id',$this->id)
            ->where('reservation_players.reservation_status',\Config::get('global.reservation.reserved'))
            ->where('reservation_players.member_id',$member_id)

            ->whereDate('reservation_time_slots.time_start','=',$date)
            
//            Disabled On request from IOS Guy
//            ->where('reservation_time_slots.time_start',">=",$dateTime)


            //->unionAll($nextValidTrainingForPlayerToday)

            ->first();


        if(!$nextValidRoutineReservationForPlayerToday ){
            return false;
        }else{
            return $nextValidRoutineReservationForPlayerToday;
        }
    }

    public function returnTrainingsForAMemberAtClubToday($member_id){

        $date = Carbon::now()->toDateString();
        $todayAsDateTime = Carbon::today()->toDateTimeString();

        $nextValidTrainingForPlayerToday = Training::select("training.id as id", "reservation_players.reservation_type", DB::raw("'$todayAsDateTime' as date"), DB::raw("CONCAT_WS(' ',coaches.firstName,coaches.lastName) as coach_name"))
            ->leftJoin('reservation_players', function ($join) {
                $join->on('training.id', '=','reservation_players.reservation_id')
                    ->where('reservation_players.reservation_type', Training::class);
            })
            ->leftJoin('coaches','training.coach_id','=','coaches.id')
            ->where('training.club_id',$this->id)
            ->where('reservation_players.member_id',$member_id)
            ->whereDate('training.startDate','<=',$date)
            ->whereDate('training.endDate','>=',$date)
            ->first();

        if(!$nextValidTrainingForPlayerToday ){
            return false;
        }else{
            return $nextValidTrainingForPlayerToday;
        }
    }


}
