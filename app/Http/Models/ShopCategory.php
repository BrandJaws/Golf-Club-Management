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

  public function products(){
    return $this->hasMany(ShopProduct::class,'category_id');
  }

  public static function getProductsByCategoryIdPaginated($categoryId, $currentPage, $perPage, $search){
    //dd($categoryId,$currentPage,$perPage,$search);
    return ShopProduct::where('category_id',$categoryId)
                ->where(function ($query) use ($search) {
                  if ($search) {
                    $query->where('name', 'like', "%$search%");
                  }
                })
                ->paginate($perPage, array(
                  'id','category_id','name','image','in_stock', 'visible'
                ), 'current_page', $currentPage);
  }
}
