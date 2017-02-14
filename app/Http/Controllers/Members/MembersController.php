<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Member;

class MembersController extends Controller {
	public function index(Request $request) {
		$search = $request->has ( 'search' ) ? $request->get ( 'search' ) : false;
		$currentPage = $request->has ( 'current_page' ) ? $request->get ( 'current_page' ) : 0;
		$perPage = $request->has ( 'per_page' ) ? $request->get ( 'per_page' ) : \Config::get ( 'global.portal_items_per_page' );
		$members = (new Member ())->listClubMembersPaginated ( Auth::user ()->club_id, $currentPage, $perPage, $search );
		if ($members->count () > 0) {
			$members = json_encode ( $members );
		}
                if($request->ajax()){
                    
                    return $members;
                }else{
                    return view ( 'admin.members.members-list', compact ( 'members' ) );
                }
		
	}
	public function add() {
		return view ( 'admin.members.create' );
	}
}
