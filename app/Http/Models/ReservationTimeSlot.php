<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationTimeSlot extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'reservation_id',
        'reservation_type',
        'time_start'
    ];
    
    public function reservation(){
        return $this->morphTo();
    }
    
    
}
