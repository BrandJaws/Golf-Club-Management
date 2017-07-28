<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreHole extends Model
{
  public $timestamps = false;

  public function score(){
    return $this->belongsTo(Score::class);
  }

  public function hole(){
    return $this->belongsTo(CourseHole::class);
  }

}
