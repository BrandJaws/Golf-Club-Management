<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class RoutineReservation extends Model
{

    protected $fillable = [ 
			'id',
			'club_id',
			'course_id'
	];
    
    public function reservation_time_slots(){
        return $this->morphMany("App\Http\Models\ReservationTimeSlot","reservation");
    }
  
    
    public function reservation_players(){
        return $this->morphMany("App\Http\Models\ReservationPlayer","reservation");
    }
    
     public function attachPlayers($players, $parent = 0, $confirmAll = false) {
		if (is_array ( $players ) && ! empty ( $players )) {
			foreach ( $players as $player ) {
                                
                                $status = ($parent  && $player == $parent) || $player == "guest" ||  $confirmAll ? \Config::get ( 'global.reservation.confirmed' )  :  \Config::get ( 'global.reservation.pending' ) ;
                               
                                if($player == "guest"){
                                    ReservationPlayer::create([ 
						'reservation_id' => $this->id,
                                                'reservation_type'=>self::class,
						'member_id' => 0,
						'status' => $status 
                                    ]);
                                  
                                }else{
                                    ReservationPlayer::create([ 
						'reservation_id' => $this->id,
                                                'reservation_type'=>self::class,
						'member_id' => $player,
						'status' => $status  
                                    ]);
                                }
				
			}
		}
		
    }
    
    public function attachTimeSlot($timeSlot) {
	
                ReservationTimeSlot::create([ 
                            'reservation_id' => $this->id,
                            'reservation_type'=>self::class,
                            'time_start' => $timeSlot
                ]);
                       
    }

    /**
     * @return int|null
     *
     * counts and returns no. of players with a status reserved + groupsizes for groups that are pending reserved
     * i-e method will return a number that will reflect the number of players that will eventually play
     */
    public function sumOfReservedAndPendingReservedIntendedSize(){

            if($this->reservation_groups){
                $sum = 0;
                foreach($this->reservation_groups as $group){
                    if($group->reservation_status == \Config::get ( 'global.reservation.reserved' ) ||
                        $group->reservation_status == \Config::get ( 'global.reservation.pending_reserved' )){
                        $sum += $group->group_size;
                    }
                }
                return $sum;
            }else{
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
    public static function findAndGroupReservationForReservationProcess($reservation_id){

            $reservation = RoutineReservation::where('id',$reservation_id)->with(['reservation_players' => function ($query) {
                                                                                        $query->orderBy('created_at', 'asc');
                                                                                        $query->orderBy('updated_at', 'asc');
                                                                                    }])->first();
            $reservation_groups = [];

            if($reservation && $reservation->reservation_players){

                $groupCount = -1;
                $tempParentId = 0;
                $totalRequestedReservationSlots = 0;

                 foreach($reservation->reservation_players as $reservationPlayer){
                     if($tempParentId != $reservationPlayer->parent_id ){
                         $groupCount++;
                         $tempParentId = $reservationPlayer->parent_id;
                         $reservation_groups[$groupCount] = new \stdClass();
                         $reservation_groups[$groupCount]->parent_id = $reservationPlayer->parent_id;
                         $reservation_groups[$groupCount]->reservation_status = $reservationPlayer->reservation_status;
                         $reservation_groups[$groupCount]->group_size = $reservationPlayer->group_size;
                         $reservation_groups[$groupCount]->players = [];
                         $totalRequestedReservationSlots += $reservationPlayer->group_size;


                     }
                     $reservation_groups[$groupCount]->players[] = $reservationPlayer;

                 }
            }
            $reservation->reservation_groups = $reservation_groups;
            $reservation->requested_reservation_slots = $totalRequestedReservationSlots;
            dd($reservation->toArray());




    }
    
    
   
    public function modifyReservationObjectForReponseOnCRUDOperations(){
          $players = [];
          $this->load('reservation_players.member','reservation_time_slots');
          foreach($this->reservation_players as $player){
              $players[] = ["reservation_player_id"=>$player->id,
                            "member_id"=>$player->member != null ? $player->member->id : 0,
                            "member_name"=>$player->member != null ? $player->member->firstName." ".$player->member->lastName : 'Guest'
                           ];
          }
          $this->reservation_id = $this->id;
          $this->reservation_type = RoutineReservation::class;
          $this->players = $players;
          
          $reserved_at = null;
          $timeSlots = [];
          foreach($this->reservation_time_slots as $timeSlot){
             if($reserved_at == null){
                 $reserved_at = Carbon::parse($timeSlot->time_start)->toDateString(); 
             }
             $timeSlots[] = Carbon::parse($timeSlot->time_start)->format('h:i A' );
          }
         
          $this->reserved_at = $reserved_at;
          $this->timeSlots = $timeSlots;
          
          unset($this->id);
          unset($this->nextJobToProcess);
          unset($this->reservation_players);
          
          
          
         
    }
    
    
    
    
    
    
    
    
    
}
