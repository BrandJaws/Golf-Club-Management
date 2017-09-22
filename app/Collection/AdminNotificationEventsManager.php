<?php
namespace App\Collection;


use Illuminate\Support\Facades\Redis;

class AdminNotificationEventsManager
{
    //string constants for channels
    private static $AdminNotificationsChannel = "admin-notifications";

    //string constants for events
    public static $ReservationUpdationEvent = "ReservationUpdation";
    public static $RestaurantOrderUpdation = "RestaurantOrderUpdation";

    private $dataToBroadcast = [
        "event" => null,
        "data" => null
    ];

    public function __construct($event, $data = null)
    {
        $this->dataToBroadcast["event"] = $event;
        $this->dataToBroadcast["data"] = $data;

    }

    public static function broadcastReservationUpdationEvent($club_id){
        $eventManager = new AdminNotificationEventsManager(self::$ReservationUpdationEvent,["club_id"=>$club_id]);
        $eventManager->broadcast(self::$AdminNotificationsChannel,$eventManager->dataToBroadcast);
    }

    public static function broadcastRestaurantOrderUpdationEvent($club_id){
        $eventManager = new AdminNotificationEventsManager(self::$RestaurantOrderUpdation,["club_id"=>$club_id]);
        $eventManager->broadcast(self::$AdminNotificationsChannel,$eventManager->dataToBroadcast);
    }

    private function broadcast($channel,$dataToBroadcast){
        try{
            Redis::publish($channel,json_encode($dataToBroadcast));
        }catch (\Exception $e){
            
        }
    }

}