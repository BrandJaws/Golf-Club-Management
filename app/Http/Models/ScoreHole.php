<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreHole extends Model
{
  public $fillable = [
    "score_card_id",
    "hole_id",
    "score",
    "putts",
    "fairway",
    "distance",
    "par",
    "handicap",
    "player_is_late",

  ];
  public $timestamps = false;

  public function score_card(){
    return $this->belongsTo(ScoreCard::class,"score_card_id");
  }

  public function hole(){
    return $this->belongsTo(CourseHole::class);
  }

}
