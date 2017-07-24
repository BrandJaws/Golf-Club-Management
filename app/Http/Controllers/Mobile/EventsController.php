<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\models\Club;
use App\Http\Models\ReservationPlayer;
use App\Http\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    public function index(Request $request){
        //dd($request->all());
        $logged_in_user = Auth::user();
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $events = (new Event())->paginatedList($logged_in_user->club_id,$currentPage, $perPage, $search,true);

        if($events->count() == 0){
            $this->error  ="no_events_found";
            return $this->response();

        }
        
        $this->response = $events;
        return $this->response();
    }

    public function show($event_id){
        $logged_in_user = Auth::user();

        $event = Event::find($event_id);

        if(!$event){
            $this->error  ="no_events_found";
            return $this->response();

        }

        if($event->club_id != $logged_in_user->club_id){
            $this->error  ="event_doesnt_belong_to_users_club";
            return $this->response();
        }

        foreach($event->reservation_players as $reservation_player){
            if($reservation_player->member_id == $logged_in_user->id){
                $event->isBookedByCurrentUser = true;
                $event->reservation_player_id = $reservation_player->id;
                break;
            }
        }
        if($event->isBookedByCurrentUser !== true){
            $event->isBookedByCurrentUser = false;
            $event->reservation_player_id = 0;
        }
        unset($event->reservation_players);
        $this->response = $event;
        return $this->response();
    }

    public function reservePlaceForAEvent(Request $request){
        if(!$request->has('event_id')){
            $this->error  ="event_id_missing";
            return $this->response();
        }
        $logged_in_user = Auth::user();

        $event = Event::find($request->get('event_id'));
        if(!$event){
            $this->error  ="no_events_found";
            return $this->response();

        }

        if($event->club_id != $logged_in_user->club_id){
            $this->error  ="event_doesnt_belong_to_users_club";
            return $this->response();
        }
        //validate if event is not in the past
        if(Carbon::parse($event->endDate) <= Carbon::today()  ){
            $this->error  ="event_is_not_available";
            return $this->response();
        }

        //validate if there are vacant places on reservation
        if($event->reservation_players->count() >= $event->seats  ){
            $this->error  ="event_slots_full";
            return $this->response();
        }

        foreach($event->reservation_players as $reservation_player){
            if($reservation_player->member_id == $logged_in_user->id ){
                $this->error  ="already_reserved_for_event";
                return $this->response();
            }

        }

        try{
            DB::beginTransaction();
            $event->attachPlayer($logged_in_user->id);
            $this->response = "event_reservation_successful";

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            \DB::rollback();

            \Log::info(__METHOD__, [
                'error' => $e->getMessage()
            ]);
            $this->error =  "exception";
        }

        return $this->response();

    }

    public function cancelPlaceForReservation(Request $request){
        if(!$request->has('reservation_player_id')){
            $this->error  ="reservation_player_id_missing";
            return $this->response();
        }

        $reservationPlayer = ReservationPlayer::find($request->get('reservation_player_id'));
        if(!$reservationPlayer){
            $this->error  ="no_reservations_found_for_member";
            return $this->response();

        }
        if($reservationPlayer->member_id != Auth::user()->id){
            $this->error = "not_reserved_for_event";
            return $this->response();
        }

        $reservationPlayer->delete();
        $this->response = "cancel_reservation_success";
        return $this->response();


    }
}
