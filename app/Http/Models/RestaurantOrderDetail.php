<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOrderDetail extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'restaurant_order_id',
    'restaurant_product_id',
    'quantity',
    'sale_total',

  ];

  public function restaurant_order(){
    return $this->belongsTo(RestaurantOrder::class);
  }
}
