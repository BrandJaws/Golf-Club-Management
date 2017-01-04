<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ReservationsController extends Controller {
	
        public function index(){
            return view('admin.reservations.reservations');
        }
}
