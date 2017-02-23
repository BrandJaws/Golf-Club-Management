<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Auth;
use App\Http\Models\Club;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;
//use \App\CustomModels\CoursesAndReservations\CourseForReservations;
//use \App\CustomModels\CoursesAndReservations\ReservationWrapper;
//use \App\CustomModels\CoursesAndReservations\ReservationInfo;
//use \App\CustomModels\CoursesAndReservations\ReservationPlayer;
use DB;

class Course extends Model {
	protected $table = 'course';
	public $timestamps = false;
	protected $fillable = [ 
			'name',
			'club_id',
			'openTime',
			'closeTime',
			'bookingDuration',
			'ballMachineAvailable',
			'environment',
			'status' 
	];
	public $reservations;
	public function populate($data = []) {
		if (array_key_exists ( 'name', $data )) {
			$this->name = $data ['name'];
		}
		if (array_key_exists ( 'club_id', $data )) {
			$this->club_id = $data ['club_id'];
		}
		if (array_key_exists ( 'openTime', $data )) {
			$this->openTime = $data ['openTime'];
		}
		if (array_key_exists ( 'closeTime', $data )) {
			$this->closeTime = $data ['closeTime'];
		}
		if (array_key_exists ( 'bookingDuration', $data )) {
			$this->bookingDuration = $data ['bookingDuration'];
		}
		if (array_key_exists ( 'status', $data )) {
			$this->status = $data ['status'];
		}
		if (array_key_exists ( 'ballMachineAvailable', $data )) {
			$this->ballMachineAvailable = $data ['ballMachineAvailable'];
		}
		if (array_key_exists ( 'environment', $data )) {
			$this->environment = $data ['environment'];
		}
		return $this;
	}
	public function club() {
		return $this->belongsTo ( '\App\Http\Models\Club', 'club_id' );
	}
	public static function getById($id) {
		return self::find ( $id );
	}
	public static function courseList() {
		return Club::find ( Auth::user ()->club_id )->course;
	}
	public static function courseListByClubId($club_id, $count=false) {
		$course =  self::where ( 'club_id', '=', $club_id );
		if($count)
			return $course->count();
		return $course->get();
	}
	public function timeSlots() {
		$open_time = Carbon::parse ( $this->openTime );
		$close_time = Carbon::parse ( $this->closeTime );
		$timeSlots = [ ];
		// $that = $this;
		$open_time->diffFiltered ( CarbonInterval::minute ( $this->bookingDuration ), function (Carbon $date) use (&$timeSlots) {
			$timeSlots [] = $date->format ( 'h:i A' );
		}, $close_time );
		$this->reservations = $timeSlots;
		return $this;
	}
	
	/**
	 * 
	 */
	
    
    /**
	 * To get reservations for all courses with players and other details: detailed = true
         * To get reservations for all courses with booking indicators for mobile service: detailed = false
	 */
     public static function getReservationsForAllCourses($date = false, $detailed = true) {
                $date = !$date ? Carbon::today()->toDateString() : $date;
		$coursesListRaw = Course::courseList ();
                $allCoursesWithReservations = Course::getAllReservationsWithCoursesForASpecificDate($date);
		$coursesForReservations = [ ];
		foreach ( $coursesListRaw as $course ) {
                    $foundCourseWithExistingReservations = false;
                    foreach($allCoursesWithReservations as $courseWithReservation){
                        if($course->id == $courseWithReservation->course_id){
                            $foundCourseWithExistingReservations = true;
                            if($detailed){
                                $coursesForReservations [] = Course::returnCourseWithCompleteReservationsObject ( $course, Course::generateReservationsTimeSlotsComplete ( $course, $courseWithReservation->timeSlots ) );
                            }else{
                                $coursesForReservations [] = Course::returnCourseWithCompleteReservationsObject ( $course, Course::generateReservationsTimeSlotsComplete ( $course, $courseWithReservation->timeSlots,false ) );
                            }
                           
                           break;
                        }
                    }
                    
                    if(!$foundCourseWithExistingReservations){
                        if($detailed){
                                $coursesForReservations [] = Course::returnCourseWithCompleteReservationsObject ( $course, Course::generateReservationsTimeSlotsComplete ( $course ) );
                            }else{
                                $coursesForReservations [] = Course::returnCourseWithCompleteReservationsObject ( $course, Course::generateReservationsTimeSlotsComplete ( $course, [],false ) );
                            }
                    }
                    
		}
                
                if($detailed){
                    $coursesForReservationsWithDate = ["date"=>Carbon::parse($date)->format('m/d/Y'), "courses"=>$coursesForReservations];
                    return $coursesForReservationsWithDate;
                }else{
                    return $coursesForReservations;
                }
		
                
                //dd($coursesForReservations);
                
                        
    }
    
    
    
    public static function generateReservationsTimeSlotsComplete($courseForReservation,$existingReservationTimeslotsForCourse = [],$detailed = true) {
        
        $open_time = Carbon::parse($courseForReservation->openTime);
        $close_time = Carbon::parse($courseForReservation->closeTime);
        $reservations = [];
        $open_time->diffFiltered(CarbonInterval::minute($courseForReservation->bookingDuration), function(Carbon $date) use(&$reservations,$detailed,$existingReservationTimeslotsForCourse) {
            $reservation = new \stdClass();
            $reservation->timeSlot = $date->format('h:i A');
            
            //Iterate through existing reservations in the course to see if there is an existing reservation on 
            //this timeslot if so replace its reservations with existing ones else make an empty array after 
            //the iteration is complete
            $foundExistingReservationOnTimeSlot = false;
           
            foreach($existingReservationTimeslotsForCourse as $existingReservationTimeslot){
                //dd($existingReservationTimeslotsForCourse);
               
                if(Carbon::parse($existingReservationTimeslot->timeSlot)->format('h:i A') == $reservation->timeSlot){
                    if($detailed){
                        $reservation->reservations = $existingReservationTimeslot->reservations;
                     }else{
                        $reservation->isReserved = true;
                        $reservation->players = [];
                        $populatedPlayers = false;
                       
                        foreach($existingReservationTimeslot->reservations as $reservationOnTimeSlot){
                            
                            foreach($reservationOnTimeSlot->reservationPlayers as $reservationPlayer){
                                if($reservationPlayer->player_id == Auth::user()->id){
                                        $reservation->timeEnd = Carbon::parse($reservationOnTimeSlot->time_end)->format('h:i A');
                                        foreach($reservationOnTimeSlot->reservationPlayers as $reservationPlayer){
                                            
                                            $reservation->players[] = $reservationPlayer->player_name;
                                            
                                        }
                                        $populatedPlayers = true;
                                        }
                                        if($populatedPlayers){
                                            break;
                                        }
                                }
                            
                            if($populatedPlayers){
                                    break;
                            }
                            
                        }
                       
                    }
                    $foundExistingReservationOnTimeSlot = true;
                    break;
                }
               
            }
                
            if(!$foundExistingReservationOnTimeSlot){
                if($detailed){

                    $reservation->reservations = [];

                 }else{
                    $reservation->time_end = "";
                    $reservation->isReserved = false;
                    
                    $reservation->players = [];
                    
                }
            }
            
          
            $reservations[] = $reservation;
        }, $close_time);
        
        return $reservations;
    }
    
    /**
     * returns all reservations with course info 
     */
    public static function getAllReservationsWithCoursesForASpecificDate($date){
        
        $query  = " SELECT "; 
        $query .= " course.id as course_id, ";
        $query .= " course.club_id as club_id, ";
        $query .= " course.name as course_name, ";
        $query .= " course.openTime, ";
        $query .= " course.closeTime, ";
        $query .= " course.bookingDuration, ";
        $query .= " tennis_reservation.id as tennis_reservation_id, ";
        $query .= " tennis_reservation.parent_id, ";
        $query .= " tennis_reservation.time_start, ";
        $query .= " tennis_reservation.time_end, ";
        $query .= " tennis_reservation.time_start as reserved_at, ";
        $query .= " GROUP_CONCAT(IFNULL(tennis_reservation_player.id,' ') ORDER BY tennis_reservation_player.id SEPARATOR '||-separation-player-||') as tennis_reservation_player_ids, ";
        $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY tennis_reservation_player.id SEPARATOR '||-separation-player-||') as player_ids, ";
        $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY tennis_reservation_player.id ASC SEPARATOR '||-separation-player-||' ) as player_names, ";
        $query .= " tennis_reservation.status ";
        $query .= " FROM ";
        $query .= " courses ";
        $query .= " LEFT JOIN tennis_reservation ON tennis_reservation.course_id = course.id ";
        $query .= " LEFT JOIN tennis_reservation_player ON tennis_reservation.id = tennis_reservation_player.tennis_reservation_id ";
        $query .= " LEFT JOIN member ON tennis_reservation_player.player_id = member.id ";
        $query .= " WHERE ";
        $query .= " DATE(tennis_reservation.time_start) = :date ";
        $query .= " AND course.club_id = ".Auth::user ()->club_id." ";
        $query .= "GROUP BY course_id, tennis_reservation.id  ";
        $query .= "ORDER BY course.id ASC, tennis_reservation.time_start ASC, tennis_reservation.id ASC   ";
 
        $allReservationsWithCourses = DB::select(DB::raw($query), ["date"=>$date]);
       // dd($allReservationsWithCourses);
        //array_splice( $original, 3, 0, $inserted )
         $abc = [];
         $splitExtraBookings = [];
        foreach($allReservationsWithCourses as $reservation){
            $timeStart = Carbon::parse($reservation->time_start);
            $timeEnd = Carbon::parse($reservation->time_end);
            $bookingDurationBasedOnStartAndEndTime = $timeEnd->diffInSeconds($timeStart); 
            $bookingDurationPerRules = $reservation->bookingDuration*60;
            $noOfBookings = $bookingDurationBasedOnStartAndEndTime/$bookingDurationPerRules;
         
            if($noOfBookings > 1){
               
               
                for($x=1; $x < $noOfBookings; $x++){
                    
                    $tempSplitBooking = json_decode(json_encode($reservation));
                    $tempSplitBooking->time_start = $timeStart->addSeconds($x*$bookingDurationPerRules)->toDateTimeString();
                    
                    $insertedSplitBooking = false;
                    $nextTimeSlotInReservationsCollectionIndex = null;
                    foreach($allReservationsWithCourses as $index=>$reservationSecondary){
                        try{
                            if($nextTimeSlotInReservationsCollectionIndex === null && 
                            Carbon::parse($reservationSecondary->time_start) > Carbon::parse($tempSplitBooking->time_start)){
                            $nextTimeSlotInReservationsCollectionIndex = $index;
                        }
                        if($reservationSecondary->time_start == $tempSplitBooking->time_start){
                          // $abc[] = $tempSplitBooking;
                           $resFirstDivision = array_slice($allReservationsWithCourses, 0, $index); 
                           $resFirstDivision[] = $tempSplitBooking;
                           $resSecondDivision = array_slice($allReservationsWithCourses, $index, count($allReservationsWithCourses) -1);
//                                
                           $allReservationsWithCourses = array_merge($resFirstDivision,$resSecondDivision);
                            $insertedSplitBooking = true;
                            break;
                        }
                        }catch(\Exception $e){
                            
                            //dd ($e);
                            
                        }
                        
                    }
                    if(!$insertedSplitBooking){
                        if($nextTimeSlotInReservationsCollectionIndex !== null){
                             $resFirstDivision = array_slice($allReservationsWithCourses, 0, $nextTimeSlotInReservationsCollectionIndex); 
                            $resFirstDivision[] = $tempSplitBooking;
                            $resSecondDivision = array_slice($allReservationsWithCourses, $nextTimeSlotInReservationsCollectionIndex, count($allReservationsWithCourses) -1);
                            $allReservationsWithCourses = array_merge($resFirstDivision,$resSecondDivision);
                          
                           
                        }else{
                            
                            $allReservationsWithCourses[] = $tempSplitBooking;
                        }
                    }
                    
                }
            }
            
            
        }

        //dd($allReservationsWithCourses);
        $courses = [];
        if(count($allReservationsWithCourses)){
            $tempCourseId = 0;
            $tempTimeSlot = "";
            $courseIndex = -1;
            $timeSlotIndex = 0;
            $reservationIndex = 0;
            $courses = [];
            
            foreach($allReservationsWithCourses as $reservation){
             
                //Change course if the id is different
                if($tempCourseId != $reservation->course_id){
                    //reset timeslot index on change of course
                    $timeSlotIndex = 0;
                    $tempCourseId = $reservation->course_id;
                    $courseIndex++;
                    $courses[$courseIndex] = new \stdClass();
                    $courses[$courseIndex]->club_id = $reservation->club_id;
                    $courses[$courseIndex]->course_id = $reservation->course_id;
                    $courses[$courseIndex]->openTime = $reservation->openTime;
                    $courses[$courseIndex]->closeTime = $reservation->closeTime;
                    $courses[$courseIndex]->bookingDuration = $reservation->bookingDuration;
                    $courses[$courseIndex]->timeSlots = [];
                    $tempTimeSlot = "";
                }
                
                //Change timeslot if different
                if($tempTimeSlot != $reservation->time_start){
                    $tempTimeSlot = $reservation->time_start;
                    //reset reservation index on change of time slot
                    $reservationIndex = 0;
                    
                    $timeSlotIndex++;
                    $courses[$courseIndex]->timeSlots[$timeSlotIndex] = new \stdClass();
                    $courses[$courseIndex]->timeSlots[$timeSlotIndex]->timeSlot = $reservation->time_start;
                    $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations = [];
                }
                
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex] = new \stdClass();
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->tennis_reservation_id = $reservation->tennis_reservation_id;
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_start = $reservation->time_start;
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_end = $reservation->time_end;
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reserved_at = $reservation->reserved_at;
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->status = $reservation->status;
                
                $tennis_reservation_player_ids = explode("||-separation-player-||",$reservation->tennis_reservation_player_ids);
                $player_ids = explode("||-separation-player-||",$reservation->player_ids);
                $player_names = explode("||-separation-player-||",$reservation->player_names);
                
                $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservationPlayers =collect([]);
                
                foreach($tennis_reservation_player_ids as $playerIndex=>$reservation_player_id){
                    $reservationPlayerObject = new \stdClass();
                    $reservationPlayerObject->tennis_reservation_player_id = trim($tennis_reservation_player_ids[$playerIndex]);
                    $reservationPlayerObject->player_id = trim($player_ids[$playerIndex]);
                    $reservationPlayerObject->player_name = trim($player_names[$playerIndex]);
                     
                     if($reservationPlayerObject->player_id == $reservation->parent_id){
                     
                        $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservationPlayers->prepend($reservationPlayerObject);
                     }else{
                         //bring parent to front
                         $courses[$courseIndex]->timeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservationPlayers->push($reservationPlayerObject);
                     }
                     
                }

                $reservationIndex++;
               
            }
        }
        
        
        return $courses;
   
    }
    
    public static function getCourseByClubId($course_id, $club_id) {
        return self::where('club_id', '=', $club_id)->where('id', '=', $course_id)->first();
    }
    /**
     * count total hours open for a course if reservations array is inflated
     */
    public function countTotalHours(){
    	if(!is_null($this->reservations)){
    		return count($this->reservations);
    	}else{
    		return 0;
    	}
    }
    
    /**
     * 
     * @param \App\Http\Models\Course $course
     * @param array $reservationsArray
     */
    public static function returnCourseWithCompleteReservationsObject(Course $course, $reservationsArray){
        $courseWithCompleteReservations = new \stdClass();
        
        $courseWithCompleteReservations->course_id = $course->id;
        $courseWithCompleteReservations->club_id = $course->club_id;
        $courseWithCompleteReservations->course_name = $course->name;
        $courseWithCompleteReservations->openTime = $course->openTime;
        $courseWithCompleteReservations->closeTime = $course->closeTime;
        $courseWithCompleteReservations->bookingDuration = $course->bookingDuration;
        $courseWithCompleteReservations->reservations = $reservationsArray;
        
        return $courseWithCompleteReservations;
    }

}
