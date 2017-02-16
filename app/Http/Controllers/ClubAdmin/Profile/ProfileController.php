<?php

namespace App\Http\Controllers\ClubAdmin\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ProfileController extends Controller {
	public function index() {
		return view ( 'admin.profile.profile' );
	}
	public function edit() {
		return view ( 'admin.profile.edit' );
	}
}
