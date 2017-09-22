<?php

namespace App\Http\Controllers\ClubAdmin\AdminNotifications;

use App\Http\Controllers\Controller;
use App\Http\Models\Course;
use App\Http\Models\EntityBasedNotification;
use App\Http\Models\RestaurantOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminNotificationsController extends Controller
{
    /**
     * @param Request $request
     *
     * Action to get all the reservations for which the display at the admin side needs to be notified and updated
     * Effectively these are the reservations in the entity_based_notifications table with the event ReservationUpdation
     */
    public function reservationUpdation(Request $request)
    {
        if (!$request->has('entity_based_notification_id'))
        {

            $this->error = "entity_based_notification_id_missing";
            return $this->response();
        }

        if (!$request->has('course_id'))
        {

            $this->error = "mobile_invalid_course_identifire";
            return $this->response();

        }

        $entityBasedNotifications = EntityBasedNotification::where('id','>',$request->get('entity_based_notification_id'))
                                                              ->where('event',\Config::get('global.entityBasedNotificationsEvents.ReservationUpdation'))
                                                              ->where('club_id', Auth::user()->club_id)
                                                              ->select('entity_id','entity_type','deleted_entity')
                                                              ->get()
                                                              ->toArray();


        //separate deleted entities first and push them to start of collection later if a replacement was not made later
        $deletedEntities = [];
        foreach ($entityBasedNotifications as $entIndex=>$entityBasedNotification){
                if($entityBasedNotification["deleted_entity"] != null){
                    $deletedEntities[] = json_decode($entityBasedNotification["deleted_entity"]);
                    unset($entityBasedNotifications[$entIndex]);
                    continue;
                }
                unset($entityBasedNotifications[$entIndex]["deleted_entity"]);
        }
        $entityBasedNotifications = array_values($entityBasedNotifications);

        if(count($entityBasedNotifications)){

            $query  = " SELECT * FROM compound_reservations_aggregated ";
            $query .= " WHERE ";
            $query .= " (reservation_id,reservation_type) IN  ";
            $query .= "     (";

            foreach ($entityBasedNotifications as $entIndex=>$entityBasedNotification){
                $query .= "     (";
                $query .= $entityBasedNotification["entity_id"].", '".addslashes($entityBasedNotification["entity_type"])."'";
                $query .= "     )";

                if($entIndex < count($entityBasedNotifications)-1){
                    $query .= ",";
                }
            }
            $query .= "     )";
            $query .= " AND ";
            $query .= " course_id = ". $request->get('course_id') ;

            $allReservationsWithCourses = DB::select(DB::raw($query));

        }else{
            $allReservationsWithCourses = [];
        }

        //Add deleted entities to the start of array if a replacement was not made later i-e not other reservation
        //was made at that timeslot
        foreach($deletedEntities as $deletedEntity){
            $replacementFoundForDeletedEntry = false;
            foreach($allReservationsWithCourses as $reservation){

                if($reservation->reserved_at == $deletedEntity->reserved_at && $reservation->time_start == $deletedEntity->time_start ){
                    $replacementFoundForDeletedEntry = true;
                    break;
                }
            }
            if(!$replacementFoundForDeletedEntry){
                array_unshift($allReservationsWithCourses,$deletedEntity);
            }

        }

        $reservationsByDate = Course::returnReseravtionObjectsArrayFromReservationArray($allReservationsWithCourses);

        if(count($reservationsByDate)){
            $maxEntityBasedNotificationId = EntityBasedNotification::select('id')
                ->where('event',\Config::get('global.entityBasedNotificationsEvents.ReservationUpdation'))
                ->orderBy('id','desc')
                ->first();

            $reservationsByDate[0]->entity_based_notification_id = $maxEntityBasedNotificationId ? $maxEntityBasedNotificationId->id : 0;
            $this->response = $reservationsByDate;
        }else{
            $this->error = "no_reservations_found_for_member";
        }

        return $this->response();
    }

    /**
     * @param Request $request
     *
     * Action to get all the reservations for which the display at the admin side needs to be notified and updated
     * Effectively these are the reservations in the entity_based_notifications table with the event ReservationUpdation
     */
    public function restaurantOrderUpdation(Request $request) {
        if (!$request->has('entity_based_notification_id')) {

            $this->error = "entity_based_notification_id_missing";
            return $this->response();
        }


        $entityBasedNotifications = EntityBasedNotification::where('id', '>', $request->get('entity_based_notification_id'))
          ->where('event', \Config::get('global.entityBasedNotificationsEvents.RestaurantOrderUpdation'))
          ->where('club_id', Auth::user()->club_id)
          ->select('entity_id', 'entity_type', 'deleted_entity')
          ->get()
          ->toArray();


        $deletedEntities = [];
        $updatedOrNewEntities = [];
        foreach ($entityBasedNotifications as $entIndex => $entityBasedNotification) {
            if ($entityBasedNotification["deleted_entity"] != NULL) {
                $deletedEntities[] = json_decode($entityBasedNotification["deleted_entity"])->id;

            }
            else {
                $updatedOrNewEntities[] = $entityBasedNotification->entity_id;
            }

        }


        if (count($updatedOrNewEntities)) {

            $updatedOrNewEntities = RestaurantOrder::whereIn("id", $updatedOrNewEntities)
              ->get();

            if (!$request->has('load_details')) {
                foreach($updatedOrNewEntities as $order){
                    $order->restaurant_order_details = $order->getRestaurantOrderDetailsCustomized();
                }
            }


            if (count($updatedOrNewEntities) || count($deletedEntities)) {
                $maxEntityBasedNotificationId = EntityBasedNotification::select('id')
                  ->where('event', \Config::get('global.entityBasedNotificationsEvents.RestaurantOrderUpdation'))
                  ->orderBy('id', 'desc')
                  ->first();

                $restaurantOrderUpdates = [
                  "deletedOrders" => $deletedEntities,
                  "updatedOrNew" => $updatedOrNewEntities,
                  "entity_based_notification_id" => $maxEntityBasedNotificationId
                ];
                $this->response = $restaurantOrderUpdates;
            }
            else {
                $this->error = "no_updates_found";
            }

            return $this->response();
        }
    }


}
