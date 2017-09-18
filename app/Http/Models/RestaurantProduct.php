<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantProduct extends Model
{
  public $fillable = [

    "club_id",
    "restaurant_sub_category_id",
    "name",
    "description",
    "image",
    "price",
    "in_stock",
    "visible"

  ];
  
}
