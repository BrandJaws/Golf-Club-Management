<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RoutineReservation extends Model
{
    public $timestamps = false;
    protected $fillable = [ 
			'id',
			'club_id',
			'course_id',
                        'parent_id',
                        'status',
                        'nextJobToProcess'
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
    
    public function modifyReservationObjectForReponseOnCRUDOperations(){
          $players = [];
          $this->load('reservation_players.member');
          foreach($this->reservation_players as $player){
              $players[] = ["reservation_player_id"=>$player->id,
                            "member_id"=>$player->member != null ? $player->member->id : 0,
                            "member_name"=>$player->member != null ? $player->member->firstName." ".$player->member->lastName : 'Guest'
                           ];
          }
          
          $this->reservation_type = RoutineReservation::class;
          $this->players = $players;
          
          
          unset($this->status);
          unset($this->nextJobToProcess);
          unset($this->reservation_players);
          
          
          
         
    }
    
    
    
    
    
    
    
    
    
    
}
