<?php

namespace App\Http\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable {
	use Notifiable;
	protected $table = 'employee';
	protected $fillable = [ 
			'firstName',
			'lastName',
			'phone',
			'email',
			'profilePic',
			'password',
			'club_id',
			'salt' 
	];
	public function club() {
		return $this->belongsTo ( 'App\Http\Models\Club' );
	}
	public static function getUserByEmail($email) {
		return self::where ( 'email', '=', $email )->first ();
	}
	public function populate($data = []) {
		if (array_key_exists ( 'lastName', $data )) {
			$this->lastName = $data ['lastName'];
		}
		if (array_key_exists ( 'firstName', $data )) {
			$this->firstName = $data ['firstName'];
		}
		if (array_key_exists ( 'phone', $data )) {
			$this->phone = $data ['phone'];
		}
		if (array_key_exists ( 'email', $data )) {
			$this->email = $data ['email'];
		}
		if (array_key_exists ( 'club_id', $data )) {
			$this->club_id = $data ['club_id'];
		}
		if (array_key_exists ( 'profilePic', $data )) {
			$this->profilePic = $data ['profilePic'];
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
	
	public function updateProfileImage($profileImage) {
		$this->forceFill ( [ 
				'profilePic' => $profileImage 
		] )->save ();
	}
	/*
	 * public function getProfilePicAttribute($value) {
	 * if($this->attributes['profilePic']){
	 * return asset($this->attributes['profilePic']);
	 * }else{
	 * return null;
	 * }
	 * }
	 */
}
