<?php

namespace App\Http\Controllers\ClubAdmin\Restaurant;

use App\Collection\AdminNotificationEventsManager;
use App\Http\Models\EntityBasedNotification;
use App\Http\Models\RestaurantMainCategory;
use App\Http\Models\RestaurantOrder;
use App\Http\Models\RestaurantSubCategory;
use App\Http\Models\RestaurantProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller {
	public function index(Request $request) {

		$mainCategories = RestaurantMainCategory::where('club_id',Auth::user()->club_id)
												->with([
														'sub_categories'=>function($query){
															$query->orderBy('name');
														}
														])
												->orderBy('name')
												->get();
		$categories = RestaurantSubCategory::where('club_id',Auth::user()->club_id)->orderBy('name')->get();
		$currentPage = 1;
		$perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');


		if($categories->count() > 0){

			$categories[0]->products = RestaurantSubCategory::getProductsByCategoryIdPaginated($categories[0]->id,$currentPage, $perPage, '');

		}
		if($mainCategories->count() > 0){
			if($mainCategories[0]->sub_categories->count() > 0){
				$mainCategories[0]->sub_categories[0]->products = RestaurantSubCategory::getProductsByCategoryIdPaginated($mainCategories[0]->sub_categories[0]->id,$currentPage, $perPage, '');
			}


		}
		return view ( 'admin.restaurant.restaurant', ["mainCategories"=>json_encode($mainCategories),"categories"=>json_encode($categories) ] );
	}

	public function getProductsByCategoryIdPaginated(Request $request) {

		$restaurant_sub_category_id =  $request->get('restaurant_sub_category_id');
		$currentPage = $request->has('current_page') && is_numeric($request->get('current_page'))? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') && is_numeric($request->get('per_page')) ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$search = $request->has('search') ? $request->get('search') : false;
 		$products = RestaurantSubCategory::getProductsByCategoryIdPaginated($restaurant_sub_category_id,$currentPage, $perPage, $search);

		$this->response = $products;

		return $this->response();

	}

	public function showNewMainCategoryForm(Request $request){


		return view ( 'admin.restaurant.create_main_category' );
	}

	public function saveNewMainCategory(Request $request){

		$validator = Validator::make($request->all(), [

			'name' => 'required|min:1,max:50',
			'icon' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',

		]);

		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}
		try {
			$mainCategory = new RestaurantMainCategory();

			$icon = $request->file('icon');
			$fileName = time() . '.' . $icon->getClientOriginalExtension();
			$icon->move('uploads/restaurant/main-categories', $fileName);
			$data = $request->all();
			$data["icon"] = 'uploads/restaurant/main-categories/' . $fileName;
			$data["club_id"] = Auth::user()->club_id;
			$mainCategory->fill($data)->save();

			return \Redirect::route('admin.restaurant.restaurant')->with([
				'success' => \trans('message.training_created_success.message')
			]);
		} catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function showEditMainCategoryForm($main_category_id){

		$mainCategory = RestaurantMainCategory::find($main_category_id);
		if(!$mainCategory){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.category_not_found.message')
			]);
		}
		if($mainCategory->club_id != Auth::user()->club_id){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.category_not_found.message')
			]);
		}

		return view ( 'admin.restaurant.edit_main_category',["main_category"=>$mainCategory] );
	}

	public function updateMainCategory($main_category_id, Request $request){

		$validator = Validator::make($request->all(), [


			'name' => 'required|min:1,max:50',

		]);


		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}

		$mainCategory = RestaurantMainCategory::find($main_category_id);
		if(!$mainCategory){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.category_not_found.message')
			]);
		}
		if($mainCategory->club_id != Auth::user()->club_id){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.category_not_found.message')
			]);
		}


		try {

			$data = $request->all();

			if($request->hasFile('icon')){
				$icon = $request->file('icon');
				$fileName = time() . '.' . $icon->getClientOriginalExtension();
				$icon->move('uploads/restaurant/main-categories', $fileName);
				$data["icon"] = 'uploads/restaurant/main-categories/' . $fileName;

			}


			$mainCategory->fill($data)->save();

			return \Redirect::route('admin.restaurant.restaurant')->with([
				'success' => \trans('message.category_creation_successful.message')
			]);
		} catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function deleteMainCategory($main_category_id){

		$mainCategory = RestaurantMainCategory::find($main_category_id);
		if(!$mainCategory){

			$this->error = "category_not_found";
			return $this->response();

		}
		if($mainCategory->club_id != Auth::user()->club_id){
			$this->error = "category_not_found";
			return $this->response();
		}

		if($mainCategory->sub_categories->count() > 0){
			$this->error = "category_has_sub_categories";
			return $this->response();
		}


		try {


			$mainCategory->delete();

			$this->response = "success";
		} catch (\Exception $exp) {

			$this->error = $exp->getMessage();

		}

		return $this->response();


	}



	public function createNewSubCategory(Request $request){

		if(!$request->has('name')){
			$this->error = "category_name_missing";
			return $this->response();
		}
		if(!$request->has('restaurant_main_category_id')){
			$this->error = "category_id_missing";
			return $this->response();
		}

		try {
			$restaurantSubCategory = new RestaurantSubCategory();
			$data = $request->all();
			$data["club_id"] = Auth::user()->id;
			$restaurantSubCategory->fill($data)->save();

			$this->response = $restaurantSubCategory;
		} catch (\Exception $exp) {
			dd($exp);
			$this->error = $exp->getMessage();

		}

		return $this->response();
	}

	public function updateSubCategory(Request $request){

		if(!$request->has('restaurant_sub_category_id')){
			$this->error = "category_id_missing";
			return $this->response();
		}

		if(!$request->has('name')){
			$this->error = "category_name_missing";
			return $this->response();
		}

		try {
			$restaurantSubCategory = RestaurantSubCategory::find($request->get('restaurant_sub_category_id'));
			if(!$restaurantSubCategory || $restaurantSubCategory->club_id != Auth::user()->club_id){
				$this->error = "category_id_missing";
				return $this->response();
			}

			$restaurantSubCategory->name =  $request->get('name');
			$restaurantSubCategory->save();

			$this->response = $restaurantSubCategory;
		} catch (\Exception $exp) {
			$this->error = $exp->getMessage();

		}

		return $this->response();
	}

	public function deleteSubCategory($restaurant_sub_category_id){

		$category = RestaurantSubCategory::where("id",$restaurant_sub_category_id)->with('products')->first();

		if(!$category){

			$this->error = "category_not_found";
			return $this->response();

		}

		if($category->club_id != Auth::user()->club_id){
			$this->error = "category_not_found";
			return $this->response();
		}

		if($category->products->count() > 0){
			$this->error = "category_has_products";
			return $this->response();
		}


		try {


			$category->delete();
			$this->response = "success";

		} catch (\Exception $exp) {

			$this->error = $exp->getMessage();

		}

		return $this->response();

	}

	public function showNewProductForm(Request $request){

		$categoryId = "";
		if($request->has('category')){
			$categoryId = $request->get('category');
		}
		$categories = RestaurantSubCategory::where("club_id",Auth::user()->club_id)->get();

		return view ( 'admin.restaurant.create_product',["categories"=>$categories, "selectedCategory"=>$categoryId] );
	}

	public function saveNewProduct(Request $request){

		$validator = Validator::make($request->all(), [
			'restaurant_sub_category_id' => 'exists:restaurant_sub_categories,id',
			'name' => 'required|min:1,max:50',
			'description' => 'required|min:1,max:250',
			'image' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',
			'price' =>'required|numeric'

		]);

		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}
		try {
			$product = new RestaurantProduct();

			$image = $request->file('image');
			$fileName = time() . '.' . $image->getClientOriginalExtension();
			$image->move('uploads/restaurant/', $fileName);
			$data = $request->all();
			$data["image"] = 'uploads/restaurant/' . $fileName;
			$data["club_id"] = Auth::user()->club_id;
			$product->fill($data)->save();

			return \Redirect::route('admin.restaurant.restaurant')->with([
				'success' => \trans('message.training_created_success.message')
			]);
		} catch (\Exception $exp) {
			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function showEditProductForm($product_id){

		$product = RestaurantProduct::find($product_id);
		if(!$product){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.product_not_found.message')
			]);
		}
		if($product->club_id != Auth::user()->club_id){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.product_not_found.message')
			]);
		}
		$categories = RestaurantSubCategory::where("club_id",Auth::user()->club_id)->get();
		return view ( 'admin.restaurant.edit_product',["categories"=>$categories , "product"=>$product] );
	}

	public function updateProduct($product_id, Request $request){

		$validator = Validator::make($request->all(), [

			'restaurant_sub_category_id' => 'exists:restaurant_sub_categories,id',
			'name' => 'required|min:1,max:50',
			'description' => 'required|min:1,max:250',
			//'image' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',
			'price' =>'required|numeric'

		]);


		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}

		$product = RestaurantProduct::find($product_id);
		if(!$product){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.product_not_found.message')
			]);
		}
		if($product->club_id != Auth::user()->club_id){
			return \Redirect::back()->withInput()->with([
				'error' => \trans('message.product_not_found.message')
			]);
		}


		try {

			$data = $request->all();
			
			if($request->hasFile('image')){
				$image = $request->file('image');
				$fileName = time() . '.' . $image->getClientOriginalExtension();
				$image->move('uploads/restaurant/', $fileName);
				$data["image"] = 'uploads/restaurant/' . $fileName;
			}



			$data["club_id"] = Auth::user()->club_id;
			$product->fill($data)->save();

			return \Redirect::route('admin.restaurant.restaurant')->with([
				'success' => \trans('message.training_created_success.message')
			]);
		} catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function deleteProduct($product_id){

		$product = RestaurantProduct::find($product_id);
		if(!$product){

			$this->error = "product_not_found";
			return $this->response();

		}
		if($product->club_id != Auth::user()->club_id){
			$this->error = "product_not_found";
			return $this->response();
		}


		try {


			$product->delete();

			$this->response = "success";
		} catch (\Exception $exp) {

			$this->error = $exp->getMessage();

		}

		return $this->response();


	}

	public function ordersList() {

		$orders = RestaurantOrder::getOpenOrdersForAClub(Auth::user()->club_id);
		$maxEntityBasedNotificationId = EntityBasedNotification::max('id');
		$entity_based_notification_id = $maxEntityBasedNotificationId ? $maxEntityBasedNotificationId : 0;

		return view ( 'admin.restaurant.orders', ["orders"=>json_encode($orders),
												  "entity_based_notification_id"=>$entity_based_notification_id]);
	}

	public function markOrderAsInProcess(Request $request){
		if (!$request->has('restaurant_order_id')) {
			$this->error = "order_id_missing";
			return $this->response();
		}
		$order = RestaurantOrder::getSingleOrderById($request->get("restaurant_order_id"));
		if(!$order){
			$this->error = "order_id_missing";
			return $this->response();
		}

		if($order->club_id != Auth::user()->club_id){
			$this->error = "order_doesnt_belong_to_requesting_body";
			return $this->response();
		}

		try{

			DB::beginTransaction();
			$order->in_process = "YES";
			$order->save();
			$this->response = $order;

			EntityBasedNotification::create([
				"club_id"=>$order->club_id,
				"event"=>AdminNotificationEventsManager::$RestaurantOrderUpdation,
				"entity_id"=>$order->id,
				"entity_type"=>RestaurantOrder::class
			]);
			AdminNotificationEventsManager::broadcastRestaurantOrderUpdationEvent($order->club_id);

			DB::commit();
		}catch (\Exception $e){

			$this->error = $e->getMessage();

		}

		return $this->response();
	}

	public function markOrderAsIsReady(Request $request){
		if (!$request->has('restaurant_order_id')) {
			$this->error = "order_id_missing";
			return $this->response();
		}
		$order = RestaurantOrder::getSingleOrderById($request->get("restaurant_order_id"));
		if(!$order){
			$this->error = "order_id_missing";
			return $this->response();
		}

		if($order->club_id != Auth::user()->club_id){
			$this->error = "order_doesnt_belong_to_requesting_body";
			return $this->response();
		}

		try{
			DB::beginTransaction();

			$order->in_process = "YES";
			$order->is_ready = "YES";
			$order->save();
			$this->response = $order;

			EntityBasedNotification::create([
				"club_id"=>$order->club_id,
				"event"=>AdminNotificationEventsManager::$RestaurantOrderUpdation,
				"entity_id"=>$order->id,
				"entity_type"=>RestaurantOrder::class
			]);
			AdminNotificationEventsManager::broadcastRestaurantOrderUpdationEvent($order->club_id);

			DB::commit();
		}catch (\Exception $e){

			$this->error = $e->getMessage();

		}

		return $this->response();
	}

	public function markOrderAsIsServed(Request $request){
		if (!$request->has('restaurant_order_id')) {
			$this->error = "order_id_missing";
			return $this->response();
		}
		$order = RestaurantOrder::getSingleOrderById($request->get("restaurant_order_id"));
		if(!$order){
			$this->error = "order_id_missing";
			return $this->response();
		}

		if($order->club_id != Auth::user()->club_id){
			$this->error = "order_doesnt_belong_to_requesting_body";
			return $this->response();
		}

		try{
			DB::beginTransaction();
			
			$order->in_process = "YES";
			$order->is_ready = "YES";
			$order->is_served = "YES";
			$order->save();
			$this->response = $order;

			EntityBasedNotification::create([
				"club_id"=>$order->club_id,
				"event"=>AdminNotificationEventsManager::$RestaurantOrderUpdation,
				"entity_id"=>$order->id,
				"entity_type"=>RestaurantOrder::class
			]);
			AdminNotificationEventsManager::broadcastRestaurantOrderUpdationEvent($order->club_id);

			DB::commit();
		}catch (\Exception $e){

			$this->error = $e->getMessage();

		}

		return $this->response();
	}

	public function orderView($order_id) {
		$order = RestaurantOrder::getSingleOrderById($order_id);
		if(!$order || $order->club_id != Auth::user()->id){
			return \Redirect::back()->with([
				'error' => trans('message.order_not_found.message')]);

		}

		$order->restaurant_order_details = $order->getRestaurantOrderDetailsCustomized();
		$maxEntityBasedNotificationId = EntityBasedNotification::max('id');
		$entity_based_notification_id = $maxEntityBasedNotificationId ? $maxEntityBasedNotificationId : 0;

		return view ( 'admin.restaurant.orders-details', ['order' => json_encode($order),
			"entity_based_notification_id"=>$entity_based_notification_id]);
	}

	public function ordersArchive(Request $request){

		if ($request->has('filters')) {
			$filters = json_decode($request->get('filters'));
		} else {

			$filters = null;
		}

		$currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$orders = RestaurantOrder::paginatedListWithFilters(Auth::user()->club_id, $perPage, $currentPage, $filters);

		$orders = json_decode($orders->toJson(), FALSE);
		$orders->filters = $filters;
		
		if ($request->ajax()) {
			return json_encode($orders);
		} else {
			return view ( 'admin.restaurant.orders-archive', ["ordersWithFilters"=>json_encode($orders)]);
		}


	}


}
