<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
  public $fillable = [
    "club_id",
    "name"

  ];
  public $timestamps = false;
}
