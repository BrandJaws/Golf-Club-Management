<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Models\ReservationChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ReservationChatController extends Controller
{
   public function store(Request $request){

       $user = Auth::user();
       if (!$request->has('message') || trim($request->get('message')) == "") {
           $this->error = "chat_message_missing";
           return $this->response();
       }

       if (!$request->has('reservation_id')) {
           $this->error = "tennis_reservation_id_missing";
           return $this->response();
       }

       if (!$request->has('reservation_type')) {
           $this->error = "tennis_reservation_id_missing";
           return $this->response();
       }

       $reservationType = $request->get('reservation_type');

       $reservation = $reservationType::where('id',$request->get('reservation_id'))
         ->with([
           'reservation_players'=>function($query){
               $query->where("reservation_status",Config::get('global.reservation.reserved'));
               $query->with(['member'=>function($query){
                   $query->select("id","firstName","lastName","profilePic","device_registeration_id","device_type");
               }]);
           },
         ])
         ->first();

       if(!$reservation){
           $this->error = "invalid_reservation";
           return $this->response();
       }

       //Validate if the player sending message is part of the reservation
       $playerValid = false;

       foreach($reservation->reservation_players as $reservationPlayer){
           if($reservationPlayer->member_id == $user->id){
               $playerValid = true;
               break;
           }
       }

       if(!$playerValid){
           $this->error = "player_not_reserved_for_reservation";
           return $this->response();
       }

       try{
           \DB::beginTransaction();

           $chatMessage = $request->all();
           $chatMessage["member_id"] = $user->id;
           $reservationChatMessage = ReservationChatMessage::create($chatMessage);

           \DB::commit();

           //Send Messages to other group members
           $reservationChatMessage->reservation = $reservation;
           $reservationChatMessage->member = $user;
           $reservationChatMessage->sendChatMessageToMembersInReservation();
           $this->response = "chat_message_sent";



       }catch (\Exception $e){

           \DB::rollback();

           \Log::info(__METHOD__, [
             'error' => $e->getMessage()
           ]);
           $this->error =  "exception";
       }


        return $this->response();
   }

    public function getMessagesForChatByReservation(Request $request){

        $user = Auth::user();
        if (!$request->has('reservation_id')) {
            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }

        if (!$request->has('reservation_type')) {
            $this->error = "tennis_reservation_id_missing";
            return $this->response();
        }

        $reservationType = $request->get('reservation_type');

        $reservation = $reservationType::where('id',$request->get('reservation_id'))
          ->with([
            'reservation_players'=>function($query){
                $query->where("reservation_status",Config::get('global.reservation.reserved'));
            },
          ])
          ->first();

        if(!$reservation){
            $this->error = "invalid_reservation";
            return $this->response();
        }

        //Validate if the player sending message is part of the reservation
        $playerValid = false;

        foreach($reservation->reservation_players as $reservationPlayer){
            if($reservationPlayer->member_id == $user->id){
                $playerValid = true;
                break;
            }
        }

        if(!$playerValid){
            $this->error = "player_not_reserved_for_reservation";
            return $this->response();
        }

        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : Config::get('global.portal_items_per_page');

        $chatMessages = $reservation->chat_messages()
                                    ->with([
                                          'member'=>function($query){
                                              $query->select('id','firstName','lastName','profilePic');
                                          },
                                    ])
                                    ->orderby('created_at', 'DESC')
                                    ->paginate($perPage, array(
                                        '*'
                                    ), 'current_page', $currentPage);

        $this->response = $chatMessages;
        return $this->response();

    }
}
