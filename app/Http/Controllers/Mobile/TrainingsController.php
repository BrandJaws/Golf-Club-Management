<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\models\Club;
use App\Http\Models\Training;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingsController extends Controller
{
    public function index(Request $request){

        $logged_in_user = Auth::user();
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $trainings = (new Training())->paginatedList($logged_in_user->club_id, $currentPage, $perPage, $search,true);

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

        //validate if there are vacant places on reservation
        if($training->seats ){
        }


    }
}
