<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSubCategory extends Model
{
  public $fillable = [
    "club_id",
    "restaurant_main_category_id",
    "name"

  ];
  public $timestamps = false;

  public function products(){
    return $this->hasMany(RestaurantProduct::class,'restaurant_sub_category_id');
  }

  public static function getProductsByCategoryIdPaginated($categoryId, $currentPage, $perPage, $search, $showOnlyVisible = false, $newestFirst = false){
    //dd($categoryId,$currentPage,$perPage,$search);
    $query =  RestaurantProduct::where('restaurant_sub_category_id',$categoryId)
                ->where(function ($query) use ($search) {
                  if ($search) {
                    $query->where('name', 'like', "%$search%");
                  }
                })
                ->where(function ($query) use ($showOnlyVisible) {
                  if ($showOnlyVisible) {
                    $query->where('visible', 'YES');
                  }
                });

     if($newestFirst){
       $query->orderBy('created_at','desc');
     }


      return $query->paginate($perPage, array(
               'id','restaurant_sub_category_id','name','image','price','in_stock', 'visible'
              ), 'current_page', $currentPage);
  }
}
