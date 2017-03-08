<?php

namespace App\Http\Controllers\ClubAdmin\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class NotificationsController extends Controller {
	public function index() {
		return view ( 'admin.notifications.notifications-list' );
	}
	public function create() {
		return view ( 'admin.notifications.create-notification' );
	}
	public function view() {
	    return view ( 'admin.notifications.view' );
    }
    public function edit() {
	    return view ( 'admin.notifications.edit' );
    }
}
