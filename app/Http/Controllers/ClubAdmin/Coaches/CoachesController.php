<?php

namespace App\Http\Controllers\ClubAdmin\Coaches;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoachesController extends Controller {
	public function index() {
		return view ( 'admin.coaches.coaches' );
	}
	public function create() {
		return view ( 'admin.coaches.create' );
	}
}
