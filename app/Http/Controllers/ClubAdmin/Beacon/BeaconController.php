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
use PhpParser\Unserializer;

class BeaconController extends Controller
{

    protected $rules = array(
        'name' => 'required|max:50',
        'UUID' => 'required|max:250',
        'major' => 'required|digits_between:1,10',
        'minor' => 'required|digits_between:1,10',
        'Immediate.message' => 'required_if:Immediate.action,custom',
        'Far.message' => 'required_if:Far.action,custom',
        'Near.message' => 'required_if:Near.action,custom'
    );

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
        return view('admin.beacon.beacon', compact('beacon'));
    }

    public function create()
    {
        if (Auth()->user()->canNot('beacon', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $courses = Course::where('club_id', '=', Auth::user()->club_id)->pluck('name', 'id')->toArray();
        
        return view('admin.beacon.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $beaconConfig = (new BeaconConfiguration())->boot($request->all());
            $beacon = new Beacon();
            $beacon->configuration = serialize($beaconConfig);
            $beacon->club_id = Auth::user()->club_id;
            $beacon->course_id = $request->get('course');
            $beacon->fill($request->all())
                ->save();
            return \Redirect::route('admin.member.index')->with([
                'success' => \trans('message.beacon_created_successfully.message')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $beacon_id)
    {
       $beacon = Beacon::find($beacon_id);
       if(!$beacon instanceof Beacon){
           return \Redirect::route('beacon.index')->with([
               'error' => 'Invalid beacon type'
           ]);
       }
       if(!$beacon->club_id != Auth::user()->club_id){
           return \Redirect::route('beacon.index')->with([
               'error' => 'You are not authorized to edit this beacon'
           ]);
       }
       $configuration = unserialize($beacon->configuration);
     
    }
}
