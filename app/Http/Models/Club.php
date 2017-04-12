<?php

namespace App\Http\models;

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
         * 
         * checks and returns an array of players who already have bookings between requested reservation
         * timeslot and a normal reservation's duration
         */
        public function getPlayersWithReservationsWithinAStartTimeAndReservationDuaration($courseRequested,$startDateTime,$players){
            $playersWithReservations = [];
            $playersNamesWithReservations = "";
            $endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinutes($courseRequested->bookingDuration)->toDateTimeString(); 
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

    public static function returnNextValidReservationForAMemberForCheckin($club_id, $member_id){

        $date = Carbon::now()->toDateString();
        $todayAsDateTime = Carbon::today()->toDateTimeString();
        $dateTime = Carbon::now()->addMinutes(10)->toDateTimeString();

        $nextValidTrainingForPlayerToday = Training::select("training.id as id", "reservation_players.reservation_type", DB::raw("'$todayAsDateTime' as dateTime"))
            ->leftJoin('reservation_players', function ($join) {
                $join->on('training.id', '=','reservation_players.reservation_id')
                    ->where('reservation_players.reservation_type', Training::class);
            })
            ->where('training.club_id',$club_id)
            ->where('reservation_players.member_id',$member_id)
            ->whereDate('training.startDate','<=',$date)
            ->whereDate('training.endDate','>=',$date)
            ->orderBy('dateTime','DESC');

        // Needs to be modified to accomodate for other reservation types such as Training and leagues
        $nextValidRoutineReservationForPlayerToday = RoutineReservation::select("routine_reservations.id as id", "reservation_players.reservation_type", "reservation_time_slots.time_start as dateTime")
            ->leftJoin('reservation_players', function ($join) {
                $join->on('routine_reservations.id', '=','reservation_players.reservation_id')
                    ->where('reservation_players.reservation_type', RoutineReservation::class);
            })
            ->leftJoin('reservation_time_slots', function ($join) {
                $join->on('routine_reservations.id', '=', 'reservation_time_slots.reservation_id')
                    ->where('reservation_time_slots.reservation_type', RoutineReservation::class);
            })
            ->where('routine_reservations.club_id',$club_id)
            ->where('reservation_players.member_id',$member_id)
            ->whereDate('reservation_time_slots.time_start','=',$date)
            ->where('reservation_time_slots.time_start',">=",$dateTime)
            ->unionAll($nextValidTrainingForPlayerToday)

            ->first();


        if(!$nextValidRoutineReservationForPlayerToday ){
            return false;
        }else{
            return $nextValidRoutineReservationForPlayerToday;
        }
    }


}
