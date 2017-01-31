<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class NotificationsController extends Controller
{
    public function index() {
        return view('admin.notifications.notifications-list');
    }

    public function create() {
        return view ('admin.notifications.create-notification');
    }
}
