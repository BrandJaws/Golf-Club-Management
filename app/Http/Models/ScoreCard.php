<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreCard extends Model
{
  use \PushNotification;
  
  public $fillable = [
    "reservation_id",
    "reservation_type",
    "player_member_id",
    "manager_member_id",
    "handicap",
    "tee",
    "scorecard_type",
    "use_handicap",
    "round_type",
    "starting_hole",
  ];
  public $timestamps = false;

  public function reservation(){
    return $this->morphTo();
  }

  public function manager_member(){
    return $this->belongsTo(Member::class, "manager_member_id");
  }

  public function player_member(){
    return $this->belongsTo(Member::class, "player_member_id");
  }

  public function score_holes(){
    return $this->hasMany(ScoreHole::class);
  }

  /**
   * @param $timeStart
   * @param $parent
   * @param $courseName
   *
   * To be used to send a push notification to a player who was managing another player's score when that other player
   * opts to do scoring on his own
   */
  public function sendNotificationToScoreManagerWhenAPlayerOvertakesManagement($previousManagerMember,$memberOvertakenBy){


    $useCase = \Config::get ( 'global.pushNotificationsUseCases.score_management_overtaken' );
    $title = "Score Management Overtaken";
    $body = trans('message.pushNotificationMessageBodies.score_management_overtaken',['memberName'=>$memberOvertakenBy->firstName.' '.$memberOvertakenBy->lastName]);
  
    if($previousManagerMember->device_type == "Iphone"){

      $this->sendNotification( $body,
        $previousManagerMember->device_registeration_id,
        $previousManagerMember->device_type,
        self::getIOSOptionsObject(
          $useCase,
          $title,
          $body,
          [ 'player_member_id'=> $memberOvertakenBy->id ]
        ),
        $previousManagerMember->id,
        $this);
    }
    //Android logic to follow

  }





}
