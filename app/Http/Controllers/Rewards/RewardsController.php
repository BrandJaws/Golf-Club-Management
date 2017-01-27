<?php

namespace App\Http\Controllers\Rewards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class RewardsController extends Controller
{
    public function index() {
        return view('admin.rewards.rewards-list');
    }
}
