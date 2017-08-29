<?php

namespace App\Http\Controllers\ClubAdmin\Shop;

use App\Http\Models\ShopCategory;
use App\Http\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller {
	public function index() {
		return view ( 'admin.shop.shop' );
	}

	public function createNewCategory(Request $request){
		$validator = Validator::make($request->all(), [

			'name' => 'required|min:1,max:50',

		]);

		if ($validator->fails()) {
			$this->error = $validator->errors();
			return $this->response();
		}
		try {
			$shopCategory = new ShopCategory();
			$data = $request->all();
			$data["club_id"] = Auth::user()->id;
			$shopCategory->fill($data)->save();

			$this->response = "success";
		} catch (\Exception $exp) {
			$this->error = $exp->getMessage();

		}

		return $this->response();
	}

	public function showNewProductForm(){
		$categories = ShopCategory::where("club_id",Auth::user()->club_id)->get();
		return view ( 'admin.shop.create_product',["categories"=>$categories] );
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
			$image->move('uploads/training/', $fileName);
			$data = $request->all();
			$data["image"] = 'uploads/training/' . $fileName;
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
			'image' => 'required|image|image|mimes:jpeg,bmp,png,jpg|max:1024',

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


			$image = $request->file('image');
			$fileName = time() . '.' . $image->getClientOriginalExtension();
			$image->move('uploads/training/', $fileName);
			$data = $request->all();
			$data["image"] = 'uploads/training/' . $fileName;
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
