<?php

namespace App\Http\Controllers\ClubAdmin\Courses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Club;
use App\Http\Models\Course;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Validator;
use App\Http\Models\TennisReservation;
use App\Http\Models\TennisReservationPlayer;
use App\Http\Models\Member;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseController
 *
 * @author kas
 */
class CoursesController extends Controller {
	//use \Notification;
	private $timeSlots = [ ];
	public function index() {
		$this->response = Course::courseList ();
        return view('admin.courses.courses-list');
		return $this->response ();
	}
	public function store(Request $request) {
		if (! $request->has ( 'name' )) {
			$this->error = 'email_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'openTime' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'closeTime' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'bookingDuration' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'status' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		try {
			\DB::beginTransaction ();
			$course = new Course ();
			$data = $request->all ();
			$data ['club_id'] = Auth::user ()->club_id;
			$course->populate ( $data )->save ();
			\DB::commit ();
			$this->response = "course_registered_successfully";
		} catch ( \Exception $e ) {
			\DB::rollback ();
			$this->error = "exception";
		}
		
		return $this->response ();
	}
	public function update(Request $request, $courseId) {
		if (! $request->has ( 'name' )) {
			$this->error = 'email_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'openTime' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'closeTime' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'bookingDuration' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'status' )) {
			$this->error = 'password_not_present';
			return $this->response ();
		}
		try {
			\DB::beginTransaction ();
			$data = $request->all ();
			$course = Course::getById ( $courseId );
			$course->populate ( $data )->save ();
			\DB::commit ();
			$this->response = "course_updated_successfully";
		} catch ( \Exception $e ) {
			\Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
			\DB::rollback ();
			$this->error = "exception";
		}
		return $this->response ();
	}
	public function show($courseId) {
		$this->response = Course::getById ( $courseId );
		return $this->response ();
	}
	
	/**
	 * Get list of course in a club
	 * for authorized users.
	 *
	 * @return Json Object
	 */
	public function getReservations(Request $request) {
		$courses = Course::getReservationsForACourseByIdForADateRange ();
		
		if ($courses) {
			
			$this->response = $courses;
		} else {
			$this->error = "course_not_available";
		}
		
		return $this->response ();
	}
	public function getReservationsByDate(Request $request, $date) {
		try {
			$date = Carbon::parse ( $date )->toDateString ();
		} catch ( \Exception $e ) {
			$this->error = "invalid_date_format";
			return $this->response ();
		}
		// dd($date);
		$courses = Course::getReservationsForACourseByIdForADateRange( $date );
		
		if ($courses) {
			
			$this->response = $courses;
		} else {
			$this->error = "course_not_available";
		}
		
		return $this->response ();
	}
        
        
       
	public function getStats() {
		try {
			$totalBookAbleHours = 0;
			$totalCourses = 0;
			$courses = Club::find ( Auth::user ()->club_id )->course;
			if ($courses) {
				$totalCourses = $courses->count ();
				foreach ( $courses as $key => $course ) {
					$totalBookAbleHours += $course->timeSlots ()->countTotalHours ();
				}
			}
			$members = Member::countClubMembers ( Auth::user ()->club_id );
			$totalHoursBooked = (new TennisReservation ())->getBookedHours ( Auth::user ()->club_id );
			$totalUnbookedHours = $totalBookAbleHours - $totalHoursBooked;
			$this->response = [ 
					'courses' => $totalCourses,
					'members' => $members,
					'totalHoursBooked' => $totalHoursBooked,
					'totalUnbookedHours' => $totalUnbookedHours 
			];
		} catch ( \Exception $e ) {
			\DB::rollback ();
			\Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
			$this->error = "exception";
		}
		return $this->response ();
	}
        
       
}
