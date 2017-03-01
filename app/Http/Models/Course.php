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

    public $timestamps = false;
    protected $table = 'course';
    protected $fillable = [
        'name',
        'club_id',
        'openTime',
        'closeTime',
        'bookingInterval',
        'bookingDuration',
        'status'
    ];

    public $reservations;

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
     * To get reservations for all courses with players and other details: detailed = true
     * To get reservations for all courses with booking indicators for mobile service: detailed = false
     */
    public static function getReservationsForAllCourses($date = false, $detailed = true)
    {
        $date = ! $date ? Carbon::today()->toDateString() : $date;
        $coursesListRaw = Course::courseList();
        $allCoursesWithReservations = Course::getAllReservationsAtACourseForADateRange($date);
        $coursesForReservations = [];
        foreach ($coursesListRaw as $course) {
            $foundCourseWithExistingReservations = false;
            foreach ($allCoursesWithReservations as $courseWithReservation) {
                if ($course->id == $courseWithReservation->course_id) {
                    $foundCourseWithExistingReservations = true;
                    if ($detailed) {
                        $coursesForReservations[] = Course::returnCourseWithCompleteReservationsObject($course, Course::generateReservationsTimeSlotsComplete($course, $courseWithReservation->timeSlots));
                    } else {
                        $coursesForReservations[] = Course::returnCourseWithCompleteReservationsObject($course, Course::generateReservationsTimeSlotsComplete($course, $courseWithReservation->timeSlots, false));
                    }
                    
                    break;
                }
            }
            
            if (! $foundCourseWithExistingReservations) {
                if ($detailed) {
                    $coursesForReservations[] = Course::returnCourseWithCompleteReservationsObject($course, Course::generateReservationsTimeSlotsComplete($course));
                } else {
                    $coursesForReservations[] = Course::returnCourseWithCompleteReservationsObject($course, Course::generateReservationsTimeSlotsComplete($course, [], false));
                }
            }
        }
        
        if ($detailed) {
            $coursesForReservationsWithDate = [
                "date" => Carbon::parse($date)->format('m/d/Y'),
                "courses" => $coursesForReservations
            ];
            return $coursesForReservationsWithDate;
        } else {
            return $coursesForReservations;
        }
        
        // dd($coursesForReservations);
    }

    public static function generateReservationsTimeSlotsComplete($courseForReservation, $existingReservationTimeslotsForCourse = [], $detailed = true)
    {
        $open_time = Carbon::parse($courseForReservation->openTime);
        $close_time = Carbon::parse($courseForReservation->closeTime);
        $reservations = [];
        $open_time->diffFiltered(CarbonInterval::minute($courseForReservation->bookingDuration), function (Carbon $date) use (&$reservations, $detailed, $existingReservationTimeslotsForCourse) {
            $reservation = new \stdClass();
            $reservation->timeSlot = $date->format('h:i A');
            
            // Iterate through existing reservations in the course to see if there is an existing reservation on
            // this timeslot if so replace its reservations with existing ones else make an empty array after
            // the iteration is complete
            $foundExistingReservationOnTimeSlot = false;
            
            foreach ($existingReservationTimeslotsForCourse as $existingReservationTimeslot) {
                // dd($existingReservationTimeslotsForCourse);
                
                if (Carbon::parse($existingReservationTimeslot->timeSlot)->format('h:i A') == $reservation->timeSlot) {
                    if ($detailed) {
                        $reservation->reservations = $existingReservationTimeslot->reservations;
                    } else {
                        $reservation->isReserved = true;
                        $reservation->players = [];
                        $populatedPlayers = false;
                        
                        foreach ($existingReservationTimeslot->reservations as $reservationOnTimeSlot) {
                            
                            foreach ($reservationOnTimeSlot->reservationPlayers as $reservationPlayer) {
                                if ($reservationPlayer->player_id == Auth::user()->id) {
                                    $reservation->timeEnd = Carbon::parse($reservationOnTimeSlot->time_end)->format('h:i A');
                                    foreach ($reservationOnTimeSlot->reservationPlayers as $reservationPlayer) {
                                        
                                        $reservation->players[] = $reservationPlayer->player_name;
                                    }
                                    $populatedPlayers = true;
                                }
                                if ($populatedPlayers) {
                                    break;
                                }
                            }
                            
                            if ($populatedPlayers) {
                                break;
                            }
                        }
                    }
                    $foundExistingReservationOnTimeSlot = true;
                    break;
                }
            }
            
            if (! $foundExistingReservationOnTimeSlot) {
                if ($detailed) {
                    
                    $reservation->reservations = [];
                } else {
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
    public static function getAllReservationsAtACourseForADateRange($courseId, $dateStart, $dateEnd)
    {
        // dd(RoutineReservation::class);
        // First Set of Data for Routine Reservations
        $query = " SELECT ";
        $query .= " courses.id as course_id, ";
        $query .= " courses.club_id as club_id, ";
        $query .= " courses.name as course_name, ";
        // $query .= " courses.openTime, ";
        // $query .= " courses.closeTime, ";
        // $query .= " courses.bookingDuration, ";
        $query .= " routine_reservations.id as reservation_id, ";
        $query .= " ANY_VALUE(reservation_time_slots.reservation_type) as reservation_type, ";
        $query .= " routine_reservations.parent_id, ";
        $query .= " reservation_time_slots.time_start as time_start, ";
        // $query .= " tennis_reservation.time_end, ";
        $query .= " DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= " GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= " GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= " GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= " routine_reservations.status ";
        $query .= " FROM ";
        $query .= " courses ";
        // $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = courses.id ";
        // Start : To Join reservation type RoutineReservation
        $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = courses.id ";
        $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'" . RoutineReservation::class . "') ";
        $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'" . RoutineReservation::class . "') ";
        
        $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
        // End : To Join a reservation type RoutineReservation
        $query .= " WHERE ";
        $query .= " courses.id = ? ";
        $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
        $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
        $query .= " AND courses.club_id = ? ";
        $query .= " GROUP BY routine_reservations.id,reservation_time_slots.time_start ";
        
        // START:To add other reservation types results in the future
        
        // $query .= " UNION ALL ";
        //
        // $query .= " SELECT ";
        // $query .= " courses.id as course_id, ";
        // $query .= " courses.club_id as club_id, ";
        // $query .= " courses.name as course_name, ";
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
        // $query .= " courses ";
        // $query .= " LEFT JOIN routine_reservations ON routine_reservations.course_id = courses.id ";
        // $query .= " LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND STRCMP(reservation_time_slots.reservation_type,'".RoutineReservation::class."') ";
        // $query .= " LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND STRCMP(reservation_players.reservation_type,'".RoutineReservation::class."') ";
        // $query .= " LEFT JOIN member ON reservation_players.member_id = member.id ";
        // $query .= " WHERE ";
        // $query .= " courses.id = ? ";
        // $query .= " AND DATE(reservation_time_slots.time_start) >= DATE(?) ";
        // $query .= " AND DATE(reservation_time_slots.time_start) <= DATE(?) ";
        // $query .= " AND courses.club_id = ? ";
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
        
        $reservationsByDate = [];
        if (count($allReservationsWithCourses)) {
            $tempDate = 0;
            $tempTimeSlot = "";
            $dateIndex = - 1;
            $timeSlotIndex = 0;
            $reservationIndex = 0;
            $reservationsByDate = [];
            
            foreach ($allReservationsWithCourses as $reservation) {
                
                // Change course if the id is different
                if ($tempDate != $reservation->reserved_at) {
                    // reset timeslot index on change of course
                    $timeSlotIndex = 0;
                    $tempDate = $reservation->reserved_at;
                    $dateIndex ++;
                    $reservationsByDate[$dateIndex] = new \stdClass();
                    $dateObject = Carbon::parse($reservation->time_start);
                    $reservationsByDate[$dateIndex]->reserved_at = $dateObject->toDateString();
                    $reservationsByDate[$dateIndex]->dayNumber = $dateObject->day;
                    $reservationsByDate[$dateIndex]->dayName = $dateObject->format('l');
                    // $reservationsByDate[$dateIndex]->course_id = $reservation->course_id;
                    // $reservationsByDate[$dateIndex]->openTime = $reservation->openTime;
                    // $reservationsByDate[$dateIndex]->closeTime = $reservation->closeTime;
                    // $reservationsByDate[$dateIndex]->bookingDuration = $reservation->bookingDuration;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots = [];
                    $tempTimeSlot = "";
                }
                
                // Change timeslot if different
                if ($tempTimeSlot != $reservation->time_start) {
                    $tempTimeSlot = $reservation->time_start;
                    // reset reservation index on change of time slot
                    $reservationIndex = 0;
                    
                    $timeSlotIndex ++;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex] = new \stdClass();
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->timeSlot = $reservation->time_start;
                    $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations = [];
                }
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex] = new \stdClass();
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reservation_id = $reservation->reservation_id;
                // $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_start = $reservation->time_start;
                // $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->time_end = $reservation->time_end;
                // $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->reserved_at = $reservation->reserved_at;
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->status = $reservation->status;
                
                $reservation_player_ids = explode("||-separation-player-||", $reservation->reservation_player_ids);
                $member_ids = explode("||-separation-player-||", $reservation->member_ids);
                $member_names = explode("||-separation-player-||", $reservation->member_names);
                
                $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players = collect([]);
                
                foreach ($reservation_player_ids as $playerIndex => $reservation_player_id) {
                    $reservationPlayerObject = new \stdClass();
                    $reservationPlayerObject->reservation_player_id = trim($reservation_player_ids[$playerIndex]);
                    $reservationPlayerObject->member_id = trim($member_ids[$playerIndex]);
                    $reservationPlayerObject->member_name = trim($member_names[$playerIndex]);
                    
                    if ($reservationPlayerObject->member_id == $reservation->parent_id) {
                        
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players->prepend($reservationPlayerObject);
                    } else {
                        // bring parent to front
                        $reservationsByDate[$dateIndex]->reservationsByTimeSlots[$timeSlotIndex]->reservations[$reservationIndex]->players->push($reservationPlayerObject);
                    }
                }
                
                $reservationIndex ++;
            }
        }
        dd($reservationsByDate);
        return $reservationsByDate;
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
     * @param \App\Http\Models\Course $course            
     * @param array $reservationsArray            
     */
    public static function returnCourseWithCompleteReservationsObject(Course $course, $reservationsArray)
    {
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
