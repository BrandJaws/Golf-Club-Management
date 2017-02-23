<?php

namespace App\Http\Controllers\ClubAdmin\Trainings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrainingsController extends Controller
{
    public function index() {
        return view('admin.trainings.trainings-list');
    }

    public function create() {
        return view('admin.trainings.create');
    }
}
