<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
  public $fillable = [
    "category_id",
    "club_id",
    "name",
    "description",
    "image",
    "price",
    "in_stock",
    "visible"

  ];
  
}
