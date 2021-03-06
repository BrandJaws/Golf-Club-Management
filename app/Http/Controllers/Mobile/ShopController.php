<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Models\ShopCategory;
use App\Http\Models\ShopProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller {

	public function getAllCategories(){

		$categories = ShopCategory::where('club_id',Auth::user()->club_id)->orderBy('name')->get();
		if($categories->count() >0){
			$this->response = $categories;
		}else{
			$this->error = "no_categories_found";
		}

		return $this->response();
	}

	public function getProductsByCategory($category_id,Request $request){


		$currentPage = $request->has('current_page') && is_numeric($request->get('current_page'))? $request->get('current_page') : 0;
		$perPage = $request->has('per_page') && is_numeric($request->get('per_page')) ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
		$search = $request->has('search') ? $request->get('search') : false;
		$showOnlyVisible = true;

		$newest = $request->has('newest') && strtolower($request->get('newest')) == 'true' ? true : false;
		
		$products = ShopCategory::getProductsByCategoryIdPaginated($category_id,$currentPage, $perPage, $search, $showOnlyVisible, $newest );

		$this->response = $products;

		return $this->response();

	}

	public function getProductById($product_id){



		$product = ShopProduct::where('id',$product_id)
								->where('club_id',Auth::user()->club_id)
								->where('visible','YES')
								->first();

		if(!$product){

			$this->error = "product_not_found";

		}else{

			$this->response = $product;

		}

		return $this->response();

	}






}
