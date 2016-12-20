<?php

namespace App\Http\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class Member extends Model implements \Illuminate\Contracts\Auth\Authenticatable {
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
			'device_type' 
	];
	public static function getUserByEmail($email) {
		return self::where ( 'email', '=', $email )->first ();
	}
	public static function getById($id) {
		return self::find ( $id );
	}
	public function club() {
		return $this->belongsTo ( 'App\Http\Models\Club' );
	}
	public function populate($data = []) {
		if (array_key_exists ( 'firstName', $data )) {
			$this->firstName = $data ['firstName'];
		}
		if (array_key_exists ( 'lastName', $data )) {
			$this->lastName = $data ['lastName'];
		}
		if (array_key_exists ( 'email', $data )) {
			$this->email = $data ['email'];
		}
		if (array_key_exists ( 'phone', $data )) {
			$this->phone = $data ['phone'];
		}
		if (array_key_exists ( 'profilePic', $data )) {
			$this->profilePic = $data ['profilePic'];
		}
		if (array_key_exists ( 'gender', $data )) {
			$this->gender = $data ['gender'];
		}
		if (array_key_exists ( 'dob', $data )) {
			$this->dob = Carbon::parse ( $data ['dob'] )->toDateString ();
		}
		if (array_key_exists ( 'device_registeration_id', $data )) {
			$this->device_registeration_id = $data ['device_registeration_id'];
		}
		if (array_key_exists ( 'device_type', $data )) {
			$this->device_type = $data ['device_type'];
		}
		if (array_key_exists ( 'password', $data )) {
			if (is_null ( $this->salt )) {
				$this->salt = self::generateSalt ();
			}
			$this->password = crypt ( $data ['password'], $this->salt );
		}
		return $this;
	}
	public static function generateSalt() {
		$cost = 10;
		$salt = strtr ( base64_encode ( mcrypt_create_iv ( 16, MCRYPT_DEV_URANDOM ) ), '+', '.' );
		$salt = sprintf ( "$2a$%02d$", $cost ) . $salt;
		return $salt;
	}
	public function listClubMembers($clubId, $search = null) {
		return self::where ( 'club_id', '=', $clubId )->where ( function ($query) use ($search) {
			if ($search !== false) {
				$query->where ( 'member.firstName', 'Like', '%' . $search . '%' )->orWhere ( 'member.lastName', 'Like', '%' . $search . '%' );
			}
		} )->get ( [ 
				'member.id',
				\DB::raw ( "CONCAT(firstName,' ', lastName) AS name" ) 
		]
		 );
	}
	public function getClubMembers($clubId, $currentPage, $perPage){
           
                $userId = \Illuminate\Support\Facades\Auth::user()->id;
		$members= self::where ( 'club_id', '=', $clubId )
                        ->where ( 'id', '<>', $userId )
                        ->paginate ( $perPage, [
				'member.id',
				'member.email',
				'member.firstName',
				'member.lastName',
				'member.phone',
				'member.profilePic',
                                DB::raw("(if( (select count(*) from favorite_member_member where member_id = ".$userId." and favorite_member_id = member.id) > 0, true,  false) ) as isFavorite ")
		], 'page', $currentPage );
                
                foreach($members as $index=>$member){
                    if($member->isFavorite === 1){
                        $members[$index]->isFavorite = true;
                    }else{
                        $members[$index]->isFavorite = false;
                    }
                }
                
              return $members;
	}
	public static function countClubMembers($clubId){
		return self::where('club_id','=',$clubId)->count();
	}
	public function getAuthIdentifier() {
	}
	public function getAuthPassword() {
	}
	public function getRememberToken() {
	}
	public function setRememberToken($value) {
	}
	public function getRememberTokenName() {
	}
	public function updateProfileImage($profileImage) {
		$this->forceFill ( [ 
				'profilePic' => $profileImage 
		] )->save ();
	}
        
        public function tennis_reservation_players() {
		return $this->hasMany ( 'App\Http\Models\TennisReservationPlayer' );
	}
        
        public function favorite_members(){
            return $this->belongsToMany("App\Http\Models\Member",
                                        "favorite_member_member",
                                        "member_id",
                                        "favorite_member_id")
                        ->withTimestamps();
        }
        
        public function favorite_courts(){
            return $this->belongsToMany("App\Http\Models\Court",
                                        "favorite_member_court",
                                        "member_id",
                                        "court_id")
                        ->withTimestamps();
        }
        
        public static function getCourtsListForMemberById($memberId){
            
            $querySetCourtId   = "SET @courtId = 0 ";
            $querySetPlayerId  = "SET @playerId = :playerId ";
            
            $query  = "SELECT "; 
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
            DB::statement(DB::raw($querySetPlayerId),["playerId"=>$memberId]);
            $courtsList = DB::select(DB::raw($query));
            
            for($x=0; $x < count($courtsList); $x++){
                if($courtsList[$x]->isFavorite){
                    $courtsList[$x]->isFavorite = true;
                }else{
                    $courtsList[$x]->isFavorite = false;
                }
                $courtsList[$x]->openTime = Carbon::parse($courtsList[$x]->openTime)->format('h:i A');
                $courtsList[$x]->closeTime = Carbon::parse($courtsList[$x]->closeTime)->format('h:i A');
            }
            return $courtsList;
            
        }
        
        public static function getAllReservationsForAMemberById($memberId){
            
            $querySetMemberId   = "SET @memberId = :memberId ";
         
            $query  = " SELECT "; 
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
           
            DB::statement(DB::raw($querySetMemberId),["memberId"=>$memberId]);
            $reservations = DB::select(DB::raw($query));
            
            foreach($reservations as $index=>$reservation){
                $reservations[$index]->date = Carbon::Parse($reservation->time_start)->toDateString();
                $reservations[$index]->time_start = Carbon::Parse($reservation->time_start)->format('h:i A');
                $reservations[$index]->time_end = Carbon::Parse($reservation->time_end)->format('h:i A');
                $reservations[$index]->players = explode(",",$reservations[$index]->players);
            }
           
            return $reservations;
        }
        
        
}
