<?php

namespace App\Http\Controllers\ClubAdmin\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class SocialController extends Controller {
	public function index() {
		return view ( 'admin.social.connect' );
	}
	public function create() {
		return view ( 'admin.social.create' );
	}
}
