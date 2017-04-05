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
            $beaconConfiguration = unserialize($beacon->configuration);
            if($beaconConfiguration == false){
                $this->error = "beacon_not_configured";
            }else{
                $this->response = $beaconConfiguration;
            }

        }else{
            $this->error = "beacon_not_trusted";
        }

        return $this->response();

    }
}
