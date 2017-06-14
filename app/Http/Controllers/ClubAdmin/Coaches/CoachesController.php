<?php
namespace App\Http\Controllers\ClubAdmin\Coaches;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Coach;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CoachesController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('coach', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $coaches = (new Coach())->listClubCoachesPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        if ($request->ajax()) {
            return $coaches;
        } else {
            if ($coaches->count() > 0) {
                $coaches = json_encode($coaches);
            }
            return view('admin.coaches.coaches', compact('coaches'));
        }
    }

    public function create()
    {
        if (Auth()->user()->canNot('coach', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        return view('admin.coaches.create');
    }

    public function edit(Request $request, $coachId)
    {
        if (Auth()->user()->canNot('coach', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        try {
            $coach = Coach::findOrFail($coachId);
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
            $coach = $coach->toArray();
        } else {
            $coach = $request->old();
            $coach['id'] = $coachId;
        }
        return view('admin.coaches.edit', compact('coach'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:1,max:40',
            'lastName' => 'required|min:1,max:40',
            'email' => 'required|email',
            'phone' => 'numeric',
            'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,jpg|max:1024'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $coach = new Coach();
            $data = $request->only([
                'firstName',
                'lastName',
                'email',
                'phone',
                'specialities'
            ]);
            $data['club_id'] = \Auth::user()->club_id;
            if ($request->hasFile('profilePic')) {
                $image = $request->file('profilePic');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/coach/', $fileName);
                $coach->profilePic = 'uploads/coach/' . $fileName;
            }
            $coach->fill($data)->save();
            return \Redirect::route('admin.coaches.coaches')->with([
                'success' => \trans('message.coach_created_successfully.message')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function update(Request $request, $coachId)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:1,max:40',
            'lastName' => 'required|min:1,max:40',
            'email' => 'required|email',
            'phone' => 'numeric',
            'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,jpg|max:1024'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $coach = Coach::findOrFail($coachId);
            $data = $request->only([
                'firstName',
                'lastName',
                'email',
                'phone',
                'specialities'
            ]);
            if ($request->hasFile('profilePic')) {
                $image = $request->file('profilePic');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move('uploads/coach/', $fileName);
                if (! is_null($coach->profilePic) && $coach->profilePic != '' && file_exists($coach->profilePic)) {
                    @unlink($coach->profilePic);
                }
                $coach->profilePic = 'uploads/coach/' . $fileName;
            }
            $coach->fill($data)->update();
            return \Redirect::route('admin.coaches.coaches')->with([
                'success' => \trans('message.coach_updated_successfully.message')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function destroy($coachId)
    {
        try {
            Coach::find($coachId)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }

    public function searchListCoaches(Request $requst)
    {
        $search = $requst->has('search') ? $requst->get('search') : '';
        try {
            $clubCoaches = (new Coach())->listClubCoaches(Auth::user()->club_id, $search);
            if ($clubCoaches && count($clubCoaches)) {
                // $this->response = $clubMember;
                return $clubCoaches;
            } else {
                // $this->error = 'no_members_could_be_found';
                return [];
            }
        } catch (\Exception $e) {
            // $this->error = "exception";
            \Log::error(__METHOD__, [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
        // return $this->response ();
    }
}
