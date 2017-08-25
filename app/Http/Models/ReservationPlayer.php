<?php

namespace App\Http\Models;

use App\Jobs\MakeReservationPlayerDecision;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReservationPlayer extends Model
{
    use \PushNotification;

    protected $fillable = [
        'reservation_id',
        'reservation_type',
        'member_id',
        'parent_id',
        'group_size',
        'response_status',
        'reservation_status',
        'nextJobToProcess',
        'comingOnTime'
    ];
    
    public function reservation(){
        return $this->morphTo();
    }
    
    public function member(){
        return $this->belongsTo(Member::class);
    }

    /**
     * Process to initiate for the final confirmation of the reservation
     */
    public function initiateFinalProcessForReservation(){

        if($this->response_status =  \Config::get ( 'global.reservation.confirmed' )){
            $this->process_type =  \Config::get ( 'global.reservationsProcessTypes.final' );
            $this->response_status =  \Config::get ( 'global.reservation.pending' );
            $this->save();
            $parent = Member::find($this->parent_id );
            $reservation = $this->reservation;
            $timeStart = $reservation->reservation_time_slots->first()->time_start;
            $course = Course::find($reservation->course_id);

            //Send Notification to player
            $this->sendNotificationToPlayerForReservationConfirmation($timeStart,$parent,$course->name);

            //dispatch jobs
            $this->dispatchMakeReservationPlayerDecisionJob();
        }


    }

    public function dispatchMakeReservationPlayerDecisionJob(){
        $delay = $this->process_type == \Config::get ( 'global.reservationsProcessTypes.initial' )  ? \Config::get ( 'global.jobDelays.initialProcess' ) : \Config::get ( 'global.jobDelays.finalProcess' );
        $this->nextJobToProcess++;
        $this->save();

        $job = (new \App\Jobs\MakeReservationPlayerDecision($this->id,
            $this->nextJobToProcess
        ))->onQueue(\Config::get ( 'global.queues.reservations' ))
          ->delay(Carbon::now()->addSeconds($delay)) ;;
        dispatch($job);
    }

    public function dispatchMakeReservationDecisionJobForFinalCycle(){

        $delay = (Carbon::parse($this->reservation->reservation_time_slots()->first()->time_start)->diffInSeconds(Carbon::now(),true)) - \Config::get ( 'global.timeDifferenceTriggerToStartFinalProcess' );
        if($delay > 0){
           
            $job = (new MakeReservationPlayerDecision($this->id,
                0,
                true
            ))->onQueue(\Config::get ( 'global.queues.reservations' ))
              ->delay(Carbon::now()->addSeconds($delay)) ;

            dispatch($job);
        }



    }

    public function sendNotificationToPlayerForReservationConfirmation($timeStart, $parent,$courseName){
        //Skip process if player_id equals zero i-e player is a guest
        //Or the player has already confirmed
        if($this->member_id == 0 || $this->response_status == \Config::get ( 'global.reservation.confirmed' )){
            return;
        }

        $useCase = \Config::get ( 'global.pushNotificationsUseCases.reservation_confirmation_prompt' );
        $title = "Reservation Confirmation";
        $parentName  = $parent->firstName.' '.$parent->lastName;
       // $timeStart = Carbon::parse($this->reservation->reservation_time_slots()->first()->time_start);
        $time = $timeStart->format('h:i A');
        $date = $timeStart->toDateString();
        if($this->process_type == \Config::get ( 'global.reservationsProcessTypes.initial' )){
            $body = sprintf(trans('message.pushNotificationMessageBodies.reservation_confirmation_prompt'),$parentName, $time,$date,$courseName);
        }else if($this->process_type == \Config::get ( 'global.reservationsProcessTypes.final' )){
            $body = sprintf(trans('message.pushNotificationMessageBodies.reservation_confirmation_prompt_final'),$time,$date,$courseName);
        }

        if($this->member->device_type == "Iphone"){

            $this->sendNotification( $body,
                $this->member->device_registeration_id,
                $this->member->device_type,
                self::getIOSOptionsObject(
                    $useCase,
                    $title,
                    $body,
                    [ 'reservation_player_id'=> $this->id ]
                ),
                true,
                $this->member->id,
                $this);
        }
        //Android logic to follow

    }

    public function sendNotificationToParentOnRequestDeclinedByPlayer(){
        //return if the player is parent himself
        if($this->member_id == $this->parent_id){
            return;
        }


        $parent = Member::find($this->parent_id);

        $member_id = $this->member_id;
        $member_name = $this->member->first_name.' '.$this->member->last_name;
        $useCase = \Config::get ( 'global.pushNotificationsUseCases.request_declined_prompt' );
        $title = "Request Declined By A Player";
        $body = sprintf(trans('message.pushNotificationMessageBodies.request_declined_prompt'),$member_name);

        if($parent->device_type == "Iphone"){
            $this->sendNotification($body,
                $parent->device_registeration_id,
                $parent->device_type,
                self::getIOSOptionsObject(
                    $useCase,
                    $title,
                    $body,
                    ['player_id'=> $member_id ,
                     'player_name'=>$member_name
                    ]
                ),
                true,
                $parent->id,
                $this);
        }
        //Android logic to follow

    }


  

    
    
   
}
