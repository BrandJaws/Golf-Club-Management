<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPlayer extends Model
{

    protected $fillable = [
        'reservation_id',
        'reservation_type',
        'member_id',
        'parent_id',
        'group_size',
        'response_status',
        'reservation_status',
        'nextJobToProcess'
    ];
    
    public function reservation(){
        return $this->morphTo();
    }
    
    public function member(){
        return $this->belongsTo(Member::class);
    }
    
   
}
