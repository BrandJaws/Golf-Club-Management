<?php
namespace App\Http\Models;

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
        'date'
    ];

    public function paginatedList($club_id, $perPage, $currentPage, $search)
    {
        return $this->where('training.club_id', '=', $club_id)
            ->leftJoin('coaches', function ($join) {
            $join->on('coaches.id', '=', 'training.coach_id');
        })
            ->where(function ($query) use ($search) {
            if ($search) {
                $query->orWhere('training.name', 'like', "%$search%");
            }
        })
            ->select('training.id as id', 'training.name', 'training.seats', 'training.date', \DB::raw('CONCAT(coaches.firstName," ",coaches.lastName) as coach'), \DB::raw("'0' as seatsReserved"))
            ->orderby('training.created_at', 'DESC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }
}