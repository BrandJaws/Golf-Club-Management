<?php
namespace App\Http\Controllers\ClubAdmin\Courses;

use Illuminate\Http\Request;
use App\Http\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Description of CourseController
 *
 * @author kas
 */
class CoursesController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('course', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $courses = (new Course())->listClubCoursesPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        
        if ($request->ajax()) {
            return $courses;
        } else {
            if ($courses->count() > 0) {
                $courses = json_encode($courses);
            }
            return view('admin.courses.courses-list', compact('courses'));
        }
    }

    public function create()
    {
        if (Auth()->user()->canNot('course', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        return view('admin.courses.create');
    }

    public function edit(Request $request, $course_id)
    {
        if (Auth()->user()->canNot('course', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        try {
            $course = Course::findOrFail($course_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {
            return Redirect::route('admin.courses.index')->with([
                'error' => \trans('message.not_found')
            ]);
        } catch (\Exception $exp) {
            return Redirect::back()->with([
                'error' => $exp->getMessage()
            ]);
        }
        if (empty($request->old())) {
            $course = $course->toArray();
        } else {
            $course = $request->old();
        }
        
        return view('admin.courses.edit', compact('course'));
    }

    public function store(Request $request)
    {
        if (Auth()->user()->canNot('course', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1,max:50',
            'openTime' => 'required|numeric',
            'closeTime' => 'required|numeric',
            'bookingDuration' => 'required|numeric',
            'bookingInterval' => 'required|numeric',
            'numberOfHoles' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $data = $request->only([
                'name',
                'openTime',
                'closeTime',
                'bookingDuration',
                'bookingInterval',
                'numberOfHoles'
            ]);
            $course = new Course();
            $course->status = ($request->has('status')) ? config('global.status.open') : config('global.status.closed');
            $course->club_id = \Auth::user()->club_id;
            $course->fill($data)->save();
            return \Redirect::route('admin.courses.index')->with([
                'success' => \trans('message.course_created_success')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function update(Request $request, $courseId)
    {
        if (Auth()->user()->canNot('course', 'App\Model')) {
            return Redirect::route('admin.dashboard')->with([
                'error' => \trans('message.unauthorized_access')
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1,max:50',
            'openTime' => 'required|numeric',
            'closeTime' => 'required|numeric',
            'bookingDuration' => 'required|numeric',
            'bookingInterval' => 'required|numeric',
            'numberOfHoles' => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $data = $request->only([
                'name',
                'openTime',
                'closeTime',
                'bookingDuration',
                'bookingInterval',
                'numberOfHoles'
            ]);
            $course = Course::findOrFail($memberId);
            $course->status = ($request->has('status')) ? config('global.status.open') : config('global.status.closed');
            $course->fill($data)->update();
            return \Redirect::route('admin.courses.index')->with([
                'success' => \trans('message.course_update_success')
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

    public function destroy($course_id)
    {
        try {
            Course::find($memberId)->delete();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }
}   
