<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Beacon extends Model {
	protected $table = 'beacon';
	protected $fillable = [ 
			'club_id',
			'course_id',
			'name',
			'UUID',
			'major',
			'minor',
	        'status'
	];
	protected $gaurded = ['configuration'];
	protected $hidden = [
			'created_at',
			'updated_at'
	];
	public function paginatedList($club_id, $perPage, $currentPage) {
		return self::where ( 'beacon.club_id', '=', $club_id )->leftJoin ( 'course', function ($join) {
			$join->on ( 'course.id', '=', 'beacon.course_id' );
		} )->paginate ( $perPage, [ 
				'beacon.*',
				'course.name as courseName' 
		], 'current_page', $currentPage );
	}
}