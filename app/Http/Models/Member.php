<?php
namespace App\Http\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Member extends Authenticatable
{
    use Notifiable;

    protected $table = 'member';

    protected $fillable = [
        'club_id',
        'firstName',
        'lastName',
        'email',
        'phone',
        'profilePic',
        'password',
        'salt',
        'gender',
        'dob',
        'device_registeration_id',
        'device_type',
        'status'
    ];

    protected $gaurded = [
        'profilePic',
        'password',
        'main_member_id'
    ];

    public static function getUserByEmail($email)
    {
        return self::where('email', '=', $email)->first();
    }

    public static function getById($id)
    {
        return self::find($id);
    }

    public function main_member(){
        return $this->belongsTo('App\Http\Models\Member');
    }

    /**
     * Relation with club
     * @usage Mobile, Web
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function club()
    {
        return $this->belongsTo('App\Http\Models\Club');
    }

    public function friends() {
        return $this->belongsToMany ( "App\Http\Models\Member", "friends_member_member", "member_id", "friend_member_id" )->withTimestamps ();
    }

    public function groups() {
        return $this->hasMany ( "App\Http\Models\Group");

    }
    public function push_notifications() {
        return $this->hasMany ( "App\Http\Models\PushNotification" );
    }

    public function populate($data = [])
    {
        if (array_key_exists('firstName', $data)) {
            $this->firstName = $data['firstName'];
        }
        if (array_key_exists('lastName', $data)) {
            $this->lastName = $data['lastName'];
        }
        if (array_key_exists('email', $data)) {
            $this->email = $data['email'];
        }
        if (array_key_exists('phone', $data)) {
            $this->phone = $data['phone'];
        }
        if (array_key_exists('profilePic', $data)) {
            $this->profilePic = $data['profilePic'];
        }
        if (array_key_exists('gender', $data)) {
            $this->gender = $data['gender'];
        }
        if (array_key_exists('dob', $data)) {
            $this->dob = Carbon::parse($data['dob'])->toDateString();
        }
        if (array_key_exists('device_registeration_id', $data)) {
            $this->device_registeration_id = $data['device_registeration_id'];
        }
        if (array_key_exists('device_type', $data)) {
            $this->device_type = $data['device_type'];
        }
        if (array_key_exists('password', $data) && trim($data['password']) != "" ) {

            $this->password = bcrypt($data['password']);
        }
        if (array_key_exists('status', $data)) {
            $this->status = $data['status'];
        }
        return $this;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtoupper($value);
    }

    public function getStatusNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Search club Members with in a club
     * @usage Mobile, Web
     *
     * @param unknown $clubId            
     * @param unknown $search            
     */
    public function listSearchClubMembers($clubId, $search = null)
    {
        return self::where('club_id', '=', $clubId)->where(function ($query) use ($search) {
            if ($search !== false) {
                $query->where('member.firstName', 'Like', '%' . $search . '%')
                    ->orWhere('member.lastName', 'Like', '%' . $search . '%');
            }
        })
            ->take(15)
            ->get([
            'member.id as member_id',
            \DB::raw("CONCAT(firstName,' ', lastName) AS member_name")
        ]);
    }

    /**
     * List Get club members
     * @usage Mobile
     *
     * @param unknown $clubId            
     * @param unknown $currentPage            
     * @param unknown $perPage            
     * @return unknown
     */
    public function getClubMembers($clubId, $currentPage, $perPage)
    {
        $userId = \Illuminate\Support\Facades\Auth::user()->id;
        $members = self::where('club_id', '=', $clubId)->where('id', '<>', $userId)->paginate($perPage, [
            'member.id',
            'member.email',
            'member.firstName',
            'member.lastName',
            'member.phone',
            'member.profilePic',
            //DB::raw("(if( (select count(*) from favorite_member_member where member_id = " . $userId . " and favorite_member_id = member.id) > 0, true,  false) ) as isFavorite ")
        ], 'page', $currentPage);
        
        foreach ($members as $index => $member) {
            if ($member->isFavorite === 1) {
                $members[$index]->isFavorite = true;
            } else {
                $members[$index]->isFavorite = false;
            }
        }
        
        return $members;
    }

    /**
     * Get paginated list of members for logedin club
     * @usage Web
     *
     * @param unknown $clubId            
     * @param unknown $currentPage            
     * @param unknown $perPage            
     */
    public function listClubMembersPaginated($clubId, $currentPage, $perPage, $searchTerm = false, $memberId = false)
    {

        return $this->where('club_id', '=', $clubId)
      ->where(function($query) use($memberId){
          if($memberId){
              $query->where('member.id','<>',$memberId);
          }
      })
      ->where(function ($query) use ($searchTerm) {
          if ($searchTerm) {
              $query->where('member.firstName', 'like', "%$searchTerm%");
              $query->orWhere('member.lastName', 'like', "%$searchTerm%");
              $query->orWhere('member.email', 'like', "%$searchTerm%");
          }
      })
      ->select('member.id as id', 'member.firstName', 'member.lastName', 'member.email', 'member.phone', 'member.gender','member.profilePic', $memberId !== false ? DB::raw("IF((SELECT COUNT(*) FROM friends_member_member WHERE member_id = $memberId AND friend_member_id = member.id)>0,1,0)  AS isFriend") : DB::raw("0  AS isFriend"))
      ->orderby('member.created_at', 'DESC')
      ->paginate($perPage, array(
        '*'
      ), 'current_page', $currentPage);
    }

    /**
     * Get total members with in a club
     * @usage Mobile, Web
     *
     * @param unknown $clubId            
     */
    public static function countClubMembers($clubId)
    {
        return self::where('club_id', '=', $clubId)->count();
    }

    public function updateProfileImage($profileImage)
    {
        $this->forceFill([
            'profilePic' => $profileImage
        ])->save();
    }

    /*
     * public function favorite_members() {
     * return $this->belongsToMany ( "App\Http\Models\Member", "favorite_member_member", "member_id", "favorite_member_id" )->withTimestamps ();
     * }
     * public function favorite_courts() {
     * return $this->belongsToMany ( "App\Http\Models\Court", "favorite_member_court", "member_id", "court_id" )->withTimestamps ();
     * }
     */
    public static function getCourtsListForMemberById($memberId)
    {
        $querySetCourtId = "SET @courtId = 0 ";
        $querySetPlayerId = "SET @playerId = :playerId ";
        
        $query = "SELECT ";
        $query .= "@courtId :=id as id, ";
        $query .= "openTime, ";
        $query .= "closeTime, ";
        $query .= "name, ";
        $query .= "bookingDuration, ";
        $query .= "ballMachineAvailable, ";
        $query .= "environment, ";
        $query .= "(if(";
        $query .= "     (select count(*) from favorite_member_court ";
        $query .= "      where member_id = @playerId and court_id = @courtId) > 0,";
        $query .= " true, ";
        $query .= " false) ";
        $query .= ") as isFavorite ";
        $query .= "FROM court ";
        $query .= "WHERE club_id = (SELECT club_id FROM member where id = @playerId) ";
        DB::statement(DB::raw($querySetCourtId));
        DB::statement(DB::raw($querySetPlayerId), [
            "playerId" => $memberId
        ]);
        $courtsList = DB::select(DB::raw($query));
        
        for ($x = 0; $x < count($courtsList); $x ++) {
            if ($courtsList[$x]->isFavorite) {
                $courtsList[$x]->isFavorite = true;
            } else {
                $courtsList[$x]->isFavorite = false;
            }
            $courtsList[$x]->openTime = Carbon::parse($courtsList[$x]->openTime)->format('h:i A');
            $courtsList[$x]->closeTime = Carbon::parse($courtsList[$x]->closeTime)->format('h:i A');
        }
        return $courtsList;
    }

    public static function getAllReservationsForAMemberById($memberId)
    {
        $querySetMemberId = "SET @memberId = :memberId ";

        $query  = "     SELECT ";
        $query .= "     course.club_id as club_id, ";
        $query .= "     course.id as course_id, ";
        $query .= "     course.name as course_name, ";
        $query .= "     course.tees as tees, ";
        $query .= "     (@reservation_id := routine_reservations.id) as reservation_id, ";
        $query .= "     (@reservation_type := reservation_time_slots.reservation_type) as reservation_type, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.parent_id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as parent_ids, ";
        $query .= "     reservation_time_slots.time_start as date_time_start, ";
        $query .= "     TIME(reservation_time_slots.time_start) as time_start, ";
        $query .= "     DATE(reservation_time_slots.time_start) as reserved_at, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_player_ids, ";
        $query .= "     GROUP_CONCAT(IFNULL(member.id,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as member_ids, ";
        $query .= "     GROUP_CONCAT(IF(CONCAT_WS(' ', member.firstName, member.lastName) <> ' ',CONCAT_WS(' ', member.firstName, member.lastName),'Guest') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_names, ";
        $query .= "     GROUP_CONCAT(IFNULL( member.profilePic,' ') ORDER BY reservation_players.id ASC SEPARATOR '||-separation-player-||' ) as member_profile_pics, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.reservation_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as reservation_statuses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.response_status,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as response_statuses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.comingOnTime,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as comingOnTime_responses, ";
        $query .= "     GROUP_CONCAT(IFNULL(reservation_players.process_type,' ') ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as processTypes, ";
        $query .= "     routine_reservations.game_status, ";
        $query .= "     GROUP_CONCAT(IF(checkins_for_clubEntry.action IS NULL , 0 , 1) ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as club_entries, ";
        $query .= "     GROUP_CONCAT(IF(checkins_for_gameEntry.action IS NULL , 0 , 1) ORDER BY reservation_players.id SEPARATOR '||-separation-player-||') as game_entries, ";
        $query .= "     (IF((SELECT COUNT(*) FROM score_cards WHERE reservation_id = @reservation_id AND reservation_type = @reservation_type AND player_member_id = @memberId) > 0, true, false)) as score_card_created, ";
        $query .= "     (IF((SELECT COUNT(*) FROM score_cards WHERE reservation_id = @reservation_id AND reservation_type = @reservation_type AND player_member_id = @memberId AND manager_member_id = @memberId) > 0, true, false)) as scorecard_managed_by_self ";
        $query .= "     FROM ";
        $query .= "     routine_reservations ";
        $query .= "     LEFT JOIN course ON routine_reservations.course_id = course.id ";
        $query .= "     LEFT JOIN reservation_time_slots ON reservation_time_slots.reservation_id = routine_reservations.id AND reservation_time_slots.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN reservation_players ON reservation_players.reservation_id = routine_reservations.id AND reservation_players.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN member ON reservation_players.member_id = member.id ";
        $query .= "     LEFT JOIN checkins as checkins_for_clubEntry ON checkins_for_clubEntry.action = 'CLUB ENTRY' AND checkins_for_clubEntry.member_id = member.id AND checkins_for_clubEntry.reservation_id = routine_reservations.id AND checkins_for_clubEntry.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     LEFT JOIN checkins as checkins_for_gameEntry ON checkins_for_gameEntry.action = 'GAME ENTRY' AND checkins_for_gameEntry.member_id = member.id AND checkins_for_gameEntry.reservation_id = routine_reservations.id AND checkins_for_gameEntry.reservation_type = '".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     WHERE ";
        $query .= "     routine_reservations.id IN ( ";
        $query .= "      SELECT DISTINCT(reservation_id) FROM reservation_players  ";
        $query .= "      WHERE member_id = @memberId AND reservation_type='".addslashes("App\\Http\\Models\\RoutineReservation")."' ";
        $query .= "     )";
        $query .= "     AND reservation_time_slots.time_start >= '".Carbon::today()->toDateString()."'";
        $query .= "     GROUP BY course.id,course.club_id,course.name,routine_reservations.id,reservation_time_slots.time_start,reservation_time_slots.reservation_type";



        DB::statement(DB::raw($querySetMemberId), [
            "memberId" => $memberId
        ]);
        $reservations = DB::select(DB::raw($query));
  
        return Course::returnReseravtionObjectsArrayFromReservationArray($reservations,true);
    }

    public function getPushNotificationsForMember($currentPage, $perPage) {
        $notifications = $this->push_notifications ()->orderBy("id","desc")->paginate ( $perPage, [
            'id',
            'messageBody'
        ], 'page', $currentPage );

        foreach ( $notifications as $index => $notification ) {

            $notifications [$index] ["messageBody"] = json_decode ( $notification ["messageBody"] );
        }

        return $notifications;
    }
}
