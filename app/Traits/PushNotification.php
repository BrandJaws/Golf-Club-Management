<?php
use Illuminate\Support\Facades\Config;
use Sly\NotificationPusher\PushManager;
use Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;
use Sly\NotificationPusher\Adapter\Gcm as GcmAdapter;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Illuminate\Support\Facades\Log;

trait PushNotification
{
	/**
	 *
	 * @param string $message        	
	 * @param string $deviceToken        	
	 * @param string $deviceType        	
	 */
	public function sendNotification($message, $deviceToken, $deviceType, $options = [], $memberId, $reservation_player_id = null) {
		try {
			
			$pushManager = new PushManager ( PushManager::ENVIRONMENT_PROD );
			if ($deviceType == Config::get ( 'global.deviceType.android' )) {
				$adapter = new GcmAdapter ( array (
						'apiKey' => Config::get ( 'services.gcm.key' ) 
				) );
				$devices = new DeviceCollection ( array (
						new Device ( $deviceToken ) 
				) );
				$message = new Message ( $message,$options );
			} else {
				$adapter = new ApnsAdapter ( array (
						'certificate' => storage_path ( 'iphone/certificate.pem' ),
						'passPhrase' => 123456789
				) );
				$devices = new DeviceCollection ( array (
						new Device ( $deviceToken ) 
				) );
				$message = new Message ( $message , $options );
			}
			$push = new Push ( $adapter, $devices, $message);
			$pushManager->add ( $push );
			$pushManager->push ();
                        
			//App\Http\Models\PushNotification::savePushNotification($memberId, $options,$reservation_player_id);
                        
		} catch ( \Exception $exp ) {
			
			Log::error( __CLASS__,['Message'=>$exp->getMessage()]);
			Log::error( __CLASS__,['Options'=>$options]);
		} 
	}
        
        public static function getIOSOptionsObject($useCase,$title,$body,$other_relevant_data_array=[]){
            $message = [];
            //$message['body'] = $title;
            $message['locArgs']['use_case'] = $useCase;
            $message['locArgs']['title'] = $title;
            $message['locArgs']['body'] = $body;
            if(count($other_relevant_data_array)>0){
                foreach($other_relevant_data_array as $key=>$value){
                    $message['locArgs'][$key] = $value;
                }
                
            }
            //d(json_decode(json_encode($message)));
            return $message;
        }
}