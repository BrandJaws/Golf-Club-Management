<?php
namespace App\Http\Controllers\ClubAdmin\Courses;

use App\Http\Models\CourseHole;
use Illuminate\Http\Request;
use App\Http\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

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

            $course = Course::where("id",$course_id)->with('holes')->first();

            if(!$course){
                return Redirect::back()->with([
                  'error' => \trans('message.not_found')
                ]);
            }


            foreach($course->holes as $hole){
                $hole->tee_values = json_decode($hole->tee_values);
            }

        } catch (\Exception $exp) {

            return Redirect::back()->with([
                'error' => $exp->getMessage()
            ]);
        }
        if (empty($request->old())) {
            $course = $course->toArray();
        } else {
            $course = $request->old();
            $course['id'] = $course_id;
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

        $foundOneOrMoreErrors = false;
        $error = [];
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1,max:50',
            'openTime' => 'required|date_format:H:i',
            'closeTime' => 'required|date_format:H:i',
            'bookingDuration' => 'required|numeric',
            'bookingInterval' => 'required|numeric',
            'numberOfHoles' => 'required|numeric',
            'teesDataJson'=> 'required',
            'holesDataJson'=> 'required',
        ]);

        if ($validator->fails()) {

            $error = json_decode(json_encode($validator->errors()),true);
            $foundOneOrMoreErrors = true;

        }


        $teesData = json_decode($request->get('teesDataJson'), true);
        if(!$teesData || !is_array($teesData) || !count($teesData)){

            Course::ensureErrorsPropertyOnData($error,"teesDataJson");
            $error['teesDataJson'] = "Must Select Atleast One Tee";
            $foundOneOrMoreErrors = true;

        }else{
            if(!Course::validateTeesData($teesData)){
                $foundOneOrMoreErrors = true;

            }
        }



        $holesData = json_decode($request->get('holesDataJson'), true);
        if(!$holesData || !is_array($holesData) || !count($holesData)){


            Course::ensureErrorsPropertyOnData($error,"holesDataJson");
            $error["holesDataJson"] = "Must Select Atleast One Hole";
            $foundOneOrMoreErrors = true;
        }else{
            $colorsSent = [];
            foreach($teesData as $tee){
                $colorsSent[] = $tee['color'];
            }

            if(!CourseHole::validateDataListAgainstModel($holesData,$colorsSent)){
                $foundOneOrMoreErrors = true;

            }
        }




        if($foundOneOrMoreErrors){

            $request->merge(["holesDataJson" => json_encode($holesData)]);
            $request->merge(["teesDataJson" => json_encode($teesData)]);
            return \Redirect::back()->withInput()->withErrors($error);
        }

        try {
            DB::beginTransaction();
            $data = $request->only([
                'name',
                'openTime',
                'closeTime',
                'bookingDuration',
                'bookingInterval',
                'numberOfHoles',
            ]);

            $data['tees'] = $request->get('teesDataJson');
            $data['status'] = ($request->has('status')) ? config('global.status.open') : config('global.status.closed');
            $data['club_id'] = \Auth::user()->club_id;

            $course = new Course();
            $course->fill($data)->save();

            foreach($holesData as $hole){

                $courseHole = new CourseHole();
                $courseHole->course_id = $course->id;
                $courseHole->hole_number = $hole['hole_number'];
                $courseHole->mens_handicap = $hole['mens_handicap'];
                $courseHole->mens_par = $hole['mens_par'];
                $courseHole->womens_handicap = $hole['womens_handicap'];
                $courseHole->womens_par = $hole['womens_par'];
                $courseHole->tee_values = json_encode($hole['tee_values']);
                $courseHole->save();

            }

            
            DB::commit();
            
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

        $foundOneOrMoreErrors = false;
        $error = [];
        $validator = Validator::make($request->all(), [
          'name' => 'required|min:1,max:50',
          'openTime' => 'required|date_format:H:i',
          'closeTime' => 'required|date_format:H:i',
          'bookingDuration' => 'required|numeric',
          'bookingInterval' => 'required|numeric',
          'numberOfHoles' => 'required|numeric',
          'teesDataJson'=> 'required',
          'holesDataJson'=> 'required',
        ]);

        if ($validator->fails()) {

            $error = json_decode(json_encode($validator->errors()),true);
            $foundOneOrMoreErrors = true;

        }


        $teesData = json_decode($request->get('teesDataJson'), true);
        if(!$teesData || !is_array($teesData) || !count($teesData)){

            Course::ensureErrorsPropertyOnData($error,"teesDataJson");
            $error['teesDataJson'] = "Must Select Atleast One Tee";
            $foundOneOrMoreErrors = true;

        }else{
            if(!Course::validateTeesData($teesData)){
                $foundOneOrMoreErrors = true;

            }
        }



        $holesData = json_decode($request->get('holesDataJson'), true);
        if(!$holesData || !is_array($holesData) || !count($holesData)){


            Course::ensureErrorsPropertyOnData($error,"holesDataJson");
            $error["holesDataJson"] = "Must Select Atleast One Hole";
            $foundOneOrMoreErrors = true;
        }else{
            $colorsSent = [];
            foreach($teesData as $tee){
                $colorsSent[] = $tee['color'];
            }

            if(!CourseHole::validateDataListAgainstModel($holesData,$colorsSent)){
                $foundOneOrMoreErrors = true;

            }
        }




        if($foundOneOrMoreErrors){

            $request->merge(["holesDataJson" => json_encode($holesData)]);
            $request->merge(["teesDataJson" => json_encode($teesData)]);
            return \Redirect::back()->withInput()->withErrors($error);
        }

        $course = Course::where("id",$courseId)->with('holes')->first();
        if(!$course){
            return Redirect::back()->with([
              'error' => \trans('message.not_found')
            ]);
        }

        try {
            DB::beginTransaction();
            $data = $request->only([
              'name',
              'openTime',
              'closeTime',
              'bookingDuration',
              'bookingInterval',
              'numberOfHoles',
            ]);

            $data['tees'] = $request->get('teesDataJson');
            $data['status'] = ($request->has('status')) ? config('global.status.open') : config('global.status.closed');


            $course->fill($data)->save();

            foreach($course->holes as $hole){
                $hole->delete();
            }

            foreach($holesData as $hole){

                $courseHole = new CourseHole();
                $courseHole->course_id = $course->id;
                $courseHole->hole_number = $hole['hole_number'];
                $courseHole->mens_handicap = $hole['mens_handicap'];
                $courseHole->mens_par = $hole['mens_par'];
                $courseHole->womens_handicap = $hole['womens_handicap'];
                $courseHole->womens_par = $hole['womens_par'];
                $courseHole->tee_values = json_encode($hole['tee_values']);
                $courseHole->save();

            }

            DB::commit();

            return \Redirect::route('admin.courses.index')->with([
                'success' => \trans('message.course_update_success')
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
            DB::beginTransaction();
            $course = Course::where("id",$course_id)->with('holes')->first();
            foreach($course->holes as $hole){
                $hole->delete();
            }

            $course->delete();

            DB::commit();
            return "success";
        } catch (\Exception $e) {
            return $e->getMessage();
            return "failure";
        }
    }
}   
