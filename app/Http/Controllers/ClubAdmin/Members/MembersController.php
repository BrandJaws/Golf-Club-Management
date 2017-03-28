<?php
namespace App\Http\Controllers\ClubAdmin\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Member;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class MembersController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('member', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $members = (new Member())->listClubMembersPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        
        if ($request->ajax()) {
            
            return $members;
        } else {
            if ($members->count() > 0) {
                $members = json_encode($members);
            }
            return view('admin.members.members-list', compact('members'));
        }
    }

    public function create()
    {
        if (Auth()->user()->canNot('member', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        return view('admin.members.create');
    }

    public function edit(Request $request, $memberId)
    {
        if (Auth()->user()->canNot('member', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        try {
            $member = Member::findOrFail($memberId);

            if($member->main_member){
                $member->main_member->setHidden(['club_id','email','phone','profilePic','password','gender','dob','device_registeration_id','device_type','main_member_id','status','auth_token','created_at','updated_at','deleted_at']);

            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {
            return Redirect::back()->with([
                'error' => \trans('message.not_found')
            ]);
        } catch (\Exception $exp) {
            return Redirect::back()->with([
                'error' => $exp->getMessage()
            ]);
        }
        if (empty($request->old())) {
            $member = $member->toArray();
        } else {
            $member_parent_id = $member->main_member_id;
            $member = $request->old();
            $member['id'] = $memberId;
            $member['main_member_id'] = $member_parent_id;
        }
        
        return view('admin.members.edit', compact('member'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:1,max:40',
            'lastName' => 'required|min:1,max:40',
            'email' => 'required|email',
            'phone' => 'numeric',
            'password' => 'required|min:4,max:15',
            'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,jpg|max:1024',
            'parentMember' => 'required_if:relation,affiliate'
        ]);
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $member = new Member();
            $data = $request->only([
                'firstName',
                'lastName',
                'email',
                'phone',
                'gender'
            ]);
            $data['club_id'] = \Auth::user()->club_id;
            if ($request->has('password')) {
                $member->password = bcrypt($request->get('password'));
            }
            if ($request->hasFile('profilePic')) {
                $image = $request->file('profilePic');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/member/', $fileName);
                if (! is_null($member->profilePic) && $member->profilePic != '' && file_exists($member->profilePic)) {
                    @unlink($member->profilePic);
                }
                $member->profilePic = 'uploads/member/' . $fileName;
            }
            if ($request->has('relation') && $request->get('relation') == 'affiliate') {
                $member->main_member_id = $request->get('parentMember');
            } else {
                $member->main_member_id = 0;
            }
            $member->fill($data)->save();
            
            return \Redirect::route('admin.member.index')->with([
                'success' => \trans('message.member_created_success')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function update(Request $request, $memberId)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:1,max:40',
            'lastName' => 'required|min:1,max:40',
            'email' => 'required|email',
            'phone' => 'numeric',
            'password' => 'min:4,max:15',
            'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,jpg|max:1024',
            'parentMember' => 'required_if:relation,affiliate'
        ]);

        $member = Member::findOrFail($memberId);
//        if($member->main_member){
//            $member->main_member->setHidden(['club_id','email','phone','profilePic','password','gender','dob','device_registeration_id','device_type','main_member_id','status','auth_token','created_at','updated_at','deleted_at']);
//
//        }
        $member->main_member()->select("id","firstName","lastName")->get();
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->with('member',$member)->withErrors($this->error);
        }

        try {
            $data = $request->except([
                'profilePic'
            ]);

            if ($request->has('password')) {
                $member->password = bcrypt($request->get('password'));
            }
            if ($request->hasFile('profilePic')) {
                $image = $request->file('profilePic');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/member/', $fileName);
                if (! is_null($member->profilePic) && $member->profilePic != '' && file_exists($member->profilePic)) {
                    @unlink($member->profilePic);
                }
                $member->profilePic = 'uploads/member/' . $fileName;
            }
            if ($request->has('relation') && $request->get('relation') == 'affiliate') {
                $member->main_member_id = $request->get('parentMember');

            } else {
                $member->main_member_id = 0;
            }
            $member->fill($data)->update();
            return \Redirect::route('admin.member.index')->with([
                'success' => \trans('message.member_update_success')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {

            return Redirect::back()->with([
                'error' => \trans('message.not_found')
            ]);
        } catch (\Exception $exp) {

            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function destroy($memberId)
    {
        try {
            Member::find($memberId)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }

    public function searchListMembers(Request $requst)
    {
        $search = $requst->has('search') ? $requst->get('search') : '';
        try {
            $clubMember = (new Member())->listSearchClubMembers(Auth::user()->club_id, $search);
            if ($clubMember && count($clubMember)) {
                return $clubMember;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            \Log::error(__METHOD__, [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
    }

   


}
