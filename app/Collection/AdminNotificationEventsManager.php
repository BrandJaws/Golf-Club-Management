<?php
namespace App\Collection;


use Illuminate\Support\Facades\Redis;

class AdminNotificationEventsManager
{
    //string constants for channels
    private static $AdminNotificationsChannel = "admin-notifications";

    //string constants for events
    public static $ReservationUpdationEvent = "ReservationUpdation";

    private $dataToBroadcast = [
        "event" => null,
        "data" => null
    ];

    public function __construct($event, $data = null)
    {
        $this->dataToBroadcast["event"] = $event;
        $this->dataToBroadcast["data"] = $data;

    }

    public static function broadcastReservationUpdationEvent(){
        $eventManager = new AdminNotificationEventsManager(self::$ReservationUpdationEvent);
        $eventManager->broadcast(self::$AdminNotificationsChannel,$eventManager->dataToBroadcast);
    }

    private function broadcast($channel,$dataToBroadcast){
        Redis::publish($channel,json_encode($dataToBroadcast));
    }

}