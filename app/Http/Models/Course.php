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
	
    protected  $table = "course";
    public $timestamps = false;
	protected $fillable = [ 
			'name',
			'club_id',
			'openTime',
			'closeTime',
			'bookingDuration',
			'bookingInterval',
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
	 * To get reservations for all course with players and other details: detailed = true
         * To get reservations for all course with booking indicators for mobile service: detailed = false
	 */
     public static function getReservationsForACourseByIdForADateRange($courseId,$dateStart,$dateEnd, $detailed = true) {
                //$date = !$date ? Carbon::today()->toDateString() : $date;
                
		$course = Course::find($courseId);
                
                $allCurrentReservationsInDateRange = Course::getAllReservationsAtACourseForADateRange($courseId,Carbon::parse($dateStart)->toDateString(),Carbon::parse($dateEnd)->toDateString());
                $dates = [];
                Carbon::parse($dateStart)->diffInDaysFiltered(function (Carbon $date) use (&$dates) {
			$dates [] = $date->toDateString();
		}, Carbon::parse($dateEnd)->addDay());
                
                $reservationsParent = new \stdClass();
                $reservationsParent->club_id = $course->club_id;
                $reservationsParent->course_id = $course->id;
                $reservationsParent->reservationsByDate = [];
                foreach($dates as $date){
                    $reservationsByDate = new \stdClass();
                    $dateObject = Carbon::parse($date);
                    $reservationsByDate->reserved_at = $dateObject->toDateString();
                    $reservationsByDate->dayNumber = $dateObject->day;
                    $reservationsByDate->dayName = $dateObject->format('l');
                    $reservationsByDate->reservationsByTimeSlot = [];
                    Carbon::parse ( $course->openTime )->diffFiltered ( CarbonInterval::minute ( $course->bookingInterval), function (Carbon $time) use (&$reservationsByDate, $date,$allCurrentReservationsInDateRange,$detailed) {
			$timeSlot = new \stdClass();
                        $timeSlot->timeSlot = $time->format ( 'h:i A' );
                        $foundExistingReservationsForTimeSlot = false;
                        foreach($allCurrentReservationsInDateRange as $reservation){
                            
                            if($reservation->reserved_at == $date){
                                 
                                foreach($reservation->reservationsByTimeSlots as $reservationsByTimeSlot ){
                                   
                                    if($reservationsByTimeSlot->timeSlot == $time->toTimeString()){
                                        
                                        $timeSlot->reservations = $reservationsByTimeSlot->reservations;
                                        $foundExistingReservationsForTimeSlot = true;
                                    }
                                }
                               
                                
                            }
                        }
                        if(! $foundExistingReservationsForTimeSlot){
                            if($detailed){
                                $timeSlot->reservations = [];
                                $timeSlot->reservations[0] = new \stdClass();
                                $timeSlot->reservations[0]->reservation_id = '';
                                $timeSlot->reservations[0]->reservation_type = '';
                                $timeSlot->reservations[0]->status = '';
                                $timeSlot->reservations[0]->players = [];
                                $timeSlot->reservations[0]->guests = 0;
                            }else{
                                $timeSlot->reservations = [];
                            }
                            
                        }
                        
                        
                        $reservationsByDate->reservationsByTimeSlot[] = $timeSlot;
                    }, Carbon::parse ( $course->closeTime )->subMinutes($course->bookingDuration-$course->bookingInterval) );
                    
                    //TODO
                    //$reservationsByDate->reservationsByTimeSlot = getAllReservationsBy;
                    $reservationsParent->reservationsByDate[] = $reservationsByDate;
                    
                }
                
                return $reservationsParent;
                
		
                
                        
    }
    
   
    /**
     * returns all reservations with course info 
     */
    public static function getAllReservationsAtACourseForADateRange($courseId,$dateStart,$dateEnd){
        //dd(RoutineReservation::class);
        //First Set of Data for Routine Reservations
        $query  = " SELECT "; 
        $query .= " course.id as course_id, ";
        $query .= " course.club_id as club_id, ";
        $query .= " course.name as course_name, ";
        //$query .= " course.openTime, ";
        //$query .= " course.closeTime, ";
        //$query .= " course.bookingDuration, ";
        $query .= " routine_reservations.id as reservation_id, ";
        $query .= " reservation_time_slots.reservation_type as reservation_type, ";
        $query .= " routine_reservations.parent_id, ";
        $query .= " TIME(reservation_time_slots.time_start) as time_start, ";
        //$query .= " tennis_reservation.time_end, ";
        $query .= " DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= " GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= " routine_reservations.status ";
        $query .= " FROM ";
        $query .= " course ";
        //$query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
        //Start : To Join reservation type RoutineReservation
        $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
        $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'".RoutineReservation::class."') ";
        $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'".RoutineReservation::class."') ";
        
        $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
        //End : To Join a reservation type RoutineReservation
        $query .= " WHERE ";
        $query .= " course.id = ? ";
        $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
        $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
        $query .= " AND course.club_id = ? ";
        $query .= " GROUP BY routine_reservations.id,reservation_time_slots.time_start,reservation_time_slots.reservation_type ";
        
//        START:To add other reservation types results in the future

//        $query .= " UNION ALL ";
//
//        $query .= " SELECT "; 
//        $query .= " course.id as course_id, ";
//        $query .= " course.club_id as club_id, ";
//        $query .= " course.name as course_name, ";
//        $query .= " routine_reservations.id as reservation_id, ";
//        $query .= " ANY_VALUE(reservation_time_slots.reservation_type) as reservation_type, ";
//        $query .= " routine_reservations.parent_id, ";
//        $query .= " reservation_time_slots.time_start as time_start, ";
//        $query .= " reservation_time_slots.time_start as reserved_at, ";
//        $query .= " GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_players_ids, ";
//        $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
//        $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
//        $query .= " routine_reservations.status ";
//        $query .= " FROM ";
//        $query .= " course ";
//        $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
//        $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'".RoutineReservation::class."') ";
//        $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'".RoutineReservation::class."') ";
//        $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
//        $query .= " WHERE ";
//        $query .= " course.id = ? ";
//        $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
//        $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
//        $query .= " AND course.club_id = ? ";
//        $query .= " GROUP BY routine_reservations.id,reservation_time_slots.time_start ";
        
//        END:To add other reservation types results in the future
        $query .= "ORDER BY time_start ASC, reservation_id ASC   ";
  
        $allReservationsWithCourses = DB::select(DB::raw($query), [$courseId,
                                                                   $dateStart,
                                                                   $dateEnd,
                                                                   Auth::user ()->club_id]);
        //dd($allReservationsWithCourses);
       
        $reservationsByDate = [];
        if(count($allReservationsWithCourses)){
            $tempDate = 0;
            $tempTimeSlot = "";
            $dateIndex = -1;
            $timeSlotIndex = 0;
            $reservationIndex = 0;
            $reservationsByDate = [];
            
            foreach($allReservationsWithCourses as $reservation){
             
                //Change course if the id is different
                if($tempDate != $reservation->reserved_at){
                    //reset timeslot index on change of course
                    $timeSlotIndex = 0;
                    $tempDate = $reservation->reserved_at;
                    $dateIndex++;
                    $reservationsByDate[$dateIndex] = new \stdClass();
                    $dateObject = Carbon::parse($reservation->time_start);
                    $reservationsByDate[$dateIndex]->reserved_at = $reservation->reserved_at;
                    $reservationsByDate[$dateIndex]->dayNumber = $dateObject->day;
                    $reservationsByDate[$dateIndex]->dayName = $dateObject->format('l');
                    //$reservationsByDate[$dateIndex]->course_id = $reservation->course_id;
                    //$reservationsByDate[$dateIndex]->openTime = $reservation->openTime;
                    //$reservationsByDate[$dateIndex]->closeTime = $reservation->closeTime;
                    //$reservationsByDate[$dateIndex]->bookingDuration = $reservation->bookingDuration;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots = [];
                    $tempTimeSlot = "";
                }
                
                //Change timeslot if different
                if($tempTimeSlot != $reservation->time_start){
                    $tempTimeSlot = $reservation->time_start;
                    //reset reservation index on change of time slot
                    $reservationIndex = 0;
                    
                    $timeSlotIndex++;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->timeSlot = $reservation->time_start;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations = [];
                }
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex] = new \stdClass();
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservation_id = $reservation->reservation_id;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservation_type = $reservation->reservation_type;
                //$reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_start = $reservation->time_start;
                //$reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_end = $reservation->time_end;
                //$reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reserved_at = $reservation->reserved_at;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->status = $reservation->status;
                
                $reservation_player_ids = explode("||-separation-player-||",$reservation->reservation_player_ids);
                $member_ids = explode("||-separation-player-||",$reservation->member_ids);
                $member_names = explode("||-separation-player-||",$reservation->member_names);
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players =collect([]);
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->guests = 0;
                foreach($reservation_player_ids as $playerIndex=>$reservation_player_id){
                    
                        if($member_ids[$playerIndex] == 0){
                            $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->guests++;
                        }
                 
                        $reservationPlayerObject = new \stdClass();
                        $reservationPlayerObject->reservation_player_id = trim($reservation_player_ids[$playerIndex]);
                        $reservationPlayerObject->member_id = trim($member_ids[$playerIndex]);
                        $reservationPlayerObject->member_name = trim($member_names[$playerIndex]);


                        if($reservationPlayerObject->member_id == $reservation->parent_id){

                            $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players->prepend($reservationPlayerObject);
                        }else{
                             //bring parent to front
                             $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players->push($reservationPlayerObject);
                        }
              
                    
                     
                }

                $reservationIndex++;
               
            }
        }
        //dd($reservationsByDate);
        return $reservationsByDate;
   
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
