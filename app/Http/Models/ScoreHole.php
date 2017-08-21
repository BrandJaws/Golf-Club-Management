<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ScoreHole extends Model
{
  public $fillable = [
    "score_card_id",
    "hole_id",
    "score",
    "putts",
    "fairway",

  ];
  public $timestamps = false;

  public function score_card(){
    return $this->belongsTo(ScoreCard::class,"score_card_id");
  }

  public function hole(){
    return $this->belongsTo(CourseHole::class);
  }

  public function calculateToPar($playerHandicap,$holeHandicap,$score, $par , $playerIsLate){
    
    $holePar = $par;
    //Only raise hole par for player if he is on time i-e the calculation of score is 
    if($playerIsLate == Config::get('global.score.handicap_options.no')){
      if($holeHandicap <= $playerHandicap){
        $holePar++;
      }
    }

    if($score == 0){
      $toPar = null;
    }else{
      $toPar = $score - $holePar;
    }

    return $toPar;

  }

}
