<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Models;
use Illuminate\Database\Eloquent\Model;
class PushNotification extends Model
{
    public function message_owner(){
        return $this->morphTo();
    }

    public static function savePushNotification($memberId,$messageBody,$message_owner=null){
        $notification = new PushNotification();
        $notification->member_id = $memberId;
        $notification->messageBody = json_encode($messageBody);
        $notification->message_owner_id = $message_owner->id;
        $notification->message_owner_type = get_class($message_owner);
        
        try{
            $notification->save();
        }catch(\Exception $e){
           
            \Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
        }
        
    }
    
    
    
    
}
