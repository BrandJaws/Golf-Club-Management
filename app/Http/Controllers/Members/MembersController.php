<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class MembersController extends Controller {
	
        public function index(){
            return view('admin.members.members-list');
        }
}
