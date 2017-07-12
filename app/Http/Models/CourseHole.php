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
}
