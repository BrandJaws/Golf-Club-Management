<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function index() {
        return view('admin.staff.staff');
    }

    public function create() {
        return view('admin.staff.create');
    }
}
