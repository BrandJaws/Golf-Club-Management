<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Models\Member;
use App\Http\Models\RoutineReservation;
use App\Http\Models\ScoreCard;
use App\Http\Models\ScoreHole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ScoresController extends Controller
{
    public function store(Request $request){

      $user = Auth::user();
      if (!$request->has('reservation_id')) {
        $this->error = "tennis_reservation_id_missing";
        return $this->response();
      }

      if (!$request->has('reservation_type')) {
        $this->error = "tennis_reservation_id_missing";
        return $this->response();
      }

      if (!$request->has('players') || !is_array($request->get('players')) || !count($request->get('players'))) {
        $this->error = "player_missing";
        return $this->response();
      }

      if (!$request->has('scorecard_type')) {
        $this->error = "scorecard_type_not_found";
        return $this->response();
      }

      if (!$request->has('round_type')) {
        $this->error = "round_type_not_found";
        return $this->response();
      }

      if (!$request->has('use_handicap')) {
        $useHandicap =  Config::get('global.score.handicap_options.no');
      }else{
        $useHandicap = $request->get('use_handicap');
      }

      if (!$request->has('starting_hole')) {
        $startingHole =  1;
      }else{
        $startingHole = $request->get('starting_hole');
      }

      $players = $request->get('players');

      foreach($players as $player){
        
        if(!isset($player["player_member_id"]) || !isset($player["handicap"]) || !isset($player["tee"])){
            $this->error = "players_data_not_valid";
            return $this->response();
        }

      }

      try
      {

        DB::beginTransaction();

        $reservation = null;
        //get reservation according to the type
        if($request->get('reservation_type') == RoutineReservation::class){
          $reservation = RoutineReservation::where('id',$request->get('reservation_id'))
                                            ->with(['course'=>function($query){
                                              $query->with(['holes'=>function($query){
                                                $query->orderBy('hole_number','ASC');
                                              }]);
                                            },
                                            'reservation_players'=>function($query){
                                              $query->where("reservation_status",Config::get('global.reservation.reserved'));
                                            }])
                                            ->first();
        }

        if(!$reservation){
          $this->error = "invalid_reservation";
          return $this->response();
        }

        //Validate if any of the players sent is not reserved for the reservation being scored
        foreach($players as $player){
          $playerValid = false;
          foreach($reservation->reservation_players as $reservationPlayer){
            if($reservationPlayer->id == $player["player_member_id"]){
              $playerValid = true;
              break;
            }
          }
          if(!$playerValid){
            $this->error = "player_not_reserved_for_reservation";
            return $this->response();
          }
        }

        if($reservation->course->numberOfHoles < $request->get('round_type')){
          $this->error = "invalid_round_type";
          return $this->response();
        }


        //Score entries array to be used to store new or updated entries to be sent back as response if all goes well
        $scoreEntries = [];
        $scoreManagersToSendMessagesToInCaseOfManagementOvertake = [];
        foreach($players as $player){

          //Check if we don't already have an entry for one or more requested players

          $updatedExistingRecord = false;

          foreach($reservation->score_cards as $scoreCard){
            //If the score is already being recorded by someone else and the player wants to score himself,
            //override the manager to himself and skip this entry
            if($scoreCard->player_member_id == $player['player_member_id'] && ($scoreCard->player_member_id == $user->id && $scoreCard->manager_member_id != $user->id)){

              if(!$request->has('overtake_own_scorecard') || $request->get('overtake_own_scorecard') != true){
                //return with error prompt if the user wants to overtake his score card
                $this->error = "requesting_user_already_being_scored";
                $this->supportingDataUseCase = 'requesting_user_already_being_scored';

                $manager = Member::find($scoreCard->manager_member_id);
                $managerName = $manager->firstName." ".$manager->lastName;
                $this->responseParameters = ["manager"=>$managerName];
                return $this->response();
              }

              $previousManager = Member::find($scoreCard->manager_member_id);

              $scoreCard->manager_member_id = $user->id;
              $scoreCard->save();
              $updatedExistingRecord = true;
              $scoreCard->load('score_holes');
              $scoreEntries[] = $scoreCard;
              $scoreManagersToSendMessagesToInCaseOfManagementOvertake[] = ["score"=>$scoreCard, "manager"=>$previousManager];


              break;
            }else if($scoreCard->player_member_id == $player['player_member_id']){
              //return with error if score entry has been made for any of the players and the player is not the requesting user
              $this->error = "player_already_being_scored";
              return $this->response();
            }
          }

          //Skip new entry if an existing record was updated in the iteration
          if($updatedExistingRecord){
            continue;
          }

          $member = Member::find($player['player_member_id']);
          if(!$member){
            $this->error = "member_not_exists";
            return $this->response();

          }

          $scoreCardEntry = ScoreCard::create([

            "reservation_id"=>$request->get('reservation_id'),
            "reservation_type"=>$request->get('reservation_type'),
            "player_member_id"=>$player['player_member_id'],
            "manager_member_id"=>$user->id,
            "handicap"=>$player["handicap"],
            "tee"=>$player["tee"],
            "scorecard_type"=>$request->get('scorecard_type'),
            "use_handicap"=>$useHandicap,
            "round_type"=>$request->get('round_type'),
            "starting_hole"=>$startingHole,
          ]);

          $scoreHoles = [];

          foreach($reservation->course->holes as $index=>$hole){

            //Only generate score entries for holes which are under round_type value
            if(($index+1) > $request->get('round_type'))
            {
              break;
            }
            else
            {

              //Par and handicap will differ based on player's gender so grab the appropriate values

              if($member->gender == Config::get('global.gender.male')){
                $par = $hole->mens_par;
                $handicap = $hole->mens_handicap;
              }else{
                $par = $hole->womens_par;
                $handicap = $hole->womens_handicap;
              }

              //Score and player_is_late values will be preset if the starting hole is greater than the one being scored
              if($index+1 < $request->get('starting_hole')){
                $score = $par+ Config::get('global.score.latePenaltyStrokesAbovePar');
                $playerIsLate = Config::get('global.score.handicap_options.yes');
              }else{
                $score = 0;
                $playerIsLate = Config::get('global.score.handicap_options.no');
              }

              $distance = 0;

              foreach(json_decode($hole->tee_values) as $tee){
                  if($player["tee"] == $tee->color){
                    $distance = $tee->distance;
                    break;
                  }
              }

              $scoreHole = ScoreHole::create([
                "score_card_id"=>$scoreCardEntry->id,
                "hole_id"=>$hole->id,
                "score"=>$score,
                "putts"=>0,
                "fairway"=>Config::get('global.score.fairway.center'),
                "distance"=>$distance,
                "par"=>$par,
                "handicap"=>$handicap,
                "player_is_late"=>$playerIsLate]);

              $scoreHoles[] = $scoreHole;




            }

          }

          $scoreCardEntry->score_holes = $scoreHoles;
          $scoreEntries[] = $scoreCardEntry;


        }

        DB::commit();

        $this->response = [
          "reservation_id" => $request->get('reservation_id'),
          "reservation_type" => $request->get('reservation_type'),
          "manager_member_id" => $user->id ,
          "scorecard_type" => $request->get('scorecard_type'),
          "use_handicap"=>$useHandicap,
          "round_type"=>$request->get('round_type'),
          "starting_hole"=>$startingHole,
          "score_cards"=>$scoreEntries,
        ];

        //Send Messages to previous mangers of scores *if replaced *if any

        foreach($scoreManagersToSendMessagesToInCaseOfManagementOvertake as $scoreManager){
          $scoreManager["score"]->sendNotificationToScoreManagerWhenAPlayerOvertakesManagement($scoreManager["manager"], $user);
        }

      }
      catch(\Exception $e)
      {
        //dd($e);
        \DB::rollback();

        \Log::info(__METHOD__, [
          'error' => $e->getMessage()
        ]);
        $this->error =  "exception";
      }
      return $this->response();

    }

  public function recordScoreForHoles(Request $request){

    if (!$request->has('score_holes') || !is_array($request->get('score_holes')) || !count($request->get('score_holes'))) {
      $this->error = "scores_missing";
      return $this->response();
    }

    $scores = $request->get('score_holes');
    foreach($scores as $score){

      if(!isset($score["score_hole_id"]) || !isset($score["score"]) || !isset($score["putts"]) || !isset($score["fairway"])){
        $this->error = "scores_data_not_valid";
        return $this->response();
      }

    }

    try
    {

      DB::beginTransaction();

      foreach($scores as $score){

        $scoreHole = ScoreHole::where('id',$score["score_hole_id"])->with('score_card')->first();

        if(!$scoreHole){
          $this->error = "invalid_hole";
          return $this->response();
        }

        if($scoreHole->score_card->manager_member_id != Auth::user()->id){
          //A custom response error message with player id included so that
          //the client consuming the service can remove it from future requests

          $this->error = 'not_allowed_score_updation';
          $this->supportingDataUseCase = 'not_allowed_score_updation';
          $this->supportingData = ["member_id"=>$scoreHole->score_card->player_member_id];

          return $this->response();


        }

        if($scoreHole->player_is_late == Config::get('global.score.handicap_options.yes')){
          $this->error = "late_holes_not_allowed";
          return $this->response();
        }

        $scoreHole->score = $score["score"];
        $scoreHole->putts = $score["putts"];
        $scoreHole->fairway = $score["fairway"];
        $scoreHole->save();

      }

      DB::commit();

      $this->response = "score_recorded_successfuly";

    }
    catch (\Exception $e)
    {

      \DB::rollback();

      \Log::info(__METHOD__, [
        'error' => $e->getMessage()
      ]);
      $this->error =  "exception";
    }
    return $this->response();
  }

  public function destroy(Request $request){

    if (!$request->has('score_card_id')) {
      $this->error = "scores_missing";
      return $this->response();
    }
    
    $scoreCard = ScoreCard::find($request->get('score_card_id'));
    
    if(!$scoreCard){
      $this->error = "invalid_scorecard";
      return $this->response();
    }

    if($scoreCard->manager_member_id != Auth::user()->id){
      $this->error = "score_not_being_managed_by_user";
      return $this->response();
    }

    $scoreCard->delete();

    $this->response = "score_card_deleted_successfuly";
    return $this->response();
    
    
    
  }


}
