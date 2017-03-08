<?php
namespace App\Http\Models;

use Illuminate\Support\Facades\Auth;
use App\Http\Models\Club;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;
// use \App\CustomModels\CoursesAndReservations\CourseForReservations;
// use \App\CustomModels\CoursesAndReservations\ReservationWrapper;
// use \App\CustomModels\CoursesAndReservations\ReservationInfo;
// use \App\CustomModels\CoursesAndReservations\ReservationPlayer;
use DB;

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
        return $this->belongsTo('\App\Http\Models\Club', 'club_id');
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
     */
    
    /**
     * To get reservations for all course with players and other details: detailed = true
     * To get reservations for all course with booking indicators for mobile service: detailed = false
     */
    public static function getReservationsForACourseByIdForADateRange($courseId, $dateStart, $dateEnd, $detailed = true)
    {
        // $date = !$date ? Carbon::today()->toDateString() : $date;
        $course = Course::find($courseId);
        
        $allCurrentReservationsInDateRange = Course::getAllReservationsAtACourseForADateRange($courseId, Carbon::parse($dateStart)->toDateString(), Carbon::parse($dateEnd)->toDateString());
        $dates = [];
        Carbon::parse($dateStart)->diffInDaysFiltered(function (Carbon $date) use (&$dates) {
            $dates[] = $date->toDateString();
        }, Carbon::parse($dateEnd)->addDay());
        
        $reservationsParent = Course::createAllReservationsParentObject($course->club_id, $course->id);
        foreach ($dates as $date) {
            $reservationsByDate = Course::createReservationsByDateObject($date);
            Carbon::parse($course->openTime)->diffFiltered(CarbonInterval::minute($course->bookingInterval), function (Carbon $time) use (&$reservationsByDate, $date, $allCurrentReservationsInDateRange, $detailed) {
                $timeSlot = Course::createReservationsByTimeSlotsObject($time, $detailed);
                foreach ($allCurrentReservationsInDateRange as $reservation) {
                    
                    if ($reservation->reserved_at == $date) {
                        
                        foreach ($reservation->reservationsByTimeSlot as $reservationsByTimeSlot) {
                            
                            if ($reservationsByTimeSlot->timeSlot == $time->format('h:i A')) {
                                
                                $timeSlot->reservations = $reservationsByTimeSlot->reservations;
                                $foundExistingReservationsForTimeSlot = true;
                            }
                        }
                    }
                }
                
                $reservationsByDate->reservationsByTimeSlot[] = $timeSlot;
            }, Carbon::parse($course->closeTime)->subMinutes($course->bookingDuration - $course->bookingInterval));
            
            // TODO
            // $reservationsByDate->reservationsByTimeSlot = getAllReservationsBy;
            $reservationsParent->reservationsByDate[] = $reservationsByDate;
        }
        
        return $reservationsParent;
    }

    /**
     * returns all reservations with course info
     */
    public static function getAllReservationsAtACourseForADateRange($courseId, $dateStart, $dateEnd)
    {
        // dd(RoutineReservation::class);
        // First Set of Data for Routine Reservations
        $query = " SELECT ";
        $query .= " course.id as course_id, ";
        $query .= " course.club_id as club_id, ";
        $query .= " course.name as course_name, ";
        // $query .= " course.openTime, ";
        // $query .= " course.closeTime, ";
        // $query .= " course.bookingDuration, ";
        $query .= " routine_reservations.id as reservation_id, ";
        $query .= " reservation_time_slots.reservation_type as reservation_type, ";
        $query .= " routine_reservations.parent_id, ";
        $query .= " TIME(reservation_time_slots.time_start) as time_start, ";
        // $query .= " tennis_reservation.time_end, ";
        $query .= " DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= " GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= " routine_reservations.status ";
        $query .= " FROM ";
        $query .= " course ";
        // $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
        // Start : To Join reservation type RoutineReservation
        $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
        $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'" . RoutineReservation::class . "') ";
        $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'" . RoutineReservation::class . "') ";
        
        $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
        // End : To Join a reservation type RoutineReservation
        $query .= " WHERE ";
        $query .= " course.id = ? ";
        $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
        $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
        $query .= " AND course.club_id = ? ";
        $query .= " GROUP BY course.id,course.club_id,course.name,routine_reservations.parent_id,routine_reservations.status,routine_reservations.id,reservation_time_slots.time_start,reservation_time_slots.reservation_type ";
        
        // START:To add other reservation types results in the future
        
        // $query .= " UNION ALL ";
        //
        // $query .= " SELECT ";
        // $query .= " course.id as course_id, ";
        // $query .= " course.club_id as club_id, ";
        // $query .= " course.name as course_name, ";
        // $query .= " routine_reservations.id as reservation_id, ";
        // $query .= " ANY_VALUE(reservation_time_slots.reservation_type) as reservation_type, ";
        // $query .= " routine_reservations.parent_id, ";
        // $query .= " reservation_time_slots.time_start as time_start, ";
        // $query .= " reservation_time_slots.time_start as reserved_at, ";
        // $query .= " GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_players_ids, ";
        // $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        // $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        // $query .= " routine_reservations.status ";
        // $query .= " FROM ";
        // $query .= " course ";
        // $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = course.id ";
        // $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'".RoutineReservation::class."') ";
        // $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'".RoutineReservation::class."') ";
        // $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
        // $query .= " WHERE ";
        // $query .= " course.id = ? ";
        // $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
        // $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
        // $query .= " AND course.club_id = ? ";
        // $query .= " GROUP BY routine_reservations.id,reservation_time_slots.time_start ";
        
        // END:To add other reservation types results in the future
        $query .= "ORDER BY time_start ASC, reservation_id ASC   ";
        
        $allReservationsWithCourses = DB::select(DB::raw($query), [
            $courseId,
            $dateStart,
            $dateEnd,
            Auth::user()->club_id
        ]);
        // dd($allReservationsWithCourses);
        $reservationsByDate = Course::returnReseravtionObjectsArrayFromReservationArray($allReservationsWithCourses);
        return $reservationsByDate;
    }

    /**
     *
     * @param string $startDateTime            
     * @return reservation object
     *         Gets reseravtion in accordance with the reservations_by_timeslots view i-e excludes players
     *         Useful where we need to find how many reservations we have on a timeslot such as in the validation process
     *         for reservations etc
     */
    public function getResevationsAtCourseForATimeSlot($startDateTime)
    {
        return DB::table('reservations_by_timeslots')->where("course_id", $this->id)
            ->where("time_start", $startDateTime)
            ->get();
    }

    /**
     *
     * @param int $course_id            
     * @param array $reservation_time_slots            
     * @return reservation objects array
     *         Gets reseravtion objects array for a datetimes array i-e start times. Returns an array of reservation
     *         objects in the same format as of the all reservations list for court
     *        
     */
    public static function getFirstResevationsWithPlayersAtCourseForMultipleTimeSlots($course_id, $reservation_time_slots)
    {
        $allReservationsWithCourses = [];
        foreach ($reservation_time_slots as $time_slot) {
            $reservation = DB::table('compound_reservations_aggregated')->where("course_id", $course_id)
                ->where("date_time_start", $time_slot->time_start)
                ->first();
            
            if ($reservation) {
                $allReservationsWithCourses[] = $reservation;
            } else {
                $time = Carbon::parse($time_slot->time_start);
                $blankReservation = new \stdClass();
                $blankReservation->club_id = "";
                $blankReservation->course_id = $course_id;
                $blankReservation->reserved_at = $time->toDateString();
                $blankReservation->time_start = $time->toTimeString();
                $blankReservation->reservation_id = "";
                $blankReservation->reservation_type = "";
                $blankReservation->status = "";
                $blankReservation->reservation_player_ids = "";
                $blankReservation->member_ids = "";
                $blankReservation->member_names = "";
                $blankReservation->parent_id = "";
                $allReservationsWithCourses[] = $blankReservation;
            }
        }
        
        return Course::returnReseravtionObjectsArrayFromReservationArray($allReservationsWithCourses);
    }

    public static function getCourseByClubId($course_id, $club_id)
    {
        return self::where('club_id', '=', $club_id)->where('id', '=', $course_id)->first();
    }

    /**
     * count total hours open for a course if reservations array is inflated
     */
    public function countTotalHours()
    {
        if (! is_null($this->reservations)) {
            return count($this->reservations);
        } else {
            return 0;
        }
    }

    /**
     *
     * @param type $reservationsArray            
     * @return reservation objects array by date
     *         converts and returns reservations results from a query on compound_reservations_aggregate view
     *         to reservation objects
     */
    public static function returnReseravtionObjectsArrayFromReservationArray($reservationsArray)
    {
        $reservationsByDate = [];
        if (count($reservationsArray)) {
            $tempDate = 0;
            $tempTimeSlot = "";
            $dateIndex = - 1;
            $timeSlotIndex = 0;
            $reservationIndex = 0;
            
            foreach ($reservationsArray as $reservation) {
                
                // Change course if the id is different
                if ($tempDate != $reservation->reserved_at) {
                    // reset timeslot index on change of course
                    $timeSlotIndex = 0;
                    $tempDate = $reservation->reserved_at;
                    $dateIndex ++;
                    $reservationsByDate[$dateIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->course_id = $reservation->course_id;
                    $dateObject = Carbon::parse($reservation->time_start);
                    $reservationsByDate[$dateIndex]->reserved_at = $reservation->reserved_at;
                    $reservationsByDate[$dateIndex]->dayNumber = $dateObject->day;
                    $reservationsByDate[$dateIndex]->dayName = $dateObject->format('l');
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot = [];
                    $tempTimeSlot = "";
                }
                
                // Change timeslot if different
                if ($tempTimeSlot != $reservation->time_start) {
                    $tempTimeSlot = $reservation->time_start;
                    // reset reservation index on change of time slot
                    $reservationIndex = 0;
                    
                    $timeSlotIndex ++;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->timeSlot = Carbon::parse($reservation->time_start)->format('h:i A');
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations = [];
                }
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex] = new \stdClass();
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->reservation_id = $reservation->reservation_id;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->reservation_type = $reservation->reservation_type;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->status = $reservation->status;
                
                $reservation_player_ids = $reservation->reservation_player_ids !== "" ? explode("||-separation-player-||", $reservation->reservation_player_ids) : [];
                $member_ids = $reservation->member_ids !== "" ? explode("||-separation-player-||", $reservation->member_ids) : [];
                $member_names = $reservation->member_names !== "" ? explode("||-separation-player-||", $reservation->member_names) : [];
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->players = collect([]);
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->guests = 0;
                foreach ($reservation_player_ids as $playerIndex => $reservation_player_id) {
                    
                    if ($member_ids[$playerIndex] == 0) {
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->guests ++;
                    }
                    
                    $reservationPlayerObject = new \stdClass();
                    $reservationPlayerObject->reservation_player_id = trim($reservation_player_ids[$playerIndex]);
                    $reservationPlayerObject->member_id = trim($member_ids[$playerIndex]);
                    $reservationPlayerObject->member_name = trim($member_names[$playerIndex]);
                    
                    if ($reservationPlayerObject->member_id == $reservation->parent_id) {
                        
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->players->prepend($reservationPlayerObject);
                    } else {
                        // bring parent to front
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlot[$timeSlotIndex]->reservations[$reservationIndex]->players->push($reservationPlayerObject);
                    }
                }
                $reservationsByDate[$dateIndex]->reservationsByTimeSlot = array_values($reservationsByDate[$dateIndex]->reservationsByTimeSlot);
                $reservationIndex ++;
            }
        }
        // dd($reservationsByDate);
        return $reservationsByDate;
    }

    public static function createAllReservationsParentObject($clubId, $courseId)
    {
        $reservationsParent = new \stdClass();
        $reservationsParent->club_id = $clubId;
        $reservationsParent->course_id = $courseId;
        $reservationsParent->reservationsByDate = [];
        
        return $reservationsParent;
    }

    public static function createReservationsByDateObject($date)
    {
        $reservationsByDate = new \stdClass();
        $dateObject = Carbon::parse($date);
        $reservationsByDate->reserved_at = $dateObject->toDateString();
        $reservationsByDate->dayNumber = $dateObject->day;
        $reservationsByDate->dayName = $dateObject->format('l');
        $reservationsByDate->reservationsByTimeSlot = [];
        
        return $reservationsByDate;
    }

    public static function createReservationsByTimeSlotsObject($time, $detailed)
    {
        $timeSlot = new \stdClass();
        $timeSlot->timeSlot = $time->format('h:i A');
        if ($detailed) {
            $timeSlot->reservations = [];
            $timeSlot->reservations[0] = new \stdClass();
            $timeSlot->reservations[0]->reservation_id = '';
            $timeSlot->reservations[0]->reservation_type = '';
            $timeSlot->reservations[0]->status = '';
            $timeSlot->reservations[0]->players = [];
            $timeSlot->reservations[0]->guests = 0;
        } else {
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
}
