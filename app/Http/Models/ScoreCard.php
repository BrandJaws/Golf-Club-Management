<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ScoreCard extends Model
{
  use \PushNotification;
  
  public $fillable = [
    "reservation_id",
    "reservation_type",
    "player_member_id",
    "manager_member_id",
    "handicap",
    "tee",
    "scorecard_type",
    "use_handicap",
    "scoring_type",
    "round_type",
    "starting_hole",
    "actively_scoring",
    "team_size",
    "team_number"
  ];
  public $timestamps = false;

  public function reservation(){
    return $this->morphTo();
  }

  public function manager_member(){
    return $this->belongsTo(Member::class, "manager_member_id");
  }

  public function player_member(){
    return $this->belongsTo(Member::class, "player_member_id");
  }

  public function score_holes(){
    return $this->hasMany(ScoreHole::class);
  }



  /**
   * @param $memberId
   * @param $reservationId
   * @param $reservationType
   *
   *
   * to get group scores and positions
   *
   * ****Return null if no scorecards are found****
   */
  public static function getSinglePlayerScoreDetailed($memberId, $reservationId, $reservationType){

    //Get group score detailed
    $groupScoreDetailed = self::getGroupScoreDetailed($memberId, $reservationId, $reservationType);
    if($groupScoreDetailed === null){
      return $groupScoreDetailed;
    }

    //Remove other player_members
    foreach($groupScoreDetailed["teams"] as $teamIndex => $team){
      foreach($team["player_members"] as $playerMemberIndex => $playerMember){
        if($playerMember["id"] != $memberId){
          unset($groupScoreDetailed["teams"][$teamIndex]["player_members"][$playerMemberIndex]);
        }
      }
      $groupScoreDetailed["teams"][$teamIndex]["player_members"] = array_values($groupScoreDetailed["teams"][$teamIndex]["player_members"]);
      if(!count($groupScoreDetailed["teams"][$teamIndex]["player_members"])){
        unset($groupScoreDetailed["teams"][$teamIndex]);
      }

    }

    $groupScoreDetailed["teams"] = array_values($groupScoreDetailed["teams"]);
    //Remove Scores for other members
    foreach($groupScoreDetailed["scores_by_hole"] as $scoreHoleIndex => $scoreHole){
      foreach($scoreHole["scores_by_teams"] as $teamScoreIndex => $teamScore){
        foreach($teamScore["player_member_scores"] as $playerScoreIndex => $playerScore){
          if($playerScore["player_member_id"] != $memberId){
            unset($groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"][$teamScoreIndex]["player_member_scores"][$playerScoreIndex]);
          }
        }
        $groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"][$teamScoreIndex]["player_member_scores"] = array_values($groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"][$teamScoreIndex]["player_member_scores"]);
        if(!count($groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"][$teamScoreIndex]["player_member_scores"])){
          unset($groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"][$teamScoreIndex]);
        }

      }
      $groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"] = array_values($groupScoreDetailed["scores_by_hole"][$scoreHoleIndex]["scores_by_teams"]);

    }



    return $groupScoreDetailed;



  }

  /**
   * @param $memberId
   * @param $reservationId
   * @param $reservationType
   *
   *
   * to get group scores and positions
   *
   * ****Return null if no scorecards are found****
   */
  public static function getGroupScoreDetailed($memberId, $reservationId, $reservationType){

    $scoreCardsGrouped = self::getScoreCardsOfAGroupByMemberIdAndReservationIdAndType($memberId, $reservationId, $reservationType);

    //return with value if null or there is only one player since scoredetails can only be calculated if there are more than one players or teams involved
    if($scoreCardsGrouped === null ){
      return $scoreCardsGrouped;
    }

    //Calculate team totals

    foreach($scoreCardsGrouped["scores_by_hole"] as $holeIndex => $scoreByHole){
      foreach($scoreByHole["scores_by_teams"] as $teamIndex => $scoreByTeam){
        foreach($scoreByTeam["player_member_scores"] as $teamMemberScore){

          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["team_score"] += $teamMemberScore["score"];
          if($scoreCardsGrouped["use_handicap"] == Config::get('global.score.handicap_options.yes') && $teamMemberScore["score"] > 0){
            $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["team_to_par"] += $teamMemberScore["to_par"];
          }
        }

      }


    }


  if(count($scoreCardsGrouped["teams"]) >= 2){

    if(
      $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.matchPlay')
      || $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.skinsGame')
    ){

      foreach($scoreCardsGrouped["scores_by_hole"] as $scoreHoleIndex => $scoreByHole){

        foreach($scoreByHole["scores_by_teams"] as $teamIndex => $scoreByTeam){

          //assign winner_member_id according to scoring_type
          if($scoreCardsGrouped["scoring_type"] == Config::get('global.score.scoring_type_options.gross')){

            //winner team will only be determined if it has played the hole i-e its score is > 1
            if($scoreByTeam["team_score"] > 0){
              //if a winning score has been set for a previous team and its is equal to current team's score at hand, its a tie
              if($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"] !== null && $scoreByTeam["team_score"] == $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"]){

                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_team_number"] = null;
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"] = null;
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["tie"] = true;

                //if its a skins game transfer the winning amount for hole to the next hole if exists
                if(isset($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex+1])){
                  $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex+1]["winning_amount"] += $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_amount"];
                }
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_amount"] = 0;
                break;

                //Else if winning_score has not been set or score by current team at hand is less than the previous winning_score override winning_score to this team's score
              }else if($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"] === null || $scoreByTeam["team_score"] < $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"]){

                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_score"] = $scoreByTeam["team_score"];
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_team_number"] = $scoreByTeam["team_number"];

              }


            }


          }else if($scoreCardsGrouped["scoring_type"] == Config::get('global.score.scoring_type_options.net')){

            //winner team will only be determined if it has played the hole i-e its score is > 1
            if($scoreByTeam["team_score"] > 0){
              //if a winning score has been set for a previous team and its is equal to current team's score at hand,
              if($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"] !== null && $scoreByTeam["team_to_par"] == $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"]){

                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_team_number"] = null;
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"] = null;
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["tie"] = true;
                //if its a skins game transfer the winning amount for hole to the next hole if exists
                if(isset($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex+1])){
                  $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex+1]["winning_amount"] += $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_amount"];
                }
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_amount"] = 0;
                break;

                //Else if winning_score has not been set or score by current team at hand is less than the previous winning_score override winning_score to this team's score
              }else if($scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"] === null || $scoreByTeam["team_to_par"] < $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"]){

                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_to_par"] = $scoreByTeam["team_to_par"];
                $scoreCardsGrouped["scores_by_hole"][$scoreHoleIndex]["winning_team_number"] = $scoreByTeam["team_number"];

              }


            }

          }

        }


      }

    }
  }



    //Calculate scoring summary for each team i-e score_total, to_par_total, holes_won, amount_won

    foreach($scoreCardsGrouped["scores_by_hole"] as $scoreHoleIndex => $scoreByHole){
      foreach($scoreByHole["scores_by_teams"] as $teamIndex => $scoreByTeam){

        foreach($scoreByTeam["player_member_scores"] as $memberIndex => $teamMemberScore){
          $scoreCardsGrouped["teams"][$teamIndex]["player_members"][$memberIndex]["score_total"] += $teamMemberScore["score"];
          //to_par_total will be calculated if handicap is selected
          if($scoreCardsGrouped["use_handicap"] == Config::get('global.score.handicap_options.yes')){

            $scoreCardsGrouped["teams"][$teamIndex]["player_members"][$memberIndex]["to_par_total"] += $teamMemberScore["to_par"];

          }
        }
        //Score will be calculated regradless of scorecard_type or scoring_type
        $scoreCardsGrouped["teams"][$teamIndex]["score_total"] += $scoreByTeam["team_score"];

        //to_par_total will be calculated if handicap is selected
        if($scoreCardsGrouped["use_handicap"] == Config::get('global.score.handicap_options.yes')){

          $scoreCardsGrouped["teams"][$teamIndex]["to_par_total"] += $scoreByTeam["team_to_par"];

        }

        //holes_won will be calculated if game type is either MATCH PLAY or SKINS GAME
        if
        (
          (
            $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.matchPlay')
            || $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.skinsGame')
          )
          && $scoreByHole["winning_team_number"] == $scoreCardsGrouped["teams"][$teamIndex]["team_number"]

        )
        {
          $scoreCardsGrouped["teams"][$teamIndex]["holes_won"]++;
        }

        if
        (
          $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.skinsGame')
          && $scoreByHole["winning_team_number"] == $scoreCardsGrouped["teams"][$teamIndex]["team_number"]
        )
        {
          $scoreCardsGrouped["teams"][$teamIndex]["amount_won"] += $scoreByHole["winning_amount"];
        }



      }
    }

    //Sort the teams according to the criteria dictated by scorecard_type and scoring_type
    //and assign positions

    if($scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.strokePlay')){

      if($scoreCardsGrouped["scoring_type"] == Config::get('global.score.scoring_type_options.gross')){
        usort($scoreCardsGrouped["teams"], function($a,$b){
          if ($a["score_total"] == $b["score_total"]) {
            return 0;
          }
          return ($a["score_total"] >  $b["score_total"]) ? 1 : -1;
        });
        //assign positions
        $position = 1;
        foreach($scoreCardsGrouped["teams"] as $teamIndex => $team){

          if
          (
            isset($scoreCardsGrouped["teams"][$teamIndex-1])
            && $scoreCardsGrouped["teams"][$teamIndex-1]["score_total"] != $scoreCardsGrouped["teams"][$teamIndex]["score_total"]
          )
          {
            //Increment position if last player's(if any) and current players scores are different
            $position++;


          }
          $scoreCardsGrouped["teams"][$teamIndex]["position"] = $position;

        }
      }else if($scoreCardsGrouped["scoring_type"] == Config::get('global.score.scoring_type_options.net')){
        usort($scoreCardsGrouped["teams"], function($a,$b){
          if ($a["to_par_total"] == $b["to_par_total"]) {
            return 0;
          }
          return ($a["to_par_total"] >  $b["to_par_total"]) ? 1 : -1;
        });
        //assign positions
        $position = 1;
        foreach($scoreCardsGrouped["teams"] as $teamIndex => $team){

          if
          (
            isset($scoreCardsGrouped["teams"][$teamIndex-1])
            && $scoreCardsGrouped["teams"][$teamIndex-1]["to_par_total"] != $scoreCardsGrouped["teams"][$teamIndex]["to_par_total"]
          )
          {
            //Increment position if last player's(if any) and current players scores are different
            $position++;


          }
          $scoreCardsGrouped["teams"][$teamIndex]["position"] = $position;

        }

      }

    }else if($scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.matchPlay')){
      usort($scoreCardsGrouped["teams"], function($a,$b){
        if ($a["holes_won"] == $b["holes_won"]) {
          return 0;
        }
        return ($a["holes_won"] <  $b["holes_won"]) ? 1 : -1;
      });
      //assign positions
      $position = 1;
      foreach($scoreCardsGrouped["teams"] as $teamIndex => $team){

        if
        (
          isset($scoreCardsGrouped["teams"][$teamIndex-1])
          && $scoreCardsGrouped["teams"][$teamIndex-1]["holes_won"] != $scoreCardsGrouped["teams"][$teamIndex]["holes_won"]
        )
        {
          //Increment position if last player's(if any) and current players scores are different
          $position++;


        }
        $scoreCardsGrouped["teams"][$teamIndex]["position"] = $position;

      }
    }else if($scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.skinsGame')){

      usort($scoreCardsGrouped["teams"], function($a,$b){
        if ($a["amount_won"] == $b["amount_won"]) {
          return 0;
        }
        return ($a["amount_won"] <  $b["amount_won"]) ? 1 : -1;
      });
      //assign positions
      $position = 1;
      foreach($scoreCardsGrouped["teams"] as $teamIndex => $team){

        if
        (
          isset($scoreCardsGrouped["teams"][$teamIndex-1])
          && $scoreCardsGrouped["teams"][$teamIndex-1]["amount_won"] != $scoreCardsGrouped["teams"][$teamIndex]["amount_won"]
        )
        {
          //Increment position if last player's(if any) and current players scores are different
          $position++;


        }
        $scoreCardsGrouped["teams"][$teamIndex]["position"] = $position;

      }
    }


    return $scoreCardsGrouped;

  }


  /**
   * @param $memberId
   * @param $reservationId
   * @param $reservationType
   * @param $selectiveMembers
   *
   * to get scorecards for a group in which member(memberId) played.
   * Method will return a score card which will have scores for each player grouped by holes
   * adjusted for extra difference in properties for hole score such as player's tee and hole_handicap
   * $selectiveMembers expects an array of member ids if we want the scorecard results for selective members only
   *
   * ****Return null if no scorecards are found****
   */
  public static function getScoreCardsOfAGroupByMemberIdAndReservationIdAndType($memberId, $reservationId, $reservationType){


      $scoreCards = ScoreCard::where("reservation_id" , $reservationId)
                              ->where("reservation_type",$reservationType)
                              ->where("manager_member_id",  function($query) use($memberId,$reservationId, $reservationType){
                                  $query->select('manager_member_id')
                                        ->from('score_cards')
                                        ->where("reservation_id" , $reservationId)
                                        ->where("reservation_type",$reservationType)
                                        ->where("player_member_id",$memberId)
                                        ->first();
                              })
                              //->where("actively_scoring",1)
                              ->with([
                                      'player_member'=>function($query){
                                        $query->select('id','firstName','lastName','profilePic','gender');
                                      },
                                       'score_holes'=>function($query){
                                         $query->with(['hole'=>function($query){
                                            $query->select('id','hole_number','mens_par','mens_handicap','womens_par','womens_handicap','tee_values');
                                         }]);
                                      }])
                              ->orderBy('team_number')
                              ->get();

      if($scoreCards->count() < 1){
          return null;
      }

      $manager_score_card_id = 0;

      foreach($scoreCards as $scoreCard){
        if($scoreCard->manager_member_id == $scoreCard->player_member_id ){
          $manager_score_card_id = $scoreCard->id;
        }
      }

      $scoreCardsGrouped = [
        "manager_score_card_id" => $manager_score_card_id,
        "reservation_id" => $scoreCards[0]->reservation_id,
        "reservation_type" => $scoreCards[0]->reservation_type,
        "manager_member_id" => $scoreCards[0]->manager_member_id,
        "scorecard_type" => $scoreCards[0]->scorecard_type,
        "use_handicap" => $scoreCards[0]->use_handicap,
        "scoring_type" => $scoreCards[0]->scoring_type,
        "round_type" => $scoreCards[0]->round_type,
        "starting_hole" => $scoreCards[0]->starting_hole,
        "teams" =>[],
        "scores_by_hole"=>[]

      ];

    //Add Teams
    $teamNumber = 0;
    $teamIndex = -1;
    foreach($scoreCards as $scoreCard){

      if($scoreCard->team_number != $teamNumber){

        $teamNumber = $scoreCard->team_number;
        $teamIndex++;
        $scoreCardsGrouped["teams"][$teamIndex] = [];
        $scoreCardsGrouped["teams"][$teamIndex]["team_number"] = $teamNumber;
        $scoreCardsGrouped["teams"][$teamIndex]["player_members"] = [];
        $scoreCardsGrouped["teams"][$teamIndex]["score_total"] = 0;
        $scoreCardsGrouped["teams"][$teamIndex]["to_par_total"] = null;
        $scoreCardsGrouped["teams"][$teamIndex]["holes_won"] = 0;
        $scoreCardsGrouped["teams"][$teamIndex]["amount_won"] = 0;
        $scoreCardsGrouped["teams"][$teamIndex]["position"]= null;


      }

      $player  = $scoreCard->player_member->toArray();
      $player["score_total"] = null;
      $player["to_par_total"] = null;
      $scoreCardsGrouped["teams"][$teamIndex]["player_members"][] = $player;
    }


    //Add score for each team to scores_by_teams array


    foreach ($scoreCards[0]->score_holes as $holeIndex => $scoreHole){
      //Calculate dollar value of hole for skins game if it is
      $holeDollarValue = null;
      if( $scoreCardsGrouped["scorecard_type"] == Config::get('global.score.scorecard_types.skinsGame')){
        foreach(Config::get('global.score.skins_game_hole_values') as $holeNumber => $value){
          if($holeIndex < $holeNumber){
            $holeDollarValue = $value;
            break;
          }
        }
      }

      $scoreCardsGrouped["scores_by_hole"][] = [
        "hole_number" => $scoreHole->hole->hole_number,
        "scores_by_teams"=>[],
        "winning_team_number"=>null,
        "tie" => false,
        "winning_score" => null,
        "winning_to_par" => null,
        "winning_amount"=>$holeDollarValue,

      ];



      $teamNumber = 0;
      $teamIndex = -1;

      //Add score for each player to scores array
      foreach($scoreCards as $scoreCardIndex => $scoreCard){

        //Create new team if the team number changes
        if($scoreCard->team_number != $teamNumber){

          $teamNumber = $scoreCard->team_number;
          $teamIndex++;
          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex] = [];
          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["team_number"] = $teamNumber;
          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["player_member_scores"] = [];
          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["team_score"] = null;
          $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["team_to_par"] = null;


        }

        if($scoreCard->player_member->gender == Config::get('global.gender.male')){
          $par = $scoreHole->hole->mens_par;
          $handicap = $scoreHole->hole->mens_handicap;
        }else{
          $par = $scoreHole->hole->womens_par;
          $handicap = $scoreHole->hole->womens_handicap;
        }

        $distance = null;
        foreach(json_decode($scoreHole->hole->tee_values) as $tee){
          if($scoreCard->tee == $tee->color){
            $distance = $tee->distance;
            break;
          }
        }



        $scoreCard->score_holes[$holeIndex]->player_is_late = $holeIndex+1 < $scoreCard->starting_hole ? Config::get('global.score.handicap_options.yes') : Config::get('global.score.handicap_options.no');
        $scoreCard->score_holes[$holeIndex]->par = $par;
        $scoreCard->score_holes[$holeIndex]->handicap = $handicap;
        $scoreCard->score_holes[$holeIndex]->distance = $distance;
        $scoreCard->score_holes[$holeIndex]->score = $holeIndex+1 < $scoreCard->starting_hole ? $par+ Config::get('global.score.latePenaltyStrokesAbovePar') : $scoreCard->score_holes[$holeIndex]->score;

        $toPar = $scoreCardsGrouped["use_handicap"] == Config::get('global.score.handicap_options.yes') ? $scoreCard->score_holes[$holeIndex]->calculateToPar($scoreCard->handicap,$handicap,$scoreCard->score_holes[$holeIndex]->score,$par, $scoreCard->score_holes[$holeIndex]->player_is_late) : null;
        //dd($scoreCard->score_holes[$holeIndex]->calculateToPar($scoreCard->handicap));
        $scoreCard->score_holes[$holeIndex]->to_par = $toPar;
        $scoreCard->score_holes[$holeIndex]->hole_handicap = $scoreCard->score_holes[$holeIndex]->handicap;

        $scoreCard->score_holes[$holeIndex]->score_hole_id = $scoreCard->score_holes[$holeIndex]->id;

        $scoreCard->score_holes[$holeIndex]->player_handicap = $scoreCard->handicap;
        $scoreCard->score_holes[$holeIndex]->tee = $scoreCard->tee;
        $scoreCard->score_holes[$holeIndex]->player_member_id = $scoreCard->player_member->id;


        $score = $scoreCard->score_holes[$holeIndex]->toArray();

        //Unset unnecessary values
        unset($score["handicap"]);
        unset($score["id"]);
        unset($score["hole"]["mens_par"]);
        unset($score["hole"]["mens_handicap"]);
        unset($score["hole"]["womens_par"]);
        unset($score["hole"]["womens_handicap"]);
        unset($score["hole"]["tee_values"]);

        $scoreCardsGrouped["scores_by_hole"][$holeIndex]["scores_by_teams"][$teamIndex]["player_member_scores"][] = $score;



      }

    }


   return $scoreCardsGrouped;


  }


  /**
   * @param $timeStart
   * @param $parent
   * @param $courseName
   *
   * To be used to send a push notification to a player who was managing another player's score when that other player
   * opts to do scoring on his own
   */
  public function sendNotificationToScoreManagerWhenAPlayerOvertakesManagement($previousManagerMember,$memberOvertakenBy){


    $useCase = \Config::get ( 'global.pushNotificationsUseCases.score_management_overtaken' );
    $title = "Score Management Overtaken";
    $body = trans('message.pushNotificationMessageBodies.score_management_overtaken',['memberName'=>$memberOvertakenBy->firstName.' '.$memberOvertakenBy->lastName]);

    if($previousManagerMember->device_type == "Iphone"){

      $this->sendNotification( $body,
        $previousManagerMember->device_registeration_id,
        $previousManagerMember->device_type,
        self::getIOSOptionsObject(
          $useCase,
          $title,
          $body,
          [ 'player_member_id'=> $memberOvertakenBy->id ]
        ),
        $previousManagerMember->id,
        $this);
    }
    //Android logic to follow

  }


}
