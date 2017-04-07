<?php
namespace App\Collection;

use App\Http\Models\Checkin;
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

    public function callNamedAction($action, $beacon_id, $member_id){
        if(method_exists($this, $action)){
            return $this->$action($beacon_id,$member_id);
        }else{
            return false;
        }
    }

    private function welcome(){
        dd("Welcome to Club");
    }

    private function clubEntry($beacon_id, $member_id){
        if(Checkin::clubEntryCheckinIsAllowed($beacon_id, $member_id)){
            dd("allowed");
        }else{
            dd("not allowed");
        }
    }


}