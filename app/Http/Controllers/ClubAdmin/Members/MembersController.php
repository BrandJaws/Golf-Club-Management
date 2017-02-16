<?php
namespace App\Http\Controllers\ClubAdmin\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Member;

class MembersController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $members = (new Member())->listClubMembersPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        if ($members->count() > 0) {
            $members = json_encode($members);
        }
        if ($request->ajax()) {
            
            return $members;
        } else {
            return view('admin.members.members-list', compact('members'));
        }
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function edit($memberId)
    {
        $member = Member::findOrFail($memberId);
        return view('admin.members.edit', compact('member'));
    }

    public function store()
    {
        
    }

    public function update(Request $request, $memberId)
    {
        dd($request->all(), $memberId);
    }

    public function destroy($memberId)
    {}
    
    public function searchListMembers(Request $requst) {
		$search = $requst->has ( 'search' ) ? $requst->get ( 'search' ) : '';
		try {
			$clubMember = (new Member ())->listClubMembers ( Auth::user ()->club_id, $search );
			if ($clubMember && count ( $clubMember )) {
				//$this->response = $clubMember;
                                return $clubMember;
			} else {
				//$this->error = 'no_members_could_be_found';
                                return [];
			}
		} catch ( \Exception $e ) {
			//$this->error = "exception";
			\Log::error ( __METHOD__, [ 
					'error' => $e->getMessage (),
					'line' => $e->getLine () 
			] );
		}
		//return $this->response ();
	}
}
