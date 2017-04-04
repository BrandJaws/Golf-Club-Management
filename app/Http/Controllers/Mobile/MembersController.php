<?php
// use Illuminate;
namespace App\Http\Controllers\Mobile;

use App\Http\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Member;
use App\Http\Models\Club;
use App\Http\Models\Court;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Models\PushNotification;
use Illuminate\Support\Facades\Hash;
class MembersController extends Controller {
	use \ImageHandler;
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

		$currentPage = $request->has ( 'page' ) ? $request->get ( 'page' ) : 0;
		$perPage = $request->has ( 'perPage' ) ? $request->get ( 'perPage' ) : \Config::get ( 'global.mobile_items_per_page' );
		$search = $request->has ( 'search' ) ? $request->get ( 'search' ) : false;

                
                $members = (new Member ())->listClubMembersPaginated ( $club->id, $currentPage, $perPage,$search );
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

	public function addMemberToFriends(Request $request) {
		if (! $request->has ( 'player_id' )) {
			$this->error = 'player_id_not_present';
			return $this->response ();
		} else {
			$member_to_be_friended_id = $request->get ( 'player_id' );
			if ($member_to_be_friended_id == Auth::user ()->id) {
				$this->error = 'cannot_add_yourself_to_favorites';
				return $this->response ();
			} else {

				$member_to_be_friended = Member::find ( $member_to_be_friended_id );
				if ($member_to_be_friended == null) {
					$this->error = 'invalid_player_id';
					return $this->response ();
				}
				$user = Member::find ( Auth::user ()->id );
				$member_already_friended = $user->friends ()->where ( "id", "=", $member_to_be_friended_id )->get ()->toArray ();

				if ($member_already_friended != null) {
					$this->error = 'player_already_favorited';
					return $this->response ();
				}
			}
		}

		$user->friends ()->attach ( $member_to_be_friended_id );
		$this->response = "player_added_to_favorites";
		return $this->response ();
	}
	public function removeMemberFromFriends(Request $request) {
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
				$member_already_friended = $user->friends ()->where ( "id", "=", $member_to_be_removed_id )->get ()->toArray ();
				if ($member_already_friended == null) {
					$this->error = 'player_already_not_favorited';
					return $this->response ();
				}
			}
		}

		$user->friends ()->detach ( $member_to_be_removed_id );
		$this->response = "player_removed_from_favorites";
		return $this->response ();
	}
	public function getFriends() {
		$logged_in_member = Member::find ( Auth::user ()->id );
		$friends = $logged_in_member->friends ()->select ( "id", "email", "firstName", "lastName", "phone", "profilePic" )->get ()->toArray ();
		if ($friends == null) {
			$this->error = "no_favorite_members";
		} else {
			for($x = 0; $x < count ( $friends ); $x ++) {
				unset ( $friends [$x]["pivot"] );
			}
			$this->response = $friends;
		}

		return $this->response ();
	}

	public function addNewFriendsGroup(Request $request){
		if (!$request->has('group_name')) {
			$this->error = "group_name_not_received";
			return $this->response();
		}

		if (!$request->has('members') || (is_array($request->get('members')) && empty ($request->get('members'))) || !is_array($request->get('members'))) {
			$this->error = "no_members_received";
			return $this->response();
		}else{

			$members = $request->get('members');
			$members = array_filter($members, function ($val) {
				if ($val == 0 || trim($val) == "") {
					return false;
				} else {
					return true;
				}
			});
			if(!count($members)){
				$this->error = "no_members_received";
				return $this->response();
			}
			$members = array_unique($members);
		}

		try{

			DB::beginTransaction();
			$memberModels = Member::whereIn("id",$members)->get();

			if(!$memberModels->count()){
				$this->error = "no_members_received";
				return $this->response();
			}

			$parent_member = Auth::user();


			$group = Group::create(['member_id'=>$parent_member->id, "name"=>$request->get('group_name')]);
			$parent_member->load('friends');

			foreach($memberModels as $member){
				$memberAlreadyFriended = false;
				$memberAlreadyInGroup = false;
				foreach($parent_member->friends as $friend){
					if($friend->id == $member->id){
						$memberAlreadyFriended = true;
						break;
					}
				}
				//validate if any of the sent members is not a friend
				if(!$memberAlreadyFriended){

					$this->error = "one_or_more_members_not_friend";
					return $this->response();
				}


				foreach($group->members as $groupMember){
					if($groupMember->id == $member->id){
						$memberAlreadyInGroup = true;
						break;
					}
				}
				//add member to friends first if not already in the list
				if(!$memberAlreadyInGroup){

					$group->members()->attach($member);

				}

			}
			//handle image upload if present
			if ($request->hasFile ( 'groupPic' )) {
				$uploadPath = self::uploadImage ( $request->file ( 'groupPic' ), 'friend_groups_image_path', md5 ( Auth::user ()->id ), true, false, $group->id );
				$group->groupPic = $uploadPath;
				$group->save();

			}


			DB::commit();

			$this->response = "group_added_successfuly";
			return $this->response();



		}catch (\Exception $e){

			\DB::rollback ();
			\Log::info ( __METHOD__, [
				'error' => $e->getMessage ()
			] );
			$this->error = "exception";
		}





		return $this->response();

	}

	public function updateFriendsGroupInfo(Request $request){

		if (!$request->has('group_id')) {
			$this->error = "group_id_not_received";
			return $this->response();
		}

		$group = Group::find($request->get('group_id'));
		if(!$group){
			$this->error = "invalid_group";
			return $this->response();
		}

		$parent_member = Auth::user();
		if($group->member_id != $parent_member->id){
			$this->error = "user_not_parent_of_group";
			return $this->response();
		}


		try{

			DB::beginTransaction();


			if ($request->has('group_name')) {
				$group->name = $request->get('group_name');
			}

			//handle image upload if present
			if ($request->hasFile ( 'groupPic' )) {
				$uploadPath = self::uploadImage ( $request->file ( 'groupPic' ), 'friend_groups_image_path', md5 ( Auth::user ()->id ), true, false, $group->id );
				$group->groupPic = $uploadPath;
				$group->save();

			}
			DB::commit();

			$this->response = "group_updated_successfuly";
			return $this->response();


		}catch (\Exception $e){

			\DB::rollback ();
			\Log::info ( __METHOD__, [
				'error' => $e->getMessage ()
			] );
			$this->error = "exception";
		}

		return $this->response();

	}

	public function addMoreFriendsToAnExistingGroup(Request $request){

		if (!$request->has('group_id')) {
			$this->error = "group_id_not_received";
			return $this->response();
		}

		if (!$request->has('members') || (is_array($request->get('members')) && empty ($request->get('members'))) || !is_array($request->get('members'))) {
			$this->error = "no_members_received";
			return $this->response();
		}else{

			$members = $request->get('members');
			$members = array_filter($members, function ($val) {
				if ($val == 0 || trim($val) == "") {
					return false;
				} else {
					return true;
				}
			});
			if(!count($members)){
				$this->error = "no_members_received";
				return $this->response();
			}
			$members = array_unique($members);
		}

		$group = Group::find($request->get('group_id'));
		if(!$group){
			$this->error = "invalid_group";
			return $this->response();
		}
		$parent_member = Auth::user();
		if($group->member_id != $parent_member->id){
			$this->error = "user_not_parent_of_group";
			return $this->response();
		}

		try{

			DB::beginTransaction();
			$memberModels = Member::whereIn("id",$members)->get();

			if(!$memberModels->count()){
				$this->error = "no_members_received";
				return $this->response();
			}

			$parent_member->load('friends');


			foreach($memberModels as $member){
				$memberAlreadyFriended = false;
				$memberAlreadyInGroup = false;
				foreach($parent_member->friends as $friend){
					if($friend->id == $member->id){
						$memberAlreadyFriended = true;
						break;
					}
				}
				//validate if any of the sent members is not a friend
				if(!$memberAlreadyFriended){

					$this->error = "one_or_more_members_not_friend";
					return $this->response();
				}


				foreach($group->members as $groupMember){
					if($groupMember->id == $member->id){
						$memberAlreadyInGroup = true;
						break;
					}
				}
				//add member to friends first if not already in the list
				if(!$memberAlreadyInGroup){

					$group->members()->attach($member);

				}

			}

			DB::commit();

			$this->response = "member_added_to_group_successfuly";
			return $this->response();



		}catch (\Exception $e){

			\DB::rollback ();
			\Log::info ( __METHOD__, [
				'error' => $e->getMessage ()
			] );
			$this->error = "exception";
		}





		return $this->response();

	}
	public function removeFriendsFromGroup(Request $request){

		if (!$request->has('group_id')) {
			$this->error = "group_id_not_received";
			return $this->response();
		}

		if (!$request->has('members') || (is_array($request->get('members')) && empty ($request->get('members'))) || !is_array($request->get('members'))) {
			$this->error = "no_members_received";
			return $this->response();
		}else{

			$members = $request->get('members');
			$members = array_filter($members, function ($val) {
				if ($val == 0 || trim($val) == "") {
					return false;
				} else {
					return true;
				}
			});
			if(!count($members)){
				$this->error = "no_members_received";
				return $this->response();
			}
			$members = array_unique($members);
		}

		$group = Group::find($request->get('group_id'));
		if(!$group){
			$this->error = "invalid_group";
			return $this->response();
		}
		$parent_member = Auth::user();
		if($group->member_id != $parent_member->id){
			$this->error = "user_not_parent_of_group";
			return $this->response();
		}

		try{

			DB::beginTransaction();
//			$memberModels = Member::whereIn("id",$members)->get();
//
//			if(!$memberModels->count()){
//				$this->error = "no_members_received";
//				return $this->response();
//			}

			$group->load('members');
			foreach($members as $memberSentForRemoval){
				foreach($group->members as $groupMember){
					if($groupMember->id == $memberSentForRemoval){

						$group->members()->detach($groupMember);

					}
				}
			}

			$group->load('members');
			if($group->members->count() == 0){
				$group->delete();
			}

			DB::commit();

			$this->response = "member_removed_from_group_successfuly";
			return $this->response();



		}catch (\Exception $e){

			\DB::rollback ();
			\Log::info ( __METHOD__, [
				'error' => $e->getMessage ()
			] );
			$this->error = "exception";
		}





		return $this->response();

	}

	public function listAllGroups(){
		$user_logged_in = Auth::user();

		$user_logged_in->load(['groups'=>function($query){
			$query->with(['members'=>function($query){
				$query->select('id','firstName','lastName','profilePic');
			}]);
		}]);

		if($user_logged_in->groups->count() == 0){
			$this->error = "no_groups_found";
			return $this->response();
		}
		foreach($user_logged_in->groups as $group){
			foreach ($group->members as $members){
				unset($members->pivot);

			}

		}

		$this->response = $user_logged_in->groups;

		return $this->response();


	}

	public function deleteGroup(Request $request){

		if (!$request->has('group_id')) {
			$this->error = "group_id_not_received";
			return $this->response();
		}

		$group = Group::find($request->get('group_id'));
		if(!$group){
			$this->error = "invalid_group";
			return $this->response();
		}
		$parent_member = Auth::user();
		if($group->member_id != $parent_member->id){
			$this->error = "user_not_parent_of_group";
			return $this->response();
		}

		try{

			DB::beginTransaction();

			$group->delete();

			DB::commit();

			$this->response = "group_deleted_successfuly";

		}catch (\Exception $e){

			\DB::rollback ();
			\Log::info ( __METHOD__, [
				'error' => $e->getMessage ()
			] );
			$this->error = "exception";
		}





		return $this->response();

	}


       
}
