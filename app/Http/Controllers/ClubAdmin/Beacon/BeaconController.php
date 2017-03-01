<?php
namespace App\Http\Controllers\ClubAdmin\Beacon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Collection\BeaconConfiguration;
use App\Http\Models\Beacon;
use App\Http\Models\Course;

class BeaconController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('beacon', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $beacon = (new Beacon())->paginatedList(Auth::user()->club_id, $currentPage, $perPage, $search);
        
        if ($request->ajax()) {
            return $beacon;
        }
        if ($beacon->count() > 0) {
            $beacon = json_encode($beacon);
        }
        return view('admin.beacon.beacon',compact('beacon'));
    }

    public function create()
    {
        $courses = (new Course())->getList();
        dd($courses);
        return view('admin.beacon.create');
    }

    public function store(Request $request)
    {
        $beaconConfig = (new BeaconConfiguration())->boot($request->all());
    }
}
