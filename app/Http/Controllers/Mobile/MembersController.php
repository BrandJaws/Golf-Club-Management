<?php
// use Illuminate;
namespace App\Http\Controllers\Mobile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Member;
use App\Http\Models\Club;
use App\Http\Models\Court;
use Validator;
use App\Http\Models\PushNotification;
use Illuminate\Support\Facades\Hash;
class MembersController extends Controller {
	//use \ImageHandler;
	//use \Notification;
	public function login(Request $request) {
		if (! $request->has ( 'email' )) {
			$this->error = 'login_email_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'password' )) {
			$this->error = 'login_password_not_present';
			return $this->response ();
		}
		if (! $request->has ( 'device_registeration_id' )) {
			$this->error = 'mobile_device_registration_token_missing';
			return $this->response ();
		}
		if (! $request->has ( 'device_type' )) {
			$this->error = 'mobile_device_registration_type_missing';
			return $this->response ();
		}
		if (! in_array ( $request->get ( 'device_type' ), \Config::get ( 'global.deviceType' ) )) {
			$this->error = 'mobile_invalid_device_type';
			return $this->response ();
		}
		$fetchUser = Member::getUserByEmail ( $request->get ( 'email' ) );
		if (is_null ( $fetchUser ) && count ( $fetchUser ) == 0) {
			$this->error = 'invalid_email_address';
			return $this->response ();
		}
		
		if (!Hash::check($request->get ( 'password' ), $fetchUser->password) ) {
			$this->error = 'invalid_password';
			return $this->response ();
		}
		$fetchUser->populate ( [ 
				'device_type' => $request->get ( 'device_type' ),
				'device_registeration_id' => $request->get ( 'device_registeration_id' ) 
		] )->save ();
		$fetchUser->setHidden ( [ 
				'password',
				'salt',
				'deleted_at',
				'created_at',
				'updated_at' 
		] );
		$data = $fetchUser->toArray ();
		$data ['auth_token'] = self::getAccessToken ( $fetchUser );
		$this->response = $data;
		return $this->response ();
	}
	public function forgotPassword(Request $request) {
		$rules = [ 
				'email' => 'required|email|exists:member,email' 
		];
		$validator = Validator::make ( $request->all (), $rules );
		if ($validator->fails ()) {
			$this->validationError = $validator->errors ()->first ();
			return $this->response ();
		}
		
		try {
			\DB::beginTransaction ();
			$member = Member::where ( 'email', '=', $request->get ( 'email' ) )->first ();
			$member->auth_token = self::v4 ();
			$member->save ();
			\Mail::send ( 'emails.forgot', [ 
					'member' => $member 
			], function ($m) use ($member) {
				$m->from ( \Config::get ( 'app.admin_email' ), 'Reset your password for grit' );
				$m->to ( $member->email, $member->name )->subject ( 'Your Reminder!' );
			} );
			\DB::commit ();
			$this->response = 'mobile_forgot_password_email_sent';
		} catch ( Exception $e ) {
			\DB::rollback ();
			\Log::info ( __METHOD__, [ 
					'error' => $e->getMessage () 
			] );
			$this->error = "exception";
		}
		return $this->response ();
	}
	public function changePassword(Request $request) {
		$rules = [ 
				'auth_token' => 'required|exists:member,auth_token' 
		];
		$validator = Validator::make ( $request->all (), $rules );
	}
	public function getClubMembers(Request $request, $club_id) {
		if (( int ) $club_id < 1) {
			$this->error = 'mobile_invalid_club_identifire';
			return $this->response ();
		}
		
		$club = Club::find ( $club_id );
		
		if (! $club instanceof Club) {
			$this->error = 'club_not_found';
			return $this->response ();
		}
                /*
                if(!$request->has('date')){
                    $this->error = "date_time_not_found";
                    return $this->response();
                }else{
                    try{
                        $date = Carbon::parse($request->get('date'));

                    }catch(\Exception $e){
                         $this->error = "date_time_not_found";
                         return $this->response();
                    }

                }
                 * */
             
		$currentPage = $request->has ( 'page' ) ? $request->get ( 'page' ) : 0;
		$perPage = $request->has ( 'perPage' ) ? $request->get ( 'perPage' ) : \Config::get ( 'global.mobile_items_per_page' );
		$search = $request->has ( 'search' ) ? $request->get ( 'search' ) : false;
                $date = $request->has ( 'date' ) ? $request->get ( 'date' ) : false;
                
                $members = (new Member ())->getClubMembers ( $club->id, $currentPage, $perPage,$search ,$date);
		if (count ( $members ) < 1) {
			$this->error = 'no_members_could_be_found';
			return $this->response ();
		}
		$this->response = $members;
		return $this->response ();
	}
	public function show() {
		$member = Auth::user ();
		if (! $member) {
			$this->error = 'mobile_member_not_found';
			return $this->response ();
		}
		$member->setHidden ( [ 
				'password',
				'salt',
				'auth_token',
				'created_at',
				'updated_at',
				'deleted_at' 
		] );
		$this->response = $member;
		return $this->response ();
	}
	public function update(Request $request) {
		$validationRules = [ 
				'firstName' => 'required|string',
				'lastName' => 'required|string',
				'phone' => 'required',
				'password' => 'sometimes|confirmed|min:6',
				'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,svg|max:1024',
				'gender' => 'required|in:MALE,FEMALE',
				'dob' => 'required|date_format:d-m-Y' 
		];
		$validator = Validator::make ( $request->all (), $validationRules );
		if ($validator->fails ()) {
			$this->validationError = $validator->errors ()->first ();
			return $this->response ();
		}
		if ($request->hasFile ( 'profilePic' )) {
			$uploadPath = self::uploadImage ( $request->file ( 'profilePic' ), 'user_profile_path', md5 ( Auth::user ()->id ), true, true );
			Auth::user ()->updateProfileImage ( $uploadPath );
		}
		Auth::user ()->populate ( $request->except ( 'profilePic' ) )->save ();
		
		$this->response = Auth::user ()->setHidden ( [ 
				'password',
				'salt',
				'auth_token',
				'created_at',
				'updated_at',
				'deleted_at' 
		] );
		return $this->response ();
	}
	public function addMemberToFavorites(Request $request) {
		if (! $request->has ( 'player_id' )) {
			$this->error = 'player_id_not_present';
			return $this->response ();
		} else {
			$member_to_be_favorited_id = $request->get ( 'player_id' );
			if ($member_to_be_favorited_id == Auth::user ()->id) {
				$this->error = 'cannot_add_yourself_to_favorites';
				return $this->response ();
			} else {
				
				$member_to_be_favorited = Member::find ( $member_to_be_favorited_id );
				if ($member_to_be_favorited == null) {
					$this->error = 'invalid_player_id';
					return $this->response ();
				}
				$user = Member::find ( Auth::user ()->id );
				$member_already_favorited = $user->favorite_members ()->where ( "id", "=", $member_to_be_favorited_id )->get ()->toArray ();
				
				if ($member_already_favorited != null) {
					$this->error = 'player_already_favorited';
					return $this->response ();
				}
			}
		}
		
		$user->favorite_members ()->attach ( $member_to_be_favorited_id );
		$this->response = "player_added_to_favorites";
		return $this->response ();
	}
	public function removeMemberFromFavorites(Request $request) {
		if (! $request->has ( 'player_id' )) {
			$this->error = 'player_id_not_present';
			return $this->response ();
		} else {
			$member_to_be_removed_id = $request->get ( 'player_id' );
			if ($member_to_be_removed_id == Auth::user ()->id) {
				$this->error = 'cannot_remove_yourself_from_favorites';
				return $this->response ();
			} else {
				$member_to_be_removed = Member::find ( $member_to_be_removed_id );
				if ($member_to_be_removed == null) {
					$this->error = 'invalid_player_id';
					return $this->response ();
				}
				$user = Member::find ( Auth::user ()->id );
				$member_already_favorited = $user->favorite_members ()->where ( "id", "=", $member_to_be_removed_id )->get ()->toArray ();
				if ($member_already_favorited == null) {
					$this->error = 'player_already_not_favorited';
					return $this->response ();
				}
			}
		}
		
		$user->favorite_members ()->detach ( $member_to_be_removed_id );
		$this->response = "player_removed_from_favorites";
		return $this->response ();
	}
	public function getFavoriteMembers() {
		$logged_in_member = Member::find ( Auth::user ()->id );
		$favorited_members = $logged_in_member->favorite_members ()->select ( "id", "email", "firstName", "lastName", "phone", "profilePic" )->get ()->toArray ();
		if ($favorited_members == null) {
			$this->error = "no_favorite_members";
		} else {
			for($x = 0; $x < count ( $favorited_members ); $x ++) {
				unset ( $favorited_members [$x]->pivot );
			}
			$this->response = $favorited_members;
		}
		
		return $this->response ();
	}
	public function addCourtToFavorites(Request $request) {
		if (! $request->has ( 'court_id' )) {
			$this->error = 'court_id_not_present';
			return $this->response ();
		} else {
			$court_to_be_favorited_id = $request->get ( 'court_id' );
			$court_to_be_favorited = Court::find ( $court_to_be_favorited_id );
			if ($court_to_be_favorited == null) {
				$this->error = 'invalid_court_id';
				return $this->response ();
			}
			$user = Member::find ( Auth::user ()->id );
			$court_already_favorited = $user->favorite_courts ()->where ( "id", "=", $court_to_be_favorited_id )->get ()->toArray ();
			
			if ($court_already_favorited != null) {
				$this->error = 'court_already_favorited';
				return $this->response ();
			}
		}
		
		$user->favorite_courts ()->attach ( $court_to_be_favorited_id );
		$this->response = "court_added_to_favorites";
		return $this->response ();
	}
	public function removeCourtFromFavorites(Request $request) {
		if (! $request->has ( 'court_id' )) {
			$this->error = 'court_id_not_present';
			return $this->response ();
		} else {
			$court_to_be_removed_id = $request->get ( 'court_id' );
			$court_to_be_removed = Member::find ( $court_to_be_removed_id );
			if ($court_to_be_removed == null) {
				$this->error = 'invalid_court_id';
				return $this->response ();
			}
			
			$user = Member::find ( Auth::user ()->id );
			$court_already_favorited = $user->favorite_courts ()->where ( "id", "=", $court_to_be_removed_id )->get ()->toArray ();
			
			if ($court_already_favorited == null) {
				$this->error = 'court_already_not_favorited';
				return $this->response ();
			}
		}
		
		$user->favorite_courts ()->detach ( $court_to_be_removed_id );
		$this->response = "court_removed_from_favorites";
		return $this->response ();
	}
	public function getCourtsListForMember() {
		$courtsForMember = Member::getCourtsListForMemberById ( Auth::user ()->id );
		
		if ($courtsForMember == null) {
			
			$this->error = "no_courts_found";
		} else {
			
			$this->response = $courtsForMember;
		}
		
		return $this->response ();
	}
	public function getReservationsForMember() {
		$memberId = Auth::user ()->id;
		$reservationsForMember = Member::getAllReservationsForAMemberById ( $memberId );
		if ($reservationsForMember != null) {
			$this->response = $reservationsForMember;
		} else {
			$this->error = "no_reservations_found_for_member";
		}
		
		return $this->response ();
	}
	public function sendTestNotification(Request $request) {
		$deviceToken = Auth::user ()->device_registeration_id;
		$deviceType = Auth::user ()->device_type;
		if (! $request->has ( 'message' )) {
			$this->error = "notification_message_missing";
			return $this->response ();
		}
                 $useCase = \Config::get ( 'global.pushNotificationsUseCases.add_more_players_prompt_on_decline' );
                $title = "Please Add More Players";
                $body = trans('message.pushNotificationMessageBodies.add_more_players_prompt');
                
                 $this->sendNotification($body, 
                                            "435383529a169b09ed1fbfbcdfee0d938b0bbffec999020e2a265eac40b8c939", 
                                            "Iphone",
                                            self::getIOSOptionsObject(
                                                $useCase,
                                                $title,
                                                $body,
                                                ['tennis_reservation_id'=> 1 ,
                                                 'last_player_declined_id'=> 1 ,
                                                 'last_player_declined_name'=>"Player Name",
                                                 'aggregate_declined_player_ids'=>json_encode([1,2,3])
                                                ]
                                            ),
                                            Auth::user()->id,
                                            41);
                return "ABC";
		$title = "Title of message";
                $options = [];
                $options['alert']['use_case'] = "test_use_case";
                $options['alert']['title'] = $title;
                $options['alert']['body'] = "Body of Message";
              
		$this->sendNotification ( $title, $deviceToken, $deviceType,$options,Auth::user()->id,41 );
	}
        
        public function getPushNotificationsForMemberById(Request $request) {
		
		$member = Auth::user();
		
		$currentPage = $request->has ( 'page' ) ? $request->get ( 'page' ) : 0;
		$perPage = $request->has ( 'perPage' ) ? $request->get ( 'perPage' ) : \Config::get ( 'global.mobile_items_per_page' );
		
                $notifications = $member->getPushNotificationsForMember ($currentPage, $perPage );
		if (count($notifications) < 1) {
			$this->error = 'no_notifications_found';
			return $this->response ();
		}
		$this->response = $notifications;
		return $this->response ();
	}
        
        public function deletePushNotificationForMemberById($notification_id) {
		
		$notification = PushNotification::find($notification_id);
                if(!$notification){
                    $this->error = 'no_notifications_found';
                    return $this->response ();
                }
                $notification->delete();
                $this->response = "notification_deleted_successfuly";
		return $this->response ();
		
	}
        
        public function deleteAllPushNotificationForMember(){
            $memberId = Auth::user()->id;
            try{
                 PushNotification::where('member_id',$memberId)->delete();
                 $this->response = "notification_deleted_successfuly";
                 return $this->response();
            }catch(\Exception $e){
                //$this->error = "notification_deleted_successfuly";
              
            }
            
            
           
            
        }
       
}
