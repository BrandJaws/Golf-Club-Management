<?php

namespace App\Http\Controllers\ClubAdmin\Shop;

use App\Http\Models\ShopCategory;
use App\Http\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller {
	public function index(Request $request) {

		$categories = ShopCategory::where('club_id',Auth::user()->club_id)->orderBy('name')->get();
		$currentPage = 1;
		$perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');


		if($categories->count() > 0){
			$categories[0]->products = $categories[0]->products()->paginate($perPage, array(
				'id','category_id','name','image','in_stock', 'visible'
			), 'current_page', $currentPage);
		}

		return view ( 'admin.shop.shop', ["categories"=>json_encode($categories)] );
	}

	public function getProductsByCategoryIdPaginated(Request $request) {

		$category_id =  $request->get('category_id');
		$currentPage = $request->has('current_page') && is_numeric($request->get('current_page'))? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') && is_numeric($request->get('per_page')) ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$search = $request->has('search') ? $request->get('search') : false;
 		$products = ShopCategory::getProductsByCategoryIdPaginated($category_id,$currentPage, $perPage, $search);
		
		$this->response = $products;

		return $this->response();

	}


	public function createNewCategory(Request $request){
//		$validator = Validator::make($request->all(), [
//
//			'name' => 'required|min:1,max:50',
//
//		]);



//		if ($validator->fails()) {
//			return response($validator->errors(),412);
//		}
		if(!$request->has('name')){
			$this->error = "category_name_missing";
			return $this->response();
		}

		try {
			$shopCategory = new ShopCategory();
			$data = $request->all();
			$data["club_id"] = Auth::user()->id;
			$shopCategory->fill($data)->save();

			$this->response = $shopCategory;
		} catch (\Exception $exp) {
			$this->error = $exp->getMessage();

		}

		return $this->response();
	}

	public function updateCategory(Request $request){

		if(!$request->has('category_id')){
			$this->error = "category_id_missing";
			return $this->response();
		}

		if(!$request->has('name')){
			$this->error = "category_name_missing";
			return $this->response();
		}

		try {
			$shopCategory = ShopCategory::find($request->get('category_id'));
			if(!$shopCategory || $shopCategory->club_id != Auth::user()->club_id){
				$this->error = "category_id_missing";
				return $this->response();
			}

			$shopCategory->name =  $request->get('name');
			$shopCategory->save();

			$this->response = $shopCategory;
		} catch (\Exception $exp) {
			$this->error = $exp->getMessage();

		}

		return $this->response();
	}

	public function deleteCategory($category_id){

		$category = ShopCategory::where("id",$category_id)->with('products')->first();

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
		$categories = ShopCategory::where("club_id",Auth::user()->club_id)->get();

		return view ( 'admin.shop.create_product',["categories"=>$categories, "selectedCategory"=>$categoryId] );
	}

	public function saveNewProduct(Request $request){

		$validator = Validator::make($request->all(), [
			'category_id' => 'exists:shop_categories,id',
			'name' => 'required|min:1,max:50',
			'description' => 'required|min:1,max:250',
			'image' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',

		]);

		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}
		try {
			$product = new ShopProduct();

			$image = $request->file('image');
			$fileName = time() . '.' . $image->getClientOriginalExtension();
			$image->move('uploads/shop/', $fileName);
			$data = $request->all();
			$data["image"] = 'uploads/shop/' . $fileName;
			$data["club_id"] = Auth::user()->club_id;
			$product->fill($data)->save();

			return \Redirect::route('admin.shop.shop')->with([
				'success' => \trans('message.training_created_success.message')
			]);
		} catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function showEditProductForm($product_id){

		$product = ShopProduct::find($product_id);
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
		$categories = ShopCategory::where("club_id",Auth::user()->club_id)->get();
		return view ( 'admin.shop.edit_product',["categories"=>$categories , "product"=>$product] );
	}

	public function updateProduct($product_id, Request $request){

		$validator = Validator::make($request->all(), [

			'category_id' => 'exists:shop_categories,id',
			'name' => 'required|min:1,max:50',
			'description' => 'required|min:1,max:250',
			//'image' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',

		]);


		if ($validator->fails()) {
			$this->error = $validator->errors();
			return \Redirect::back()->withInput()->withErrors($this->error);
		}

		$product = ShopProduct::find($product_id);
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
				$image->move('uploads/shop/', $fileName);
				$data["image"] = 'uploads/shop/' . $fileName;
			}



			$data["club_id"] = Auth::user()->club_id;
			$product->fill($data)->save();

			return \Redirect::route('admin.shop.shop')->with([
				'success' => \trans('message.training_created_success.message')
			]);
		} catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}
	}

	public function deleteProduct($product_id){

		$product = ShopProduct::find($product_id);
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





}
