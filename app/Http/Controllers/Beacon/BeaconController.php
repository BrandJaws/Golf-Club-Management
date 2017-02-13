<?php

namespace App\Http\Controllers\Beacon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BeaconController extends Controller
{
    public function index() {
        return view('admin.beacon.beacon');
    }
}
