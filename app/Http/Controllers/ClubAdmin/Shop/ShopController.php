<?php

namespace App\Http\Controllers\ClubAdmin\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller {
	public function index() {
		return view ( 'admin.shop.shop' );
	}
}