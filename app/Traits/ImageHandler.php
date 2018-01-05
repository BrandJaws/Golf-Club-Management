<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageHandler
 *
 * @author kas
 */
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Config;
trait ImageHandler {
	public static function uploadImage(UploadedFile $file, $path, $md5String, $upload = true, $clearOld = true, $renameTo = false , $oldImagePath="") {
		try {
			$path = str_replace ( [ 
					'{member_id}',
					'{club_id}',
					'{admin_id}',
					'{employee_id}' 
			], $md5String, \Config::get ( 'global.image_path.' . $path ) );
			$date = new \DateTime ();
			$timeStamp = $date->getTimestamp ();
                        if($renameTo !== false){
                            $fileName = $renameTo.".".$file->getClientOriginalExtension();
                        }else{
                            $fileName = str_replace ( ' ', '_', strtolower ( $file->getClientOriginalName () ) );
                        }
			

			if (! file_exists ( $path ) && ! is_dir ( $path )) {
				self::constructPath ( $path );
			} else if ($upload && $clearOld) {

				//array_map ( 'unlink', glob ( $path . "/*" ) );
				if($oldImagePath){
					
					array_map ( 'unlink', glob ( $oldImagePath ) );
				}


			}
			
			if ($upload) {
				$file->move ( $path, $fileName );
			}
			return $path . $fileName;
		} catch ( \Exception $e ) {
			dd($e);
			\Log::error ( __METHOD__, [ 
					'error' => $e->getMessage (),
					'line' => $e->getLine () 
			] );
			//$this->error = 'There is a problem uploading image please try later';
		}
	}
	public static function constructPath($path) {
		$dir = pathinfo ( $path, PATHINFO_DIRNAME );
		$dirArr = pathinfo ( $path );
		$is_directory = is_dir ( $dir );
		if ($is_directory) {
			return true;
		} else {
			if (self::constructPath ( $dir )) {
				if (mkdir ( $dir )) {
					chmod ( $dir, 0755 );
					return true;
				}
			}
		}
		return false;
	}
	public static function deleteImage($md5String, $path) {
		$path = str_replace ( [ 
				'{member_id}',
				'{club_id}',
				'{admin_id}' 
		], $md5String, \Config::get ( 'global.image_path.' . $path ) );
		if (is_dir ( $path )) {
			array_map ( 'unlink', glob ( $path . "/*" ) );
			if (substr ( $path, strlen ( $path ) - 1, 1 ) == '/') {
				$path = rtrim ( $path, '/' );
			}
			rmdir ( $path );
		}
	}
}
