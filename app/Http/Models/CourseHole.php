<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

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

  public static function  validateDataListAgainstModel(&$dataList, $colors){

    $foundOneOrMoreErrors = false;

    foreach($dataList as $holeIndex=>$hole){

      //Unset previous errors
      if(isset($dataList[$holeIndex]['error'])){
        unset($dataList[$holeIndex]['error']);
      }

      $validator = Validator::make($hole, [
        'hole_number'=>'required|integer|min:1',
        'mens_handicap' => 'required|integer|min:1',
        'mens_par' => 'required|integer|min:1',
        'womens_handicap' => 'required|integer|min:1',
        'womens_par' => 'required|integer|min:1',
        'tee_values' => 'required',
      ]);



      if ($validator->fails()) {
        $dataList[$holeIndex]['error'] =  json_decode(json_encode($validator->errors()),true);
        $foundOneOrMoreErrors = true;
      }


      if(!$dataList[$holeIndex]['tee_values'] || !is_array($dataList[$holeIndex]['tee_values']) || !count($dataList[$holeIndex]['tee_values'])){
        Course::ensureErrorsPropertyOnData($dataList[$holeIndex]['tee_values'], "tee_values");
        $dataList[$holeIndex]['tee_values']['error']['tee_values'][] =  "Please Select Atleast One Tee";
        $foundOneOrMoreErrors = true;
      }


      if(!CourseHole::validateTeeValues($dataList[$holeIndex]['tee_values'], $colors)){
        $foundOneOrMoreErrors = true;

      }
    }

    if($foundOneOrMoreErrors){
      return false;
    }else{
      return true;
    }

  }

  public static function validateTeeValues(&$data, $colors){

    $foundOneOrMoreErrors = false;

    $validationRules = [
      'color' => 'required|string',
      'distance' => 'required|integer|min:1',
    ];



    foreach($data as $index => $tee){

      //Unset previous errors
      if(isset($data[$index]['error'])){
        unset($data[$index]['error']);
      }
      
      $validator = Validator::make($tee,$validationRules);

      if($validator->fails()){

        $data[$index]['error'] = json_decode(json_encode($validator->errors()), true);
        $foundOneOrMoreErrors = true;

      }
      if(array_search($tee['color'],$colors) === false){

        Course::ensureErrorsPropertyOnData($data[$index], "color");
        $data[$index]['error']['color'][] = "Please Select A Valid Color";
        $foundOneOrMoreErrors = true;
      }


    }

    if($foundOneOrMoreErrors){
      return false;
    }else{
      return true;
    }


  }

  


}
