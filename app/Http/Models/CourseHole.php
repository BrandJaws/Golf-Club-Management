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
      'mens_handicap' => 'required|integer',
      'mens_par' => 'required|integer',
      'womens_handicap' => 'required|integer',
      'womens_par' => 'required|integer',
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
