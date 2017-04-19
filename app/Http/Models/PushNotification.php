<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Models;
use Illuminate\Database\Eloquent\Model;
class PushNotification extends Model
{
    public static function savePushNotification($memberId,$messageBody,$tennis_reservation_id=null){
        $notification = new PushNotification();
        $notification->member_id = $memberId;
        $notification->messageBody = json_encode($messageBody);
        $notification->tennis_reservation_id = $tennis_reservation_id;
      
        try{
            $notification->save();
        }catch(\Exception $e){
           
            \Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
        }
        
    }
    
    
    
    
}
