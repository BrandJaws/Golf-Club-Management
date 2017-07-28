<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
  public $timestamps = false;

  public function reservation(){
    return $this->morphTo();
  }

}
