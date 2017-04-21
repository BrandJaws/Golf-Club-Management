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
        if (array_key_exists('password', $data)) {
            $this->password = Hash::make($data['password']);
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
        
        $query = " SELECT ";
        $query .= " tennis_reservation.id as tennis_reservation_id, ";
        $query .= " court.name as court_name, ";
        $query .= " tennis_reservation.time_start, ";
        $query .= " tennis_reservation.time_end, ";
        $query .= " GROUP_CONCAT(CONCAT_WS(' ', firstName, lastName)) as players ";
        $query .= " FROM ";
        $query .= " member ";
        $query .= " LEFT JOIN tennis_reservation_player ON tennis_reservation_player.player_id = member.id ";
        $query .= " LEFT JOIN tennis_reservation ON tennis_reservation_player.tennis_reservation_id = tennis_reservation.id ";
        $query .= " LEFT JOIN court ON court.id = tennis_reservation.court_id ";
        $query .= " WHERE tennis_reservation_id in ( ";
        $query .= "      SELECT DISTINCT(tennis_reservation_id) FROM tennis_reservation_player  ";
        $query .= "      WHERE player_id = @memberId ";
        $query .= " ) AND tennis_reservation_player.player_id <> @memberId GROUP BY tennis_reservation.id ORDER BY tennis_reservation.id ";
        $query .= " ";
        
        DB::statement(DB::raw($querySetMemberId), [
            "memberId" => $memberId
        ]);
        $reservations = DB::select(DB::raw($query));
        
        foreach ($reservations as $index => $reservation) {
            $reservations[$index]->date = Carbon::Parse($reservation->time_start)->toDateString();
            $reservations[$index]->time_start = Carbon::Parse($reservation->time_start)->format('h:i A');
            $reservations[$index]->time_end = Carbon::Parse($reservation->time_end)->format('h:i A');
            $reservations[$index]->players = explode(",", $reservations[$index]->players);
        }
        
        return $reservations;
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
