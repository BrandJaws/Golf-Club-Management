<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Models\Beacon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BeaconController extends Controller
{
    public function validateTrustedbeacon(Request $request)
    {
        if(!$request->has('uuid')){
            $this->error  ="beacon_uuid_missing";
            return $this->response();
        }

        if(!$request->has('major')){
            $this->error  ="beacon_major_missing";
            return $this->response();
        }

        if(!$request->has('minor')){
            $this->error  ="beacon_minor_missing";
            return $this->response();
        }
        
        $beacon = Beacon::findBeacon($request->get('uuid'),$request->get('major'),$request->get('minor'));

        if($beacon && $beacon->club_id == Auth::user()->club_id){
            $beacon->configuration = unserialize($beacon->configuration)->toArray();
            if($beacon->configuration == false){
                $this->error = "beacon_not_configured";
            }else{
                $beacon->setHidden(['club_id','course_id','created_at','updated_at']);
                $this->response = $beacon;
            }

        }else{
            $this->error = "beacon_not_trusted";
        }

        return $this->response();

    }

    public function performAppropriateAction(Request $request){
        if(!$request->has('id')){
            $this->error  ="beacon_id_missing";
            return $this->response();
        }

        $beacon = Beacon::find($request->get('id'));
        if(!$beacon || $beacon->club_id != Auth::user()->club_id){
            $this->error = "beacon_not_trusted";
            return $this->response();
        }
        
        if(!$request->has('action')){
            $this->error  ="beacon_action_missing";
            return $this->response();
        }

        $beacon->configuration = unserialize($beacon->configuration);

        $actionResult = $beacon->configuration->callNamedAction($request->get('action'));

        if($actionResult === false){
            $this->error  ="beacon_action_missing";
            return $this->response();
        }

    }
}
