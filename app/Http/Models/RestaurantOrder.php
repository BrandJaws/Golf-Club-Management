<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrder extends Model
{
  protected $fillable = [
    'club_id',
    'member_id',
    'is_ready',
    'is_served',
    'gross_total',
  ];

  public function order_details(){
    return $this->hasMany(RestaurantOrderDetail::class);
  }
}
