<?php
namespace App\Http\Models;

use Illuminate\Support\Facades\Auth;
use App\Http\Models\Club;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

// use \App\CustomModels\CoursesAndReservations\CourseForReservations;
// use \App\CustomModels\CoursesAndReservations\ReservationWrapper;
// use \App\CustomModels\CoursesAndReservations\ReservationInfo;
// use \App\CustomModels\CoursesAndReservations\ReservationPlayer;


class Course extends Model
{

    protected $table = "course";

    public $timestamps = false;

    protected $fillable = [
        'name',
        'openTime',
        'closeTime',
        'bookingDuration',
        'bookingInterval',
        'numberOfHoles',
        'status'
    ];

    private $gaurded = [
        'club_id'
    ];

    public $reservations;

    /**
     *
     * @deprecated use fill instead
     * @param array $data            
     * @return \App\Http\Models\Course
     */
    public function populate($data = [])
    {
        if (array_key_exists('name', $data)) {
            $this->name = $data['name'];
        }
        if (array_key_exists('club_id', $data)) {
            $this->club_id = $data['club_id'];
        }
        if (array_key_exists('openTime', $data)) {
            $this->openTime = $data['openTime'];
        }
        if (array_key_exists('closeTime', $data)) {
            $this->closeTime = $data['closeTime'];
        }
        if (array_key_exists('bookingDuration', $data)) {
            $this->bookingDuration = $data['bookingDuration'];
        }
        if (array_key_exists('status', $data)) {
            $this->status = $data['status'];
        }
        if (array_key_exists('ballMachineAvailable', $data)) {
            $this->ballMachineAvailable = $data['ballMachineAvailable'];
        }
        if (array_key_exists('environment', $data)) {
            $this->environment = $data['environment'];
        }
        return $this;
    }

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public static function getById($id)
    {
        return self::find($id);
    }

    public static function courseList()
    {
        return Club::find(Auth::user()->club_id)->course;
    }

    public static function courseListByClubId($club_id, $count = false)
    {
        $course = self::where('club_id', '=', $club_id);
        if ($count)
            return $course->count();
        return $course->get();
    }

    public function timeSlots()
    {
        $open_time = Carbon::parse($this->openTime);
        $close_time = Carbon::parse($this->closeTime);
        $timeSlots = [];
        // $that = $this;
        $open_time->diffFiltered(CarbonInterval::minute($this->bookingDuration), function (Carbon $date) use (&$timeSlots) {
            $timeSlots[] = $date->format('h:i A');
        }, $close_time);
        $this->reservations = $timeSlots;
        return $this;
    }
    /**
	 * To get reservations for all course with players and other details: detailed = true
         * To get reservations for all course with booking indicators for mobile service: detailed = false
	 */
     public static function getReservationsForACourseByIdForADateRange($course,$dateStart,$dateEnd, $detailed = true) {
                //$date = !$date ? Carbon::today()->toDateString() : $date;
                

                
                $allCurrentReservationsInDateRange = Course::getAllReservationsAtACourseForADateRange($course->id,Carbon::parse($dateStart)->toDateString(),Carbon::parse($dateEnd)->toDateString());

                $dates = [];
                Carbon::parse($dateStart)->diffInDaysFiltered(function (Carbon $date) use (&$dates) {
			$dates [] = $date->toDateString();
		}, Carbon::parse($dateEnd)->addDay());
                
                $reservationsParent = Course::createAllReservationsParentObject($course);
                foreach($dates as $date){
                    $reservationsByDate = Course::createReservationsByDateObject($date);
                    Carbon::parse ( $course->openTime )->diffFiltered ( CarbonInterval::minute ( $course->bookingInterval), function (Carbon $time) use (&$reservationsByDate, $date,$allCurrentReservationsInDateRange,$detailed) {
			$timeSlot = Course::createReservationsByTimeSlotsObject($time,$detailed);
                        foreach($allCurrentReservationsInDateRange as $reservation){
                            
                            if($reservation->reserved_at == $date){
                                 
                                foreach($reservation->reservationsByTimeSlot as $reservationsByTimeSlot ){
                                   try{
                                       if($reservationsByTimeSlot->timeSlot == $time->format ( 'h:i A' )){

                                           $timeSlot->reservations = $reservationsByTimeSlot->reservations;

                                       }
                                   }catch (\Exception $e){

                                   }

                                }
                               
                                
                            }
                        }
                        
                        $reservationsByDate->reservationsByTimeSlot[] = $timeSlot;
                    }, Carbon::parse ( $course->closeTime )->subMinutes($course->bookingDuration-$course->bookingInterval) );
                    

                    $reservationsParent->reservationsByDate[] = $reservationsByDate;
                    
                }
                //dd($reservationsParent);
                return $reservationsParent;
                
		
                
                        
    }
    
   
    /**
     * returns all reservations with course info 
     */
    public static function getAllReservationsAtACourseForADateRange($courseId,$dateStart,$dateEnd){
        //dd(RoutineReservation::class);
        //First Set of Data for Routine Reservations
        $query  = " SELECT * FROM compound_reservations_aggregated ";
        $query .= " WHERE ";
        $query .= " course_id = ? ";
        if($dateStart == $dateEnd){
            $query .= " AND DATE(date_time_start) = DATE(?) ";
        }else{
            $query .= " AND DATE(date_time_start) >= DATE(?) ";
            $query .= " AND DATE(date_time_start) <= DATE(?) ";
        }
        $query .= " AND club_id = ? ";
        $query .= "ORDER BY time_start ASC, reservation_id ASC   ";

        if($dateStart == $dateEnd){
            $allReservationsWithCourses = DB::select(DB::raw($query), [$courseId,
                                                                   $dateStart,
                                                                   Auth::user ()->club_id]); 
        }else{
            
            $allReservationsWithCourses = DB::select(DB::raw($query), [$courseId,
                                                                   $dateStart,
                                                                   $dateEnd,
                                                                   Auth::user ()->club_id]); 
        }

       

        $reservationsByDate = Course::returnReseravtionObjectsArrayFromReservationArray($allReservationsWithCourses);
        return $reservationsByDate;
   
    }


    /**
     * @param string $startDateTime
     * @return int
     *
     * The method will check if a validation is allowed on a timeslot by getting all reservations and then
     * checking if a reservation for a type with higher priority than routine reservation exists. Otherwise return
     * the reservation id
     *
     * returns bool false  if reservation is not allowed
     * returns 0 if no reservation is found
     * returns reservation id is a routine reservation is found and is allowed reservation
     */
    public function validateIfAllowedRoutineReservationAndReturnIdIfAlreadyExists($startDateTime){
        $reservations =  DB::table('reservations_by_timeslots')->where("course_id",$this->id)->where("time_start",$startDateTime)->get();
        $firstRoutineReservation = null;
        $reservationNotAllowedAtTimeSlot = 0;
        foreach($reservations as $reservation)
        {
            if($reservation->reservation_type == RoutineReservation::class)
            {
                $firstRoutineReservation = $firstRoutineReservation == null ? $reservation : $firstRoutineReservation;
            }
            //Blocks to follow will test other reservation types and if existence of any other restricts routine reservations
            //set the reservationNotAllowedAtTimeSlot variable to true
            else if(false)
            {
                $reservationNotAllowedAtTimeSlot = true;
            }

        }
        //return with false if not allowed a reservation
        if($reservationNotAllowedAtTimeSlot)
        {
            return false;
        }
        else
        {
            if($firstRoutineReservation != null){
                return $firstRoutineReservation->reservation_id;
            }else{
                return 0;
            }

        }


    }
    
    /**
     * 
     * @param string $startDateTime
     * @return reservation object 
     * Gets reseravtion in accordance with the reservations_by_timeslots view i-e excludes players
     * Useful where we need to find how many reservations we have on a timeslot such as in the validation process
     * for reservations etc
     */
    public function getResevationsAtCourseForATimeSlot($startDateTime){
        return DB::table('reservations_by_timeslots')->where("course_id",$this->id)->where("time_start",$startDateTime)->get();
    }


    
    /**
     * 
     * @param int $course_id
     * @param array $reservation_time_slots
     * @return reservation objects array 
     * Gets reseravtion objects array for a datetimes array i-e start times. Returns an array of reservation
     * objects in the same format as of the all reservations list for court
     * **Replaced By the similar function getResevationsWithPlayersAtCourseForMultipleIds in actual usage. Kept just in case it might be useful in the future**
     * 
     */
    public static function getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($course_id,$reservation_time_slots){
        $allReservationsWithCourses = [];
        foreach($reservation_time_slots as $time_slot){
            $reservation = DB::table('compound_reservations_aggregated')->where("course_id",$course_id)->where("date_time_start",$time_slot->time_start)->first();

            if($reservation){
                $allReservationsWithCourses[] = $reservation;
            }else{

                $blankReservation = Course::generateBlankReservationForATimeSlot($time_slot->time_start,$course_id);
                $allReservationsWithCourses[] = $blankReservation;
                
            }
        }

        return Course::returnReseravtionObjectsArrayFromReservationArray($allReservationsWithCourses);
        
    }

    /**
     *
     * @param int $course_id
     * @param array $timeSlotsForDeletedReservations
     * @return reservation objects array
     * Gets reseravtion objects array for an ids array. Returns an array of reservation
     * objects in the same format as of the all reservations list for court
     * Uses course_id,  timeSlots to create blank reservations for the deleted reservations
     *
     */
    public static function getResevationsWithPlayersAtCourseForMultipleIds($course_id,$timeSlots,$reservation_ids){

            $allReservations = [];
            $reservations = DB::table('compound_reservations_aggregated')->whereIn("reservation_id",$reservation_ids)->get();

            foreach($timeSlots as $timeslot){
                $foundReservationAgainstTimeSlot = false;
                foreach ($reservations as $reservation){
                    if($reservation->date_time_start == $timeslot->time_start ){
                        $foundReservationAgainstTimeSlot = true;
                        $allReservations[] = $reservation;
                        break;
                    }
                }

                if(!$foundReservationAgainstTimeSlot){
                    $blankReservation = Course::generateBlankReservationForATimeSlot($timeslot->time_start,$course_id);
                    $allReservations[] = $blankReservation;
                }
            }



        return Course::returnReseravtionObjectsArrayFromReservationArray($allReservations);

    }


    /**
     * To generate a blank reservation object for a timeslot to be sent along with database records to be converted to
     * reservation objects that can be sent back to clients for display
     * To be user where a reservation is not found at a timeslot i-e deleted
     */
    public static function generateBlankReservationForATimeSlot($timeStart,$courseId){
        $time = Carbon::parse($timeStart);
        $blankReservation = new \stdClass();
        $blankReservation->club_id = "";
        $blankReservation->course_id = $courseId;
        $blankReservation->reserved_at = $time->toDateString() ;
        $blankReservation->time_start = $time->toTimeString() ;
        $blankReservation->reservation_id = "";
        $blankReservation->reservation_type = "";
        $blankReservation->game_status = "";
        $blankReservation->status = "";
        $blankReservation->reservation_player_ids = "";
        $blankReservation->member_profile_pics = "";
        $blankReservation->response_statuses = "";
        $blankReservation->member_ids = "";
        $blankReservation->member_names = "";
        $blankReservation->processTypes = "";
        $blankReservation->comingOnTime_responses = "";
        $blankReservation->parent_id = "";
        $blankReservation->club_entries = "";
        $blankReservation->game_entries= "";

        return $blankReservation;
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
    * @param type $reservationsArray
    * @return reservation objects array by date
    * converts and returns reservations results from a query on compound_reservations_aggregate view 
    * to reservation objects 
    */
   public static function returnReseravtionObjectsArrayFromReservationArray($reservationsArray){
       $reservationsByDate = [];
        if(count($reservationsArray)){
            $tempDate = 0;
            $tempTimeSlot = "";
            $dateIndex = -1;
            $timeSlotIndex = 0;
            $reservationIndex = 0;
            
            foreach($reservationsArray as $reservation){
             
                //Change course if the id is different
                if($tempDate != $reservation->reserved_at){
                    //reset timeslot index on change of course
                    $timeSlotIndex = 0;
                    $tempDate = $reservation->reserved_at;
                    $dateIndex++;
                    $reservationsByDate[$dateIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->course_id = $reservation->course_id;
                    $dateObject = Carbon::parse($reservation->time_start);
                    $reservationsByDate[$dateIndex]->reserved_at = $reservation->reserved_at;
                    $reservationsByDate[$dateIndex]->dayNumber = $dateObject->day;
                    $reservationsByDate[$dateIndex]->dayName = $dateObject->format('l');
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot = [];
                    $tempTimeSlot = "";
                }
                
                //Change timeslot if different
                if($tempTimeSlot != $reservation->time_start){
                    $tempTimeSlot = $reservation->time_start;
                    //reset reservation index on change of time slot
                    $reservationIndex = 0;
                    
                    $timeSlotIndex++;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->timeSlot = Carbon::parse($reservation->time_start)->format ( 'h:i A' );
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations = [];
                }
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex] = new \stdClass();
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->reservation_id = $reservation->reservation_id;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->reservation_type = $reservation->reservation_type;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->game_status = $reservation->game_status;
                //$reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->reservation_status = $reservation->reservation_status;
                
                $reservation_player_ids = $reservation->reservation_player_ids !== "" ? explode("||-separation-player-||",$reservation->reservation_player_ids) : [];
                $member_ids = $reservation->member_ids !== "" ? explode("||-separation-player-||",$reservation->member_ids) : [];
                $member_names = $reservation->member_names !== "" ? explode("||-separation-player-||",$reservation->member_names) : [];
                $member_profile_pics = $reservation->member_profile_pics !== "" ? explode("||-separation-player-||",$reservation->member_profile_pics) : [];
                $response_statuses = $reservation->response_statuses !== "" ? explode("||-separation-player-||",$reservation->response_statuses) : [];
                $comingOnTime_responses = $reservation->comingOnTime_responses !== "" ? explode("||-separation-player-||",$reservation->comingOnTime_responses) : [];
                $processTypes = $reservation->processTypes !== "" ? explode("||-separation-player-||",$reservation->processTypes) : [];
                $clubEntries = $reservation->club_entries !== "" ? explode("||-separation-player-||",$reservation->club_entries) : [];
                $gameEntries = $reservation->game_entries !== "" ? explode("||-separation-player-||",$reservation->game_entries) : [];
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->players =collect([]);
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->guests = 0;
                foreach($reservation_player_ids as $playerIndex=>$reservation_player_id){
                        
                        if($member_ids[$playerIndex] == 0){
                            $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->guests++;
                        }
                 
                        $reservationPlayerObject = new \stdClass();
                        $reservationPlayerObject->reservation_player_id = trim($reservation_player_ids[$playerIndex]);
                        $reservationPlayerObject->member_id = trim($member_ids[$playerIndex]);
                        $reservationPlayerObject->member_name = trim($member_names[$playerIndex]);
                        $reservationPlayerObject->profilePic = trim($member_profile_pics[$playerIndex]);
                        $reservationPlayerObject->response_status = trim($response_statuses[$playerIndex]);
                        $reservationPlayerObject->comingOnTime = trim($comingOnTime_responses[$playerIndex]);
                        $reservationPlayerObject->process_type = trim($processTypes[$playerIndex]);
                        $reservationPlayerObject->club_entry = trim($clubEntries[$playerIndex]);
                        $reservationPlayerObject->game_entry = trim($gameEntries[$playerIndex]);
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->players->push($reservationPlayerObject);

              
                    
                     
                }
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot = array_values($reservationsByDate[$dateIndex]->reservationsByTimeSlot);
                $reservationIndex++;
               
            }
        }
        ;
        return $reservationsByDate;
   }
   
   public static function createAllReservationsParentObject($course){
        $reservationsParent = new \stdClass();
        $reservationsParent->club_id = $course->club_id;
        $reservationsParent->course_id = $course->id;
        $reservationsParent->courseOpenTime = $course->openTime;
        $reservationsParent->courseCloseTime = $course->closeTime;
        $reservationsParent->reservationsByDate = [];
        
        return $reservationsParent;
   }
   
   public static function createReservationsByDateObject($date){
        $reservationsByDate = new \stdClass();
        $dateObject = Carbon::parse($date);
        $reservationsByDate->reserved_at = $dateObject->toDateString();
        $reservationsByDate->dayNumber = $dateObject->day;
        $reservationsByDate->dayName = $dateObject->format('l');
        $reservationsByDate->reservationsByTimeSlot = [];
        
        return $reservationsByDate;
   }
   
   public static function createReservationsByTimeSlotsObject($time,$detailed){
        $timeSlot = new \stdClass();
        $timeSlot->timeSlot = $time->format ( 'h:i A' );
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

        return $timeSlot;
   }
    /**
     * Get paginated list of members for logedin club
     * @usage Web
     *
     * @param unknown $clubId            
     * @param unknown $currentPage            
     * @param unknown $perPage            
     */
    public function listClubCoursesPaginated($clubId, $currentPage, $perPage, $searchTerm = false)
    {
        return $this->where('club_id', '=', $clubId)
            ->where(function ($query) use ($searchTerm) {
            if ($searchTerm) {
                $query->orWhere('course.name', 'like', "%$searchTerm%");
            }
        })
            ->select('course.id as id', 'course.name', 'course.openTime', 'course.closeTime', 'course.bookingInterval', 'course.bookingDuration', 'course.numberOfHoles', 'course.status')
            ->orderby('course.id', 'DESC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }

    /**
     * @param $club_id
     * @param $member_id
     *
     *
     * returns most relevant reservation for a member for current time
     * If the current time falls between a users reservation that reservation will be the most relevant one
     * else the next closest reservation will be the most relevant one
     * useful for gameCheckin event where we need to find the reservation to checkin for based on current time
     *
     * returns the most relevant reservation if found and boolean false if not
     */
    public function returnMostRelevantReservationForAMemberForCurrentTime($member_id){

        $date = Carbon::today()->toDateString();
        $dateTime = Carbon::now();

        // Needs to be modified to accomodate for other reservation types such as Leagues
        $reservationsForPlayerToday = RoutineReservation::select("routine_reservations.id as id", "reservation_players.reservation_type", "routine_reservations.club_id","reservation_time_slots.time_start",DB::raw("DATE_ADD(reservation_time_slots.time_start, INTERVAL $this->bookingDuration MINUTE) as time_end"))
            ->leftJoin('reservation_players', function ($join) {
                $join->on('routine_reservations.id', '=','reservation_players.reservation_id')
                    ->where('reservation_players.reservation_type', RoutineReservation::class);
            })
            ->leftJoin('reservation_time_slots', function ($join) {
                $join->on('routine_reservations.id', '=', 'reservation_time_slots.reservation_id')
                    ->where('reservation_time_slots.reservation_type', RoutineReservation::class);
            })
            ->where('routine_reservations.course_id',$this->id)
            ->where('reservation_players.member_id',$member_id)
            ->where('reservation_players.reservation_status',\Config::get('global.reservation.reserved'))
            ->whereDate('reservation_time_slots.time_start','=',$date)
            ->orderBy('reservation_time_slots.time_start')
            ->get();
       
        //scan for a reservation surrounding current time
        foreach($reservationsForPlayerToday as $reservation){
            if($dateTime >= $reservation->start_time || $dateTime < $reservation->end_time){
                return $reservation;
            }
        }

        //else return first reservation with start time greater than curren time i-e next on time reservation
        foreach($reservationsForPlayerToday as $reservation){
            if($dateTime < $reservation->start_time){
                return $reservation;
            }
        }

        return false;

    }
}
