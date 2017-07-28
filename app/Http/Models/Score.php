<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
  public $timestamps = false;

  public function reservation(){
    return $this->morphTo();
  }

  public function player_member(){
    return $this->belongsTo(Member::class);
  }

  public function score_holes(){
    return $this->hasMany(ScoreHole::class);
  }



}
