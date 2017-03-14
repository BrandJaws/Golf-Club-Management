<?php
namespace App\Http\Controllers\ClubAdmin\Coaches;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Coach;
use Illuminate\Support\Facades\Auth;

class CoachesController extends Controller
{

    public function index(Request $request)
    {
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
        return view('admin.coaches.create');
    }

    public function edit($coachId)
    {
        $coach = Coach::findOrFail($coachId);
        return view('admin.coaches.edit', compact('coach'));
    }

    public function store()
    {}

    public function update(Request $request, $coachId)
    {
        dd($request->all(), $coachId);
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
