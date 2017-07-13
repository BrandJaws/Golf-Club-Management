<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CourseHole extends Model
{

  protected $fillable = [
    'course_id',
    'mens_handicap',
    'mens_par',
    'womens_handicap',
    'womens_par',
    'tee_values',
  ];

  public function Course()
  {
    return $this->belongsTo("App\Http\Models\Course");
  }

  public static function  validateDataAgainstModel($data){
    $validator = Validator::make($data, [
      'mens_handicap' => 'required|min:1,max:50',
      'mens_par' => 'required|date_format:H:i',
      'womens_handicap' => 'required|date_format:H:i',
      'womens_par' => 'required|numeric',
      'tee_values' => 'required',
    ]);

    if ($validator->fails()) {
      return false;
    }

    $teesData = json_decode($data('tee_values'));
    if(!$teesData || !is_array($teesData) || !count($teesData)){
      return false;
    }

    return true;

  }
}
