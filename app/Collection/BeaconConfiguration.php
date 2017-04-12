<?php
namespace App\Collection;

use App\Http\Models\Checkin;
use App\Http\models\Club;
use Carbon\Carbon;
use Illuminate\Support\Collection;
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
                Checkin::create([
                    'beacon_id'=>$beacon->id,
                    'reservation_id'=>$nextValidReservationToday->id,
                    'reservation_type'=>$nextValidReservationToday->reservation_type,
                    'member_id'=>$member->id,
                    'checkinTime'=>Carbon::now()->toDateTimeString(),
                    'action'=>"clubEntry",
                    'recordedBy'=>"user",
                    'onTime'=>1
                ]);


                $response->response = "club_entry_checkin_successful";
                return $response;

            }catch(\Exception $e){
                
            }

        }else{
            $response->error = "already_checked_in";
            return $response;
          
        }
    }


}