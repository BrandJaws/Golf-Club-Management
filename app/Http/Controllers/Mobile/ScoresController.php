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

      if (!$request->has('team_size')) {
        $this->error = "team_size_missing";
        return $this->response();
      }

      if (!$request->has('teams') || !is_array($request->get('teams')) || !count($request->get('teams'))) {
        $this->error = "score_card_teams_missing";
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

      if (!$request->has('scoring_type')) {
        $scoringType = Config::get('global.score.scoring_type_options.gross');
      }else{
        if(!in_array($request->get('scoring_type'), Config::get('global.score.scoring_type_options'))){
          $this->error = "invalid_scoring_type";
          return $this->response();
        }

        $scoringType = $request->get('scoring_type');
      }

      if (!$request->has('use_handicap')) {
        $useHandicap =  Config::get('global.score.handicap_options.no');
      }else{
        $useHandicap = $request->get('use_handicap');
      }

      //Can't use no handicap and net scoring type option 
      if($useHandicap ==  Config::get('global.score.handicap_options.no') && $scoringType == Config::get('global.score.scoring_type_options.net') ){
        $this->error = "invalid_scoring_type";
        return $this->response();
      }



      $players = [];
      $includedSelf = false;
      foreach($request->get('teams') as $team){
        //Validate if team has the right number of players
        if(count($team["player_members"]) != $request->get('team_size')){
          $this->error = "inconsistent_team_size";
          return $this->response();
        }
        foreach($team["player_members"] as $teamMember){

          if($teamMember["player_member_id"] == $user->id){
            $includedSelf = true;
          }

          if(!isset($teamMember["player_member_id"]) || !isset($teamMember["handicap"]) || !isset($teamMember["tee"])){
            $this->error = "players_data_not_valid";
            return $this->response();
          }

          //Check for duplicate entries
          foreach($players as $player){
            if($player["player_member_id"] == $teamMember["player_member_id"]){
              $this->error = "duplicate_members_in_teams";
              return $this->response();
            }
          }

          $players[] = $teamMember;

        }
      }

      if(!$includedSelf){
        $this->error = "self_not_in_teams";
        return $this->response();
      }

      try
      {

        DB::beginTransaction();


        $reservationType = $request->get('reservation_type');

        $reservation = $reservationType::where('id',$request->get('reservation_id'))
                                            ->with(['course'=>function($query){
                                              $query->with(['holes'=>function($query){
                                                $query->orderBy('hole_number','ASC');
                                              }]);
                                            },
                                            'reservation_players'=>function($query){
                                              $query->where("reservation_status",Config::get('global.reservation.reserved'));
                                            }])
                                            ->first();


        if(!$reservation){
          $this->error = "invalid_reservation";
          return $this->response();
        }

        if($reservation->course->numberOfHoles < $request->get('round_type')){
          $this->error = "invalid_round_type";
          return $this->response();
        }

        $reservation->course->tees = json_decode($reservation->course->tees);
        if(is_array($reservation->course->tees)){
          foreach($request->get('teams') as $team){

            foreach($team["player_members"] as $teamMember){
              $teeValid = false;
              foreach($reservation->course->tees as $tee){
                if($tee->color == $teamMember["tee"]){
                  $teeValid = true;
                  break;
                }
              }

              if(!$teeValid){
                $this->error = "invalid_tee_value";
                return $this->response();
              }


            }
          }

        }

        //Validate if any of the players sent is not reserved for the reservation being scored
        foreach($players as $player){

          $playerValid = false;
          foreach($reservation->reservation_players as $reservationPlayer){
            if($reservationPlayer->member_id == $player["player_member_id"]){
              $playerValid = true;
              break;
            }
          }
          if(!$playerValid){
            $this->error = "player_not_reserved_for_reservation";
            return $this->response();
          }
        }

        //Validate if a scorecard is already being managed by the requesting user
        foreach($reservation->score_cards as $scoreCard){
          if($scoreCard->manager_member_id == $user->id && $scoreCard->actively_scoring == 1){
            $this->error = "already_created_scorecard";
            return $this->response();
          }
        }


        $scoreManagersToSendMessagesToInCaseOfManagementOvertake = [];
        foreach($players as $player){

          //Validate and set starting hole for the player
          if (!isset($player["starting_hole"])) {
            $startingHole =  1;
          }else{
            if($player["starting_hole"] >= $reservation->course->numberOfHoles){
              $this->error = "invalid_starting_hole";
              return $this->response();
            }else{
              $startingHole = $player["starting_hole"];
            }

          }

          //Check if we don't already have an entry for one or more requested players

          $updatedExistingRecord = false;

          foreach($reservation->score_cards as $scoreCard){

            //If the player has been scored by someone else and wants to score himself OR
            //the scoreCard is not being actively_scored i-e scorecard had been scored by someone else and later he removed the added player i-e set actively_scoring to 0
            if($scoreCard->player_member_id == $player['player_member_id'] && (($scoreCard->player_member_id == $user->id && $scoreCard->manager_member_id != $user->id) || $scoreCard->actively_scoring == 0)){

              //If the score is already being recorded by someone else
              //prompt user and override the manager to himself and skip this entry. Afterwards make arrangement to send notification to previous manager
              if($scoreCard->manager_member_id != $player['player_member_id']){
                if(!$request->has('overtake_own_scorecard') || ($request->get('overtake_own_scorecard') !== true || $request->get('overtake_own_scorecard') !== 1) ){
                  //return with error prompt if the user wants to overtake his score card
                  $this->error = "requesting_user_already_being_scored";
                  $this->supportingDataUseCase = 'requesting_user_already_being_scored';

                  $manager = Member::find($scoreCard->manager_member_id);
                  $managerName = $manager->firstName." ".$manager->lastName;
                  $this->responseParameters = ["manager"=>$managerName];
                  return $this->response();
                }

                $previousManager = Member::find($scoreCard->manager_member_id);

                //adjust holes according to the new round_type value
                if(count($scoreCard->score_holes) < $request->get('round_type'))
                {
                  //create excess holes according to the new round_type value
                  for($holeIndex=count($scoreCard->score_holes); $holeIndex < $request->get('round_type'); $holeIndex++){


                    $scoreHole = ScoreHole::create([
                      "score_card_id"=>$scoreCard->id,
                      "hole_id"=>$reservation->course->holes[$holeIndex]->id,
                      "score"=>0,
                      "putts"=>0,
                      "fairway"=>Config::get('global.score.fairway.center'),
                    ]);
                  }
                  //dd(count($scoreCard->score_holes));
                }
                else if(count($scoreCard->score_holes) > $request->get('round_type'))
                {
                  foreach($scoreCard->score_holes as $scoreHoleIndex => $scoreHole){
                    if($scoreHoleIndex > $request->get('round_type') -1){
                      $scoreHole->delete();
                    }
                  }

                }

                $scoreCard->manager_member_id = $user->id;
                $scoreCard->handicap = $player["handicap"];
                $scoreCard->tee = $player["tee"];
                $scoreCard->scorecard_type = $request->get('scorecard_type');
                $scoreCard->scoring_type = $scoringType;
                $scoreCard->use_handicap = $useHandicap;
                $scoreCard->round_type = $request->get('round_type');
                $scoreCard->starting_hole = $startingHole;
                $scoreCard->team_size = $request->get('team_size');
                $scoreCard->team_number = 0;
                $scoreCard->actively_scoring = 1;

                $scoreCard->save();


                $updatedExistingRecord = true;
                //$scoreCard->load('score_holes');
                $scoreManagersToSendMessagesToInCaseOfManagementOvertake[] = ["score"=>$scoreCard, "manager"=>$previousManager];

                // Reset teams when a player leaves And he was actively being scored by the previous manager
                $scoreCardsForPreviousManager = ScoreCard::where("reservation_id" , $request->get('reservation_id'))
                  ->where("reservation_type",$request->get('reservation_type'))
                  ->where('manager_member_id',$previousManager->id)
                  ->orderBy('team_number')
                  ->get();

                foreach($scoreCardsForPreviousManager as $scoreCardIndex => $scoreCardByPrevManager){
                  $scoreCardByPrevManager->team_number = $scoreCardIndex+1;
                  $scoreCardByPrevManager->team_size = 1;
                  $scoreCardByPrevManager->save();
                }



                break;

              }else{
                //If the score had been recorded by someone else and  later he removed the added player i-e set actively_scoring to 0
                //Could be any player and not necessarily the requesting user himself
                //overtake the score silently

                //adjust holes according to the new round_type value
                if(count($scoreCard->score_holes) < $request->get('round_type'))
                {
                  //create excess holes according to the new round_type value
                  for($holeIndex=count($scoreCard->score_holes); $holeIndex < $request->get('round_type'); $holeIndex++){


                    $scoreHole = ScoreHole::create([
                      "score_card_id"=>$scoreCard->id,
                      "hole_id"=>$reservation->course->holes[$holeIndex]->id,
                      "score"=>0,
                      "putts"=>0,
                      "fairway"=>Config::get('global.score.fairway.center'),
                    ]);
                  }
                  //dd(count($scoreCard->score_holes));
                }
                else if(count($scoreCard->score_holes) > $request->get('round_type'))
                {

                  foreach($scoreCard->score_holes as $scoreHoleIndex => $scoreHole){
                    if($scoreHoleIndex > $request->get('round_type') -1){
                      $scoreHole->delete();
                    }
                  }

                }

                $scoreCard->manager_member_id = $user->id;
                $scoreCard->handicap = $player["handicap"];
                $scoreCard->tee = $player["tee"];
                $scoreCard->scorecard_type = $request->get('scorecard_type');
                $scoreCard->scoring_type = $scoringType;
                $scoreCard->use_handicap = $useHandicap;
                $scoreCard->round_type = $request->get('round_type');
                $scoreCard->starting_hole = $startingHole;
                $scoreCard->team_size = $request->get('team_size');
                $scoreCard->team_number = 0;
                $scoreCard->actively_scoring = 1;

                $scoreCard->save();
                $updatedExistingRecord = true;

              }



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
            "scoring_type"=>$scoringType,
            "use_handicap"=>$useHandicap,
            "round_type"=>$request->get('round_type'),
            "starting_hole"=>$startingHole,
            "team_size"=>$request->get('team_size'),
            "team_number"=>0, //Team numbers will be synchronized and re-assigned later according to the request teams data
            "actively_scoring"=>1
          ]);

          //$scoreHoles = [];

          foreach($reservation->course->holes as $index=>$hole){

            //Only generate score entries for holes which are under round_type value
            if(($index+1) > $request->get('round_type'))
            {
              break;
            }
            else
            {



              $scoreHole = ScoreHole::create([
                "score_card_id"=>$scoreCardEntry->id,
                "hole_id"=>$hole->id,
                "score"=>0,
                "putts"=>0,
                "fairway"=>Config::get('global.score.fairway.center'),
              ]);

              //$scoreHoles[] = $scoreHole;


            }

          }


        }

        //Sync and set team numbers for each scorecard for each player according to teams data received
        $scoreCards = ScoreCard::where("reservation_id" , $request->get('reservation_id'))
                                ->where("reservation_type",$request->get('reservation_type'))
                                ->where('manager_member_id',$user->id)
                                ->get();

        foreach($request->get('teams') as $teamIndex => $team){
          foreach ($team["player_members"] as $teamMember){
            foreach($scoreCards as $scoreCard){
              if($scoreCard->player_member_id == $teamMember["player_member_id"]){
                $scoreCard->team_number = $teamIndex+1;
                $scoreCard->save();
                break;
              }
            }
          }
        }

        DB::commit();

        //Send Messages to previous mangers of scores *if replaced *if any

        foreach($scoreManagersToSendMessagesToInCaseOfManagementOvertake as $scoreManager){
          $scoreManager["score"]->sendNotificationToScoreManagerWhenAPlayerOvertakesManagement($scoreManager["manager"], $user);
        }
        

        $this->response = ScoreCard::getScoreCardsOfAGroupByMemberIdAndReservationIdAndType($user->id,$request->get('reservation_id'),$request->get('reservation_type'));



      }
      catch(\Exception $e)
      {
        dd($e);
        \DB::rollback();

        \Log::info(__METHOD__, [
          'error' => $e->getMessage()
        ]);
        $this->error =  "exception";
      }
      return $this->response();

    }

  public function update(Request $request){

    $user = Auth::user();


    if (!$request->has('manager_score_card_id')) {
      $this->error = "manager_score_card_id_missing";
      return $this->response();
    }

    $managerScoreCard = ScoreCard::find($request->get('manager_score_card_id'));
    if(!$managerScoreCard){
      $this->error = "score_card_not_found";
      return $this->response();
    }
    if($managerScoreCard->manager_member_id != $user->id){
      $this->error = "user_not_manager";
      return $this->response();
    }
    $scoreCardGroup = ScoreCard::where("reservation_id" , $managerScoreCard->reservation_id)
                                ->where("reservation_type",$managerScoreCard->reservation_type)
                                ->where("manager_member_id",  $user->id)

                                ->with(["score_holes"=>function($query){
                                    $query->leftJoin("course_holes","score_holes.hole_id","=","course_holes.id");
                                    $query->select("score_holes.id as id",
                                      "score_card_id",
                                      "hole_id",
                                      "score",
                                      "putts",
                                      "fairway","course_id","hole_number","mens_handicap","mens_par","womens_handicap","womens_par","tee_values"

                                      );
                                    $query->orderBy("course_holes.hole_number");
                                }])
                                ->get();


    if (!$request->has('team_size')) {
      $this->error = "team_size_missing";
      return $this->response();
    }

    if (!$request->has('teams') || !is_array($request->get('teams')) || !count($request->get('teams'))) {
      $this->error = "score_card_teams_missing";
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

    if (!$request->has('scoring_type')) {
      $scoringType = Config::get('global.score.scoring_type_options.gross');
    }else{
      if(!in_array($request->get('scoring_type'), Config::get('global.score.scoring_type_options'))){
        $this->error = "invalid_scoring_type";
        return $this->response();
      }

      $scoringType = $request->get('scoring_type');
    }

    if (!$request->has('use_handicap')) {
      $useHandicap =  Config::get('global.score.handicap_options.no');
    }else{
      $useHandicap = $request->get('use_handicap');
    }

    //Can't use no handicap and net scoring type option
    if($useHandicap ==  Config::get('global.score.handicap_options.no') && $scoringType == Config::get('global.score.scoring_type_options.net') ){
      $this->error = "invalid_scoring_type";
      return $this->response();
    }



    $players = [];
    $includedSelf = false;
    foreach($request->get('teams') as $team){
      //Validate if team has the right number of players
      if(count($team["player_members"]) != $request->get('team_size')){
        $this->error = "inconsistent_team_size";
        return $this->response();
      }
      foreach($team["player_members"] as $teamMember){

        if($teamMember["player_member_id"] == $user->id){
          $includedSelf = true;
        }

        if(!isset($teamMember["player_member_id"]) || !isset($teamMember["handicap"]) || !isset($teamMember["tee"])){
          $this->error = "players_data_not_valid";
          return $this->response();
        }

        //Check for duplicate entries
        foreach($players as $player){
          if($player["player_member_id"] == $teamMember["player_member_id"]){
            $this->error = "duplicate_members_in_teams";
            return $this->response();
          }
        }

        $players[] = $teamMember;

      }
    }

    if(!$includedSelf){
      $this->error = "self_not_in_teams";
      return $this->response();
    }

    try
    {

      DB::beginTransaction();

      $reservation = $managerScoreCard->reservation()
                                          ->with(['course'=>function($query){
                                          $query->with(['holes'=>function($query){
                                            $query->orderBy('hole_number','ASC');
                                          }]);
                                        },
                                          'reservation_players'=>function($query){
                                            $query->where("reservation_status",Config::get('global.reservation.reserved'));
                                          }])
                                        ->first();


      if(!$reservation){
        $this->error = "invalid_reservation";
        return $this->response();
      }

      if($reservation->course->numberOfHoles < $request->get('round_type')){
        $this->error = "invalid_round_type";
        return $this->response();
      }
      $reservation->course->tees = json_decode($reservation->course->tees);
      if(is_array($reservation->course->tees)){
        foreach($request->get('teams') as $team){

          foreach($team["player_members"] as $teamMember){
            $teeValid = false;
            foreach($reservation->course->tees as $tee){
              if($tee->color == $teamMember["tee"]){
                $teeValid = true;
                break;
              }
            }

            if(!$teeValid){
              $this->error = "invalid_tee_value";
              return $this->response();
            }


          }
        }

      }




      //Validate if any of the players sent is not reserved for the reservation being scored
      foreach($players as $player){

        $playerValid = false;
        foreach($reservation->reservation_players as $reservationPlayer){
          if($reservationPlayer->member_id == $player["player_member_id"]){
            $playerValid = true;
            break;
          }
        }
        if(!$playerValid){
          $this->error = "player_not_reserved_for_reservation";
          return $this->response();
        }
      }



      //Find existing scorecards against requested players and the ones which need to have new scorecards created
      foreach($players as $player){
        $requestedPlayerFound = false;
        foreach($scoreCardGroup as $scoreCard){
          //Validate and set starting hole for the player

          if (!isset($player["starting_hole"])) {
            $startingHole =  1;
          }else{
            if($player["starting_hole"] >= $reservation->course->numberOfHoles){
              $this->error = "invalid_starting_hole";
              return $this->response();
            }else{
              $startingHole = $player["starting_hole"];
            }

          }

          if($scoreCard->player_member_id == $player["player_member_id"]){



            //**************Update Score Card***************//

            //adjust holes according to the new round_type value
            if(count($scoreCard->score_holes) < $request->get('round_type'))
            {

              //create excess holes according to the new round_type value
              for($holeIndex=count($scoreCard->score_holes); $holeIndex < $request->get('round_type'); $holeIndex++){


                $scoreHole = ScoreHole::create([
                  "score_card_id"=>$scoreCard->id,
                  "hole_id"=>$reservation->course->holes[$holeIndex]->id,
                  "score"=>0,
                  "putts"=>0,
                  "fairway"=>Config::get('global.score.fairway.center'),
                ]);
              }
              //dd(count($scoreCard->score_holes));
            }
            else if(count($scoreCard->score_holes) > $request->get('round_type'))
            {

              foreach($scoreCard->score_holes as $scoreHoleIndex => $scoreHole){

                if($scoreHoleIndex > $request->get('round_type') -1){
                 
                  $scoreHole->delete();
                }
              }

            }

            $scoreCard->manager_member_id = $user->id;
            $scoreCard->handicap = $player["handicap"];
            $scoreCard->tee = $player["tee"];
            $scoreCard->scorecard_type = $request->get('scorecard_type');
            $scoreCard->scoring_type = $scoringType;
            $scoreCard->use_handicap = $useHandicap;
            $scoreCard->round_type = $request->get('round_type');
            $scoreCard->starting_hole = $startingHole;
            $scoreCard->team_size = $request->get('team_size');
            $scoreCard->team_number = 0;
            $scoreCard->actively_scoring = 1;

            $scoreCard->save();

            //$scoreCardsToUpdate[] = $scoreCard;
            $requestedPlayerFound = true;

            break;
          }

        }

        if(!$requestedPlayerFound){

          //**************Create Score Card***************//
          $updatedExistingRecord = false;
          foreach($reservation->score_cards as $scoreCard){

            if($scoreCard->player_member_id == $player['player_member_id'] &&  $scoreCard->actively_scoring == 0){


                //If the score had been recorded by someone else and  later he removed the added player i-e set actively_scoring to 0
                //Could be any player and not necessarily the requesting user himself
                //overtake the score silently

                //adjust holes according to the new round_type value
                if(count($scoreCard->score_holes) < $request->get('round_type'))
                {
                  //create excess holes according to the new round_type value
                  for($holeIndex=count($scoreCard->score_holes); $holeIndex < $request->get('round_type'); $holeIndex++){


                    $scoreHole = ScoreHole::create([
                      "score_card_id"=>$scoreCard->id,
                      "hole_id"=>$reservation->course->holes[$holeIndex]->id,
                      "score"=>0,
                      "putts"=>0,
                      "fairway"=>Config::get('global.score.fairway.center'),
                    ]);
                  }
                  //dd(count($scoreCard->score_holes));
                }
                else if(count($scoreCard->score_holes) > $request->get('round_type'))
                {
                  foreach($scoreCard->score_holes as $scoreHoleIndex => $scoreHole){
                    if($scoreHoleIndex > $request->get('round_type') -1){
                      $scoreHole->delete();
                    }
                  }

                }

                $scoreCard->manager_member_id = $user->id;
                $scoreCard->handicap = $player["handicap"];
                $scoreCard->tee = $player["tee"];
                $scoreCard->scorecard_type = $request->get('scorecard_type');
                $scoreCard->scoring_type = $scoringType;
                $scoreCard->use_handicap = $useHandicap;
                $scoreCard->round_type = $request->get('round_type');
                $scoreCard->starting_hole = $startingHole;
                $scoreCard->team_size = $request->get('team_size');
                $scoreCard->team_number = 0;
                $scoreCard->actively_scoring = 1;

                $scoreCard->save();
                $updatedExistingRecord = true;


            }else if($scoreCard->player_member_id == $player['player_member_id']){
              //return with error if score entry has been made for any of the players and the player is not the requesting user
              $this->error = "player_already_being_scored";
              return $this->response();
            }
          }


          if($updatedExistingRecord){
            continue;
          }

          $member = Member::find($player['player_member_id']);
          if(!$member){
            $this->error = "member_not_exists";
            return $this->response();

          }

          $scoreCardEntry = ScoreCard::create([

            "reservation_id"=>$managerScoreCard->reservation_id,
            "reservation_type"=>$managerScoreCard->reservation_type,
            "player_member_id"=>$player['player_member_id'],
            "manager_member_id"=>$user->id,
            "handicap"=>$player["handicap"],
            "tee"=>$player["tee"],
            "scorecard_type"=>$request->get('scorecard_type'),
            "scoring_type"=>$scoringType,
            "use_handicap"=>$useHandicap,
            "round_type"=>$request->get('round_type'),
            "starting_hole"=>$startingHole,
            "team_size"=>$request->get('team_size'),
            "team_number"=>0, //Team numbers will be synchronized and re-assigned later according to the request teams data
            "actively_scoring"=>1
          ]);

          foreach($reservation->course->holes as $index=>$hole){

            //Only generate score entries for holes which are under round_type value
            if(($index+1) > $request->get('round_type'))
            {
              break;
            }
            else
            {



              $scoreHole = ScoreHole::create([
                "score_card_id"=>$scoreCardEntry->id,
                "hole_id"=>$hole->id,
                "score"=>0,
                "putts"=>0,
                "fairway"=>Config::get('global.score.fairway.center'),
              ]);



            }

          }


         // $scoreCardsToCreate[] = $player;
        }

       }

      //Find if there are any existing scorecards, players for which have not been sent in the request. I-e manager intends to remove them
      foreach($scoreCardGroup as $scoreCard){
        $playerSentInRequest = false;
        foreach($players as $player){
          if($scoreCard->player_member_id == $player["player_member_id"]){

            $playerSentInRequest = true;
            break;
          }

        }
        if(!$playerSentInRequest){
          //**************Deactivate Score Card***************//
          $scoreCard->manager_member_id = $scoreCard->player_member_id;
          $scoreCard->actively_scoring = 0;
          $scoreCard->team_number = 1;
          $scoreCard->save();
         // $scoreCardsToRemove[] = $scoreCard;
        }

      }


      //Sync and set team numbers for each scorecard for each player according to teams data received
      $scoreCards = ScoreCard::where("reservation_id" , $managerScoreCard->reservation_id)
        ->where("reservation_type",$managerScoreCard->reservation_type)
        ->where('manager_member_id',$user->id)
        ->get();

      foreach($request->get('teams') as $teamIndex => $team){
        foreach ($team["player_members"] as $teamMember){
          foreach($scoreCards as $scoreCard){
            if($scoreCard->player_member_id == $teamMember["player_member_id"]){
              $scoreCard->team_number = $teamIndex+1;
              $scoreCard->save();
              break;
            }
          }
        }
      }

      DB::commit();


      $this->response = ScoreCard::getScoreCardsOfAGroupByMemberIdAndReservationIdAndType($user->id,$managerScoreCard->reservation_id,$managerScoreCard->reservation_type);



    }
    catch(\Exception $e)
    {

      \DB::rollback();

      \Log::info(__METHOD__, [
        'error' => $e->getMessage()
      ]);
      $this->error =  "exception";
    }
    return $this->response();

  }

//  public function destroy(Request $request){
//
//    if (!$request->has('score_card_id')) {
//      $this->error = "scores_missing";
//      return $this->response();
//    }
//
//    $scoreCard = ScoreCard::find($request->get('score_card_id'));
//
//    if(!$scoreCard){
//      $this->error = "invalid_scorecard";
//      return $this->response();
//    }
//
//    if($scoreCard->manager_member_id != Auth::user()->id){
//      $this->error = "score_not_being_managed_by_user";
//      return $this->response();
//    }
//
//    $scoreCard->delete();
//
//    $this->response = "score_card_deleted_successfuly";
//    return $this->response();
//
//
//
//  }

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

        $scoreHole = ScoreHole::where('id',$score["score_hole_id"])->with('score_card','hole')->first();

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

        if($scoreHole->hole->hole_number < $scoreHole->score_card->starting_hole){
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



  public function getGroupScoreDetailed(Request $request){

    if (!$request->has('reservation_id')) {
      $this->error = "tennis_reservation_id_missing";
      return $this->response();
    }

    if (!$request->has('reservation_type')) {
      $this->error = "tennis_reservation_id_missing";
      return $this->response();
    }

    $scoreCardsGrouped =  ScoreCard::getGroupScoreDetailed(Auth::user()->id,$request->get('reservation_id'),$request->get('reservation_type'));
    if($scoreCardsGrouped === null){
      $this->error = "invalid_scorecard";
      return $this->response();
    }

    return $scoreCardsGrouped;
    
  }

  public function getSinglePlayerScoreDetailed(Request $request){

    if (!$request->has('reservation_id')) {
      $this->error = "tennis_reservation_id_missing";
      return $this->response();
    }

    if (!$request->has('reservation_type')) {
      $this->error = "tennis_reservation_id_missing";
      return $this->response();
    }

    $scoreCardSinglePlayer =  ScoreCard::getSinglePlayerScoreDetailed(Auth::user()->id,$request->get('reservation_id'),$request->get('reservation_type'));

    if($scoreCardSinglePlayer === null){
      $this->error = "invalid_scorecard";
      return $this->response();
    }

    return $scoreCardSinglePlayer;


  }


}
