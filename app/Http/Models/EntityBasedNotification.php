<?php

namespace App\Http\Models;

use App\Collection\AdminNotificationEventsManager;
use Illuminate\Database\Eloquent\Model;

class EntityBasedNotification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'club_id',
        'event',
        'entity_id',
        'entity_type',
        'deleted_entity'
    ];


}
