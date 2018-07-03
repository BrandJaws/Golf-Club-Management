<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $table = 'event';

    protected $fillable = [
        'club_id',
        'name',
        'description',
        'seats',
        'promotionContent',
        'promotionType',
        'sessions',
        'startDate',
        'endDate',
        'price'
    ];

    public function reservation_players()
    {
        return $this->morphMany("App\Http\Models\ReservationPlayer", "reservation");
    }

    public function attachPlayer($player)
    {


                $playerData = [];
                $playerData["reservation_id"] =$this->id;
                $playerData["reservation_type"] =self::class;
                $playerData["member_id"] = $player == "guest" ? 0 : $player;
                $playerData["parent_id"] = $player == "guest" ? null : $player;
                $playerData["group_size"] = 1;
                $playerData["response_status"] = \Config::get('global.reservation.confirmed');
                $playerData["reservation_status"] = \Config::get('global.reservation.reserved');

                $reservationPlayer = ReservationPlayer::create($playerData);
                return $reservationPlayer;

    }

    public function paginatedList($club_id, $currentPage,$perPage, $search, $onlyShowEventsNotYetComplete = false)
    {
        
        return $this->where('event.club_id', '=', $club_id)
            ->where(function($query) use ($onlyShowEventsNotYetComplete){
                if ($onlyShowEventsNotYetComplete) {
                    $query->where("endDate",">",Carbon::now()->toDateString());
                }
            })
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('event.name', 'like', "%$search%");
                }
            })
            ->select('event.id as id', 'event.name', 'event.seats', 'event.startDate', 'event.endDate', 'event.promotionType','event.promotionContent',\DB::raw("(SELECT COUNT(*)  FROM reservation_players WHERE  reservation_id = event.id AND reservation_type = '".addslashes(Event::class)."'  ) as seatsReserved"))
            ->orderby('event.created_at', 'DESC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }

    public function getPlayersForEventPaginated($perPage, $currentPage){
        return ReservationPlayer:: where('reservation_id', '=', $this->id)
                                 ->where('reservation_type', '=', self::class)
                                 ->leftJoin('member', 'member.id', '=', 'reservation_players.member_id')
                                 ->select('reservation_players.id', \DB::raw('CONCAT(member.firstName," ",member.lastName ) as name'), 'member.email', 'member.phone')
                                 ->orderby('reservation_players.created_at', 'DESC')
                                 ->paginate($perPage, array(
                                     '*'
                                 ), 'current_page', $currentPage);
    }
}
