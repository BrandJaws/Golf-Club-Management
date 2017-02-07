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
			'permissions' 
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
			$this->password =  Hash::make(  $data ['password']);
		}
		return $this;
	}
	
	public function updateProfileImage($profileImage) {
		$this->forceFill ( [ 
				'profilePic' => $profileImage 
		] )->save ();
	}
}
