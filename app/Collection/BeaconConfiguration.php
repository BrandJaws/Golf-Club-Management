<?php
namespace App\Collection;

use App\Http\Models\Checkin;
use App\Http\models\Club;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class BeaconConfiguration
{

    protected $skeleton = [
        'Near' => [
            'action' => '',
            'message' => ''
        ],
        'Immediate' => [
            'action' => '',
            'message' => ''
        ],
        'Far' => [
            'action' => '',
            'message' => ''
        ]
    ];

    private $configuration;

    public function __construct()
    {
        $this->configuration = new Collection([]);
    }

    public function boot(array $configuration)
    {
        foreach ($this->skeleton as $key => $value) {
            $propertySet = array_get($configuration, $key, null);
            if (is_null($propertySet))
                throw new \Exception($key . ' is required');
            else {
                $action = array_get($configuration, $key . '.action', null);
                if (! is_null($action)) {
                    if($action == 'custom' && (array_get($configuration, $key . '.message', null) == '' || array_get($configuration, $key . '.message', null) == null)){
                        throw new \Exception('Message for '.$key . ' is required');
                    }
                    $this->configuration->put($key, [
                        'action' => $action,
                        'message' => ($action =='custom')? array_get($configuration, $key . '.message', null):null
                    ]);
                }
            }
        }
        return $this;
    }
    
    public function hasAction($zone, $action){
        
        return array_get($this->configuration->get($zone), 'action', null) == $action;    
    }
    public function getMessage($zone){
        return array_get($this->configuration->get($zone), 'message', null);
    }
    public function toArray()
    {
        return $this->configuration->toArray();
    }

    public function callNamedAction($action, $beacon, $member){
        if(method_exists($this, $action)){
            return $this->$action($beacon,$member);
        }else{
            return false;
        }
    }

    private function welcome(){
        dd("Welcome to Club");
    }

    private function clubEntry($beacon, $member){
        $response = new \stdClass();

        $nextValidReservationToday = Club::returnNextValidReservationForAMemberForCheckin($beacon->club_id,$member->id);
        if(!$nextValidReservationToday){
            $response->error = "no_reservations_today";
            return $response;
        }
        

        if(!Checkin::memberHasAlreadyRecordedClubEntryForAReservation($nextValidReservationToday->id,$nextValidReservationToday->reservation_type, $member)){
            try{
                DB::beginTransaction();
                Checkin::create([
                    'beacon_id'=>$beacon->id,
                    'reservation_id'=>$nextValidReservationToday->id,
                    'reservation_type'=>$nextValidReservationToday->reservation_type,
                    'member_id'=>$member->id,
                    'checkinTime'=>Carbon::now()->toDateTimeString(),
                    'action'=>\Config::get ( 'global.beacon_actions.clubEntry' ),
                    'recordedBy'=>"user",
                    'onTime'=>1
                ]);

                DB::commit();
                $response->response = "checkin_successful";
                return $response;

            }catch(\Exception $e){
                DB::rollBack();
            }

        }else{
            $response->error = "already_checked_in";
            return $response;
          
        }
    }

    private function gameEntry($beacon, $member){

        $response = new \stdClass();
        $minimumTimeBeforeGameEntryInMinutes = 10;

        $mostRelevantReservation = $beacon->course->returnMostRelevantReservationForAMemberForCurrentTime($member->id);
        if(!$mostRelevantReservation){
            //return with error no reservation
            $response->error = "no_reservations_today";
            return $response;

        }

        $gameEntryAgainstMostRelevantReservation = Checkin::where("reservation_id",$mostRelevantReservation->id)
                                                          ->where("reservation_type",$mostRelevantReservation->reservation_type)
                                                          ->where("action",\Config::get ( 'global.beacon_actions.gameEntry' ))
                                                          ->first();
        if($gameEntryAgainstMostRelevantReservation){
            //return with error already checked in
            $response->error = "already_checked_in";
            return $response;
        }

        $clubEntryAgainstRelevantReservation = Checkin::where("reservation_id",$mostRelevantReservation->id)
                                                      ->where("reservation_type",$mostRelevantReservation->reservation_type)
                                                      ->where("action",\Config::get ( 'global.beacon_actions.clubEntry' ))
                                                      ->first();

        if(!$clubEntryAgainstRelevantReservation){
            //respond with error that go back and get the club checkin done first
            $response->error = "checkin_club_entry_missing";
            return $response;
        }

        if(Carbon::now() < Carbon::parse($mostRelevantReservation->time_start)->subMinutes($minimumTimeBeforeGameEntryInMinutes) ){
            //respond with error if reservation start time is further than a minimum time required before user can check in
            $response->error = "not_yet_eligible_for_checkin";
            return $response;
        }


        if($clubEntryAgainstRelevantReservation->onTime == 1){
            //proceed with checkin
            try{
                DB::beginTransaction();
                Checkin::create([
                    'beacon_id'=>$beacon->id,
                    'reservation_id'=>$mostRelevantReservation->id,
                    'reservation_type'=>$mostRelevantReservation->reservation_type,
                    'member_id'=>$member->id,
                    'checkinTime'=>Carbon::now()->toDateTimeString(),
                    'action'=>\Config::get ( 'global.beacon_actions.gameEntry' ),
                    'recordedBy'=>"user",
                    'onTime'=>1
                ]);


                DB::commit();
                $response->response = "checkin_successful";
                return $response;

            }catch(\Exception $e){
                DB::rollBack();
            }

        }else{
            //return with error that cant checkin because late
            $response->error = "checkin_failed_due_to_late";
            return $response;

        }


    }


}