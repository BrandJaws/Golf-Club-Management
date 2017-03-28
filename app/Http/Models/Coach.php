<?php
namespace App\Http\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use Notifiable;

    protected $fillable = [
        'club_id',
        'firstName',
        'lastName',
        'email',
        'phone',
        'profilePic',
        'gender',
        'dob',
        'specialities',
        'status'
    ];

    /**
     * Relation with club
     * @usage Mobile, Web
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function club()
    {
        return $this->belongsTo('App\Http\Models\Club');
    }

    /**
     *
     * @deprecated use fill instead of populate
     * @param array $data            
     * @return \App\Http\Models\Coach
     */
    public function populate($data = [])
    {
        if (array_key_exists('firstName', $data)) {
            $this->firstName = $data['firstName'];
        }
        if (array_key_exists('lastName', $data)) {
            $this->lastName = $data['lastName'];
        }
        if (array_key_exists('email', $data)) {
            $this->email = $data['email'];
        }
        if (array_key_exists('phone', $data)) {
            $this->phone = $data['phone'];
        }
        if (array_key_exists('profilePic', $data)) {
            $this->profilePic = $data['profilePic'];
        }
        if (array_key_exists('gender', $data)) {
            $this->gender = $data['gender'];
        }
        if (array_key_exists('dob', $data)) {
            $this->dob = Carbon::parse($data['dob'])->toDateString();
        }
        if (array_key_exists('status', $data)) {
            $this->status = $data['status'];
        }
        return $this;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtoupper($value);
    }

    public function getStatusNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Search club Coaches with in a club
     * @usage Mobile, Web
     *
     * @param unknown $clubId            
     * @param unknown $search            
     */
    public function listClubCoaches($clubId, $search = null)
    {
        return self::where('club_id', '=', $clubId)->where(function ($query) use ($search) {
            if ($search !== false) {
                $query->where('coaches.firstName', 'Like', '%' . $search . '%')
                    ->orWhere('coaches.lastName', 'Like', '%' . $search . '%');
            }
        })
            ->take(15)
            ->get([
            'coaches.id',
            \DB::raw("CONCAT(firstName,' ', lastName) AS name")
        ]);
    }

    /**
     * Get paginated list of coaches for logedin club
     * @usage Web
     *
     * @param unknown $clubId            
     * @param unknown $currentPage            
     * @param unknown $perPage            
     */
    public function listClubCoachesPaginated($clubId, $currentPage, $perPage, $searchTerm = false)
    {
        return $this->where('club_id', '=', $clubId)
            ->where(function ($query) use ($searchTerm) {
            if ($searchTerm) {
                $query->orWhere('coaches.firstName', 'like', "%$searchTerm%");
                $query->orWhere('coaches.lastName', 'like', "%$searchTerm%");
                $query->orWhere('coaches.email', 'like', "%$searchTerm%");
            }
        })
            ->select('coaches.id as id', 'coaches.firstName', 'coaches.lastName', 'coaches.email', 'coaches.phone', 'coaches.specialities')
            ->orderby('coaches.created_at', 'DESC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }

    /**
     * Get total coaches with in a club
     * @usage Mobile, Web
     *
     * @param unknown $clubId            
     */
    public static function countClubCoaches($clubId)
    {
        return self::where('club_id', '=', $clubId)->count();
    }

    public function updateProfileImage($profileImage)
    {
        $this->forceFill([
            'profilePic' => $profileImage
        ])->save();
    }

    public function getCoachDropDownList($club_id)
    {
        return $this->where('club_id', '=', $club_id)->get([
           \DB::raw("CONCAT(firstName,' ', lastName) AS name"),
            'id'
        ]);
    }
}
