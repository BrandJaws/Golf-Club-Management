<?php

namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestaurantOrder extends Model
{
  protected $fillable = [
    'club_id',
    'member_id',
    'in_process',
    'is_ready',
    'is_served',
    'gross_total',
    'vat',
    'net_total'
  ];

  public static function getSingleOrderByIdWithDetails($restaurantOrderId){
    $restaurantOrder = RestaurantOrder::getSingleOrderById($restaurantOrderId);
    if(!$restaurantOrder){
      return null;
    }

    $restaurantOrder->restaurant_order_details = $restaurantOrder->getRestaurantOrderDetailsCustomized();
    return $restaurantOrder;
  }

  public static function getSingleOrderById($restaurantOrderId){
    $restaurantOrder = RestaurantOrder::leftJoin('member','restaurant_orders.member_id','=','member.id')
                                      ->where('restaurant_orders.id',$restaurantOrderId)
                                      ->select(
                                              'restaurant_orders.club_id',
                                              'restaurant_orders.id as id',
                                              'member_id',
                                              DB::raw('CONCAT(member.firstName," ",member.lastName) as member_name'),
                                              'in_process',
                                              'is_ready',
                                              'is_served',
                                              'gross_total',
                                        'vat',
                                        'net_total',
                                        DB::raw('DATE_FORMAT(restaurant_orders.created_at, "%b %D, %Y %h:%i:%s") as time')
                                      )
                                      ->first();

    return $restaurantOrder;


  }

  public static function getRecentOrdersForAMemberPaginated($memberId,$currentPage, $perPage){
    //dd($categoryId,$currentPage,$perPage,$search);
    return  RestaurantOrder::leftJoin('member','restaurant_orders.member_id','=','member.id')
                              ->where('member_id',$memberId)
                              ->orderBy('created_at','desc')
                              ->paginate($perPage, array(
                                'restaurant_orders.id as id',
                                'member_id',
                                DB::raw('CONCAT(member.firstName," ",member.lastName) as member_name'),
                                'in_process',
                                'is_ready',
                                'is_served',
                                'gross_total',
                                'vat',
                                'net_total',
                                DB::raw('DATE_FORMAT(restaurant_orders.created_at, "%b %D, %Y %h:%i:%s") as time')
                              ), 'current_page', $currentPage);

  }

 

  public function getRestaurantOrderDetailsCustomized(){

      return RestaurantOrderDetail::where('restaurant_order_id',$this->id)
                           ->leftJoin('restaurant_products','restaurant_order_details.restaurant_product_id','=','restaurant_products.id')
                           ->select('restaurant_order_details.id as id',
                                    'restaurant_order_details.restaurant_order_id',
                                    'restaurant_order_details.restaurant_product_id',
                                    'restaurant_products.name as restaurant_product_name',
                                    'restaurant_products.price as price',
                                    'restaurant_order_details.quantity',
                                    'restaurant_order_details.sale_total'

                           )
                          ->get();

  }

  public static function getOpenOrdersForAClub($clubId){
    //dd($categoryId,$currentPage,$perPage,$search);
    return  RestaurantOrder::leftJoin('member','restaurant_orders.member_id','=','member.id')
      ->where('restaurant_orders.club_id',$clubId)
      ->where('is_served',"NO")
      ->orderBy('restaurant_orders.created_at','asc')
      ->select(
        'restaurant_orders.id as id',
        'member_id',
        DB::raw('CONCAT(member.firstName," ",member.lastName) as member_name'),
        'in_process',
        'is_ready',
        'is_served',
        'gross_total',
        'vat',
        'net_total',
        DB::raw('DATE_FORMAT(restaurant_orders.created_at, "%b %D, %Y %h:%i:%s") as time')
      )
      ->get();

  }

  public static function paginatedListWithFilters($club_id, $perPage, $currentPage, $filters = null)
  {

    $orders = RestaurantOrder::leftJoin('member','restaurant_orders.member_id','=','member.id')
    ->where('restaurant_orders.club_id',$club_id)
    ->where('is_served',"YES")
    ->where(function ($query) use ($filters) {
        if ($filters) {
          if ((int) ($filters->memberId) > 0) {

            $query->where('member_id', $filters->memberId);
          }

          if ($filters->date) {

            if($filters->timeFrom){
              $query->where('restaurant_orders.created_at', '>=', Carbon::parse($filters->date." ".$filters->timeFrom)->toDateTimeString());
              if($filters->timeTo){
                $query->where('restaurant_orders.created_at', '<=', Carbon::parse($filters->date." ".$filters->timeTo)->toDateTimeString());
              }
            }else if($filters->timeTo){
              $query->where('restaurant_orders.created_at', '>=', Carbon::parse($filters->date)->toDateTimeString());
              $query->where('restaurant_orders.created_at', '<=', Carbon::parse($filters->date." ".$filters->timeTo)->toDateTimeString());
            }else{
              $query->whereDate('restaurant_orders.created_at', '=', Carbon::parse($filters->date)->toDateTimeString());
            }

          }else{
            if($filters->timeFrom || $filters->timeTo){
              $todaysDate = Carbon::today()->toDateString();
              if($filters->timeFrom){
                $query->where('restaurant_orders.created_at', '>=', Carbon::parse($todaysDate." ".$filters->timeFrom)->toDateTimeString());
                if($filters->timeTo){
                  $query->where('restaurant_orders.created_at', '<=', Carbon::parse($todaysDate." ".$filters->timeTo)->toDateTimeString());
                }
              }else if($filters->timeTo){
                $query->where('restaurant_orders.created_at', '>=', Carbon::parse($todaysDate)->toDateTimeString());
                $query->where('restaurant_orders.created_at', '<=', Carbon::parse($todaysDate." ".$filters->timeTo)->toDateTimeString());
              }
            }

          }

        }
      })
      ->orderBy('restaurant_orders.created_at','desc')
      ->paginate($perPage, [

      'restaurant_orders.id as id',
      'member_id',
      DB::raw('CONCAT(member.firstName," ",member.lastName) as member_name'),
      'in_process',
      'is_ready',
      'is_served',
      'gross_total',
        'vat',
        'net_total',
      DB::raw('DATE_FORMAT(restaurant_orders.created_at, "%b %D, %Y %h:%i:%s") as time'),


    ], 'page', $currentPage);
    return $orders;
  }

}
