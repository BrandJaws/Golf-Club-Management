<?php

namespace App\Http\Controllers\ClubAdmin\Segments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class SegmentsController extends Controller {
	public function index() {
		return view ( 'admin.segments.segments' );
	}
	public function create() {
		return view ( 'admin.segments.create' );
	}
}
