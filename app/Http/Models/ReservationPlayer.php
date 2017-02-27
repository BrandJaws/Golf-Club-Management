<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPlayer extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'reservation_id',
        'reservation_type',
        'member_id',
        'status'
    ];
    
    public function reservation(){
        return $this->morphTo();
    }
    
   
}
