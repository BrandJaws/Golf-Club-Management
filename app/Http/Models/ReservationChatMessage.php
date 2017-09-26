<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ReservationChatMessage extends Model
{
  use \PushNotification;
  protected $fillable = [
    'reservation_id',
    'reservation_type',
    'member_id',
    'message'
  ];

  public function reservation(){
    return $this->morphTo();
  }

  public function member(){
    return $this->belongsTo(Member::class);
  }
  
  public function paginatedList($club_id, $currentPage,$perPage, $search, $onlyShowEventsNotYetComplete = false)
  {

    return $this->where('event.club_id', '=', $club_id)
      ->where(function($query) use ($onlyShowEventsNotYetComplete){
        if ($onlyShowEventsNotYetComplete) {
          $query->where("endDate",">",Carbon::now()->toDateString());
        }
      })
      ->where(function ($query) use ($search) {
        if ($search) {
          $query->where('event.name', 'like', "%$search%");
        }
      })
      ->select('event.id as id', 'event.name', 'event.seats', 'event.startDate', 'event.endDate', \DB::raw("(SELECT COUNT(*)  FROM reservation_players WHERE  reservation_id = event.id AND reservation_type = '".addslashes(Event::class)."'  ) as seatsReserved"))
      ->orderby('event.created_at', 'DESC')
      ->paginate($perPage, array(
        '*'
      ), 'current_page', $currentPage);
  }

  public function sendChatMessageToMembersInReservation(){

    foreach($this->reservation->reservation_players as $reservation_player){
      if($reservation_player->member_id != 0 && ($reservation_player->member->id != $this->member_id)){

        
        $member_id = $this->member_id;
        $useCase = Config::get ( 'global.pushNotificationsUseCases.reservation_group_chat_message' );
        $title = "Reservation Group Chat Message";
        $body = $this->message;
     
        if($reservation_player->member->device_type == "Iphone"){
          $this->sendNotification($body,
            $reservation_player->member->device_registeration_id,
            $reservation_player->member->device_type,
            self::getIOSOptionsObject(
              $useCase,
              $title,
              $body,
              ["reservation_chat_message_id"=> $this->id ,
                "reservation_id"=>$this->reservation_id,
                "reservation_type"=>$this->reservation_type,
                "member_id"=>$this->member_id,
                "message"=>$this->message,
                'memberName'=>$this->member->firstName." ".$this->member->lastName,
                'memberProfilePic'=>$this->member->profilePic == null ? "" : $this->member->profilePic,
                "created_at"=>$this->created_at->toDateTimeString()
              ]
            ),
            false,
            null,
            null);
        }
        //Android logic to follow


      }
    }
  }
}
