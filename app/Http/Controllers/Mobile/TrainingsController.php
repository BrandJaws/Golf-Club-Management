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
    public function index(){
        $logged_in_user = Auth::user();
        $trainings = Training::where("endDate",">",Carbon::now()->toDateString())
                             ->where("club_id",$logged_in_user->club_id)
                             ->get();
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
}
