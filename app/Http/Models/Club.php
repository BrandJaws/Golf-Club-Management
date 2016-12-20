<?php

namespace App\Http\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Club extends Model {

    protected $table = 'club';
    protected $fillable = [
        'name',
        'address',
        'logo',
        'opening',
        'closing'
    ];

    public function populate($data = []) {
        if (array_key_exists('club_name', $data)) {
            $this->name = $data['club_name'];
        }
        if (array_key_exists('address', $data)) {
            $this->address = $data['address'];
        }
        if (array_key_exists('logo', $data)) {
            $this->logo = $data['logo'];
        }
        if (array_key_exists('opening', $data)) {
            $this->opening = $data['opening'];
        }
        if (array_key_exists('closing', $data)) {
            $this->closing = $data['closing'];
        }
        return $this;
    }
    public function court(){
        return $this->hasMany('\App\Http\Models\Court')->where('status','=','OPEN');
    }
    public function member() {
        return $this->hasMany('\App\Http\Models\Member');
    }
    public function listPlayers(){
         return $this->hasMany('\App\Http\Models\Member')->where('member.id','!=',Auth::user()->id);
    }
}
