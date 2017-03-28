<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;
    protected $table = 'group';
    protected $fillable = [
        'member_id',
        'name'
    ];

    public function members() {
        return $this->belongsToMany ( "App\Http\Models\Member", "group_member", "group_id", "member_id" )->withTimestamps ();
    }
}
