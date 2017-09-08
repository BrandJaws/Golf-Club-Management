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
    "in_stock",
    "visible"

  ];
  public $timestamps = false;
}
