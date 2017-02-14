<?php
namespace App\Http\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $table = 'employee';

    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'email',
        'profilePic',
        'password',
        'club_id',
        'status',
        'permissions'
    ];
    
  
    public function club()
    {
        return $this->belongsTo('App\Http\Models\Club');
    }

    public static function getUserByEmail($email)
    {
        return self::where('email', '=', $email)->first();
    }

    /**
     *
     * @deprecated Use fillableFromArray instead
     * @param array $data            
     * @return \App\Http\Models\Employee
     */
    public function populate($data = [])
    {
        if (array_key_exists('lastName', $data)) {
            $this->lastName = $data['lastName'];
        }
        if (array_key_exists('firstName', $data)) {
            $this->firstName = $data['firstName'];
        }
        if (array_key_exists('phone', $data)) {
            $this->phone = $data['phone'];
        }
        if (array_key_exists('email', $data)) {
            $this->email = $data['email'];
        }
        if (array_key_exists('club_id', $data)) {
            $this->club_id = $data['club_id'];
        }
        if (array_key_exists('profilePic', $data)) {
            $this->profilePic = $data['profilePic'];
        }
        if (array_key_exists('password', $data)) {
            $this->password = Hash::make($data['password']);
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

    public function setPermissionsAttribute($value)
    {
        $permissions = \Config::get('global.staff.permissions');
        foreach ($permissions as $key => $module) {
            $permissions[$key] = (array_key_exists($key, $value)) ? true : false;
        }
        $this->attributes['permissions'] = json_encode($permissions);
    }

    public function updateProfileImage($profileImage)
    {
        $this->forceFill([
            'profilePic' => $profileImage
        ])->save();
    }

    /**
     * Get paginated list of employees for logedin club
     * @usage Web
     *
     * @param unknown $clubId            
     * @param unknown $currentPage            
     * @param unknown $perPage            
     */
    public function listClubEmployeesPaginated($clubId, $currentPage, $perPage, $searchTerm = false)
    {
        return $this->where('club_id', '=', $clubId)
            ->where(function ($query) use ($searchTerm) {
            if ($searchTerm) {
                $query->orWhere('employee.firstName', 'like', "%$searchTerm%");
                $query->orWhere('employee.lastName', 'like', "%$searchTerm%");
                $query->orWhere('employee.email', 'like', "%$searchTerm%");
            }
        })
            ->select('employee.id as id', 'employee.firstName', 'employee.lastName', 'employee.email', 'employee.phone', 'employee.status')
            ->orderby('employee.created_at', 'ASC')
            ->paginate($perPage, array(
            '*'
        ), 'current_page', $currentPage);
    }
}
