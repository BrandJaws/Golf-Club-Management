<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\models\Club;
use App\Http\Models\ReservationPlayer;
use App\Http\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingsController extends Controller
{
    public function index(Request $request){
        //dd($request->all());
        $logged_in_user = Auth::user();
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $trainings = (new Training())->paginatedList($logged_in_user->club_id,$currentPage, $perPage, $search,true);

        if($trainings->count() == 0){
            $this->error  ="no_trainings_found";
            return $this->response();

        }
        
        $this->response = $trainings;
        return $this->response();
    }

    public function show($training_id){
        $logged_in_user = Auth::user();

        $training = Training::find($training_id);

        if(!$training){
            $this->error  ="no_trainings_found";
            return $this->response();

        }

        if($training->club_id != $logged_in_user->club_id){
            $this->error  ="training_doesnt_belong_to_users_club";
            return $this->response();
        }

        foreach($training->reservation_players as $reservation_player){
            if($reservation_player->member_id == $logged_in_user->id){
                $training->isBookedByCurrentUser = true;
                $training->reservation_player_id = $reservation_player->id;
                break;
            }
        }
        if($training->isBookedByCurrentUser !== true){
            $training->isBookedByCurrentUser = false;
            $training->reservation_player_id = 0;
        }
        unset($training->reservation_players);
        $this->response = $training;
        return $this->response();
    }

    public function reservePlaceForATraining(Request $request){
        if(!$request->has('training_id')){
            $this->error  ="training_id_missing";
            return $this->response();
        }
        $logged_in_user = Auth::user();

        $training = Training::find($request->get('training_id'));
        if(!$training){
            $this->error  ="no_trainings_found";
            return $this->response();

        }

        if($training->club_id != $logged_in_user->club_id){
            $this->error  ="training_doesnt_belong_to_users_club";
            return $this->response();
        }
        //validate if training is not in the past
        if(Carbon::parse($training->endDate) <= Carbon::today()  ){
            $this->error  ="training_is_not_available";
            return $this->response();
        }

        //validate if there are vacant places on reservation
        if($training->reservation_players->count() >= $training->seats  ){
            $this->error  ="training_slots_full";
            return $this->response();
        }

        foreach($training->reservation_players as $reservation_player){
            if($reservation_player->member_id == $logged_in_user->id ){
                $this->error  ="already_reserved_for_training";
                return $this->response();
            }

        }

        try{
            DB::beginTransaction();
            $training->attachPlayer($logged_in_user->id);
            $this->response = "training_reservation_successful";

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
            $this->error = "not_reserved_for_training";
            return $this->response();
        }

        $reservationPlayer->delete();
        $this->response = "cancel_reservation_success";
        return $this->response();


    }
}
