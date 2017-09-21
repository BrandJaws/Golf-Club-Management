<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Models\RestaurantMainCategory;
use App\Http\Models\RestaurantOrder;
use App\Http\Models\RestaurantOrderDetail;
use App\Http\Models\RestaurantProduct;
use App\Http\Models\RestaurantSubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller {

	public function getAllCategories(){

		$mainCategories = RestaurantMainCategory::where('club_id',Auth::user()->club_id)
			->with([
				'sub_categories'=>function($query){
					$query->orderBy('name');
				}
			])
			->orderBy('name')
			->get();
		if($mainCategories->count() >0){
			$this->response = $mainCategories;
		}else{
			$this->error = "no_categories_found";
		}

		return $this->response();
	}

	public function getProductsBySubCategory($category_id,Request $request){


		$currentPage = $request->has('current_page') && is_numeric($request->get('current_page'))? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') && is_numeric($request->get('per_page')) ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$search = $request->has('search') ? $request->get('search') : false;
		$showOnlyVisible = true;

		$newest = $request->has('newest') && strtolower($request->get('newest')) == 'true' ? true : false;
		
		$products = RestaurantSubCategory::getProductsByCategoryIdPaginated($category_id,$currentPage, $perPage, $search, $showOnlyVisible, $newest );

		$this->response = $products;

		return $this->response();

	}

	public function getProductById($product_id){



		$product = RestaurantProduct::findProductByIdForAMember($product_id,Auth::user());
		if(!$product){

			$this->error = "product_not_found";

		}else{

			$this->response = $product;

		}

		return $this->response();

	}

	public function placeNewOrder(Request $request){

		if (!$request->has('restaurant_order_details') || !is_array($request->get('restaurant_order_details')) || count($request->get('restaurant_order_details')) < 1) {
			$this->error = "restaurant_order_details_missing";
			return $this->response();
		}

		$orderDetails = $request->get('restaurant_order_details');
		$orderGrossTotal = 0;
		//Validate if order details are valid
		foreach($orderDetails as $index => $orderDetail){
			if(!isset($orderDetail["restaurant_product_id"]) || !isset($orderDetail["quantity"]) ){
				$this->error = "invalid_order_details";
				return $this->response();
			}
			$orderDetails[$index]["quantity"] = intval($orderDetail["quantity"]);
			if($orderDetails[$index]["quantity"] < 1){
				$this->error = "invalid_order_details";
				return $this->response();
			}

			$orderDetails[$index]["restaurant_product"] = RestaurantProduct::findProductByIdForAMember($orderDetail["restaurant_product_id"],Auth::user());

			if(!$orderDetails[$index]["restaurant_product"]){
				$this->error = "invalid_order_details";
				return $this->response();
			}
			$orderDetails[$index]["sale_total"] = $orderDetails[$index]["quantity"] * $orderDetails[$index]["restaurant_product"]->price;
			$orderGrossTotal += $orderDetails[$index]["sale_total"];

		}

		try{
			DB::beginTransaction();

			$restaurantOrder = RestaurantOrder::create([

				'club_id' => Auth::user()->club_id,
				'member_id'=> Auth::user()->id,
				'gross_total'=> $orderGrossTotal,

			]);

			foreach($orderDetails as $index => $orderDetail){

				RestaurantOrderDetail::create([
					'restaurant_order_id' => $restaurantOrder->id,
					'restaurant_product_id' => $orderDetail["restaurant_product_id"],
					'quantity' => $orderDetail["quantity"],
					'sale_total' => $orderDetail["sale_total"],
				]);
			}

			DB::commit();


			$this->response = "order_creation_successful";


		}catch (\Exception $e){

			\DB::rollback();
			\Log::info(__METHOD__, [
				'error' => $e->getMessage()
			]);
			$this->error = "exception";
		}

		return $this->response();

	}

	public function getSingleOrder($order_id){

		$order = RestaurantOrder::getSingleOrderByIdWithDetails($order_id);

		if(!$order){
			$this->error = "order_not_found";
		}else{
			$this->response = $order;
		}


		return $this->response();
	}

	public function updateOrder(Request $request){
		if (!$request->has('restaurant_order_id')) {
			$this->error = "order_id_missing";
			return $this->response();
		}
		$order = RestaurantOrder::getSingleOrderById($request->get("restaurant_order_id"));
		if(!$order){
			$this->error = "order_id_missing";
			return $this->response();
		}

		if($order->in_process == 'YES'){
			$this->error = "order_already_in_process";
			return $this->response();
		}

		if($order->member_id != Auth::user()->id){
			$this->error = "order_doesnt_belong_to_requesting_body";
			return $this->response();
		}

		$previousOrderDetails = $order->getRestaurantOrderDetailsCustomized();
		if (!$request->has('restaurant_order_details') || !is_array($request->get('restaurant_order_details')) || count($request->get('restaurant_order_details')) < 1) {
			$this->error = "restaurant_order_details_missing";
			return $this->response();
		}

		$orderDetails = $request->get('restaurant_order_details');
		$orderGrossTotal = 0;
		//Validate if order details are valid
		foreach($orderDetails as $index => $orderDetail){
			if(!isset($orderDetail["restaurant_product_id"]) || !isset($orderDetail["quantity"]) ){
				$this->error = "invalid_order_details";
				return $this->response();
			}
			$orderDetails[$index]["quantity"] = intval($orderDetail["quantity"]);
			if($orderDetails[$index]["quantity"] < 1){
				$this->error = "invalid_order_details";
				return $this->response();
			}

			$orderDetails[$index]["restaurant_product"] = RestaurantProduct::findProductByIdForAMember($orderDetail["restaurant_product_id"],Auth::user());

			if(!$orderDetails[$index]["restaurant_product"]){
				$this->error = "invalid_order_details";
				return $this->response();
			}
			$orderDetails[$index]["sale_total"] = $orderDetails[$index]["quantity"] * $orderDetails[$index]["restaurant_product"]->price;
			$orderGrossTotal += $orderDetails[$index]["sale_total"];

		}

		try{
			DB::beginTransaction();

			foreach($previousOrderDetails as $orderDetailOld){

				$orderDetailOld->delete();
			}
			$order->gross_total = $orderGrossTotal;
			$order->save();
			foreach($orderDetails as $index => $orderDetail){

				RestaurantOrderDetail::create([
					'restaurant_order_id' => $order->id,
					'restaurant_product_id' => $orderDetail["restaurant_product_id"],
					'quantity' => $orderDetail["quantity"],
					'sale_total' => $orderDetail["sale_total"],
				]);
			}

			DB::commit();


			$this->response = "order_updation_successful";


		}catch (\Exception $e){

			\DB::rollback();
			\Log::info(__METHOD__, [
				'error' => $e->getMessage()
			]);
			$this->error = "exception";
		}

		return $this->response();



	}

	public function deleteOrder(Request $request){
		if (!$request->has('restaurant_order_id')) {
			$this->error = "order_id_missing";
			return $this->response();
		}
		$order = RestaurantOrder::getSingleOrderById($request->get("restaurant_order_id"));
		if(!$order){
			$this->error = "order_id_missing";
			return $this->response();
		}

		if($order->in_process == 'YES'){
			$this->error = "order_already_in_process";
			return $this->response();
		}

		if($order->member_id != Auth::user()->id){
			$this->error = "order_doesnt_belong_to_requesting_body";
			return $this->response();
		}

		try{
			DB::beginTransaction();

			$orderDetails = $order->getRestaurantOrderDetailsCustomized();

			foreach($orderDetails as $orderDetailOld){

				$orderDetailOld->delete();
			}

			$order->delete();
			$this->response = "order_deletion_successful";

			DB::commit();

		}catch (\Exception $e){

			DB::rollBack();
			\Log::info(__METHOD__, [
				'error' => $e->getMessage()
			]);
			$this->error = "exception";
		}

		return $this->response();



	}

	public function getOrdersForMember(Request $request){

		$currentPage = $request->has('current_page') && is_numeric($request->get('current_page'))? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') && is_numeric($request->get('per_page')) ? $request->get('per_page') : \Config::get('global.portal_items_per_page');



		$orders = RestaurantOrder::getRecentOrdersForAMemberPaginated(Auth::user()->id,$currentPage, $perPage);

		$this->response = $orders;

		return $this->response();
	}






}
