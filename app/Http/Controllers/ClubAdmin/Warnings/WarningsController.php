<?php

namespace App\Http\Controllers\ClubAdmin\Warnings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WarningsController extends Controller
{
    public function index() {
        return view('admin.warnings.warnings');
    }

    public function create() {
        return view('admin.warnings.create');
    }
}
