<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TokenProvider
 *
 * @author kas
 */
trait AuthToken {
	public static function v4() {
		return sprintf ( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
				// 32 bits for "time_low"
				mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), 
				// 16 bits for "time_mid"
				mt_rand ( 0, 0xffff ), 
				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand ( 0, 0x0fff ) | 0x4000, 
				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand ( 0, 0x3fff ) | 0x8000, 
				// 48 bits for "node"
				mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ), mt_rand ( 0, 0xffff ) );
	}
	public function getAccessToken($object) {
		if (is_object ( $object ) && ! empty ( $object )) {
			$checkToken = \App\Http\Models\AuthToken::findTokenByRelation ( $object->id, get_class ( $object ) );
			if (! is_null ( $checkToken ) && count ( $checkToken ) > 0) {
				$token = $checkToken->access_token;
			} else {
				$token = self::v4 ();
				$authToken = new \App\Http\Models\AuthToken ();
				$authToken->populate ( [ 
						'access_token' => $token,
						'resource_type' => get_class ( $object ),
						'resource_id' => $object->id 
				] )->save ();
			}
			return $token;
		}
		return false;
	}
	public function validateAccessToken($token) {
		if (! empty ( $token )) {
			$authToken = new \App\Http\Models\AuthToken ();
			$AuthObject = $authToken->where ( 'access_token', '=', $token )->first ();
			if (! is_null ( $AuthObject )) {
				return $AuthObject;
			}
		}
		return false;
	}
	public static function fetchToken($section, $token) {
		if (! empty ( $section ) && ! empty ( $token )) {
			$value = Cache::tags ( $section )->get ( $token );
			if (! is_null ( $value )) {
				return $value;
			}
		}
		return false;
	}
	public static function deleteToken($section, $token) {
	}
}
