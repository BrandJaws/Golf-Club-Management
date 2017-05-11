<?php
namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{

    protected $table = 'training';

    protected $fillable = [
        'club_id',
        'coach_id',
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

    public function paginatedList($club_id, $currentPage,$perPage, $search, $onlyShowTrainingsNotYetComplete = false)
    {
        
        return $this->where('training.club_id', '=', $club_id)
            ->leftJoin('coaches', function ($join) {
            $join->on('coaches.id', '=', 'training.coach_id');
            })
            ->where(function($query) use ($onlyShowTrainingsNotYetComplete){
                if ($onlyShowTrainingsNotYetComplete) {
                    $query->where("endDate",">",Carbon::now()->toDateString());
                }
            })
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('training.name', 'like', "%$search%");
                }
            })
            ->select('training.id as id', 'training.name', 'training.seats', 'training.startDate', 'training.endDate',\DB::raw('CONCAT(coaches.firstName," ",coaches.lastName) as coach'), \DB::raw("(SELECT COUNT(*)  FROM reservation_players WHERE  reservation_id = training.id AND reservation_type = '".addslashes(Training::class)."'  ) as seatsReserved"))
            ->orderby('training.created_at', 'DESC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }

    public function getPlayersForTrainingPaginated($perPage, $currentPage){
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
