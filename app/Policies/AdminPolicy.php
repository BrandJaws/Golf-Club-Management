<?php
namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Gate;
use App\Http\Models\Employee;

class AdminPolicy
{
    use HandlesAuthorization;

    protected $permissions;

    /**
     *
     * @param Employee $user            
     * @param unknown $ability            
     * @return boolean
     */
    public function before(Employee $user, $ability)
    {
        if (is_null($user->permissions)) {
            return true;
        } else {
            $this->permissions = json_decode($user->permissions, true);
        }
    }

    /**
     * check if current user can manage members
     *
     * @param Employee $employee            
     * @return boolean
     */
    public function member(Employee $employee)
    {
        return (array_get($this->permissions, 'member')) ?: false;
    }

    /**
     * Check if current user can manage staff
     *
     * @param Employee $employee            
     * @return boolean
     */
    public function staff(Employee $employee)
    {
        return (array_get($this->permissions, 'staff')) ?: false;
    }

    public function reservation(Employee $employee)
    {
        return (array_get($this->permissions, 'reservation')) ?: false;
    }

    public function shop(Employee $employee)
    {
        return (array_get($this->permissions, 'shop')) ?: false;
    }

    public function segment(Employee $employee)
    {
        return (array_get($this->permissions, 'segment')) ?: false;
    }

    public function beacon(Employee $employee)
    {
        return (array_get($this->permissions, 'beacon')) ?: false;
    }

    public function offer(Employee $employee)
    {
        return (array_get($this->permissions, 'offer')) ?: false;
    }

    public function notification(Employee $employee)
    {
        return (array_get($this->permissions, 'notification')) ?: false;
    }

    public function social(Employee $employee)
    {
        return (array_get($this->permissions, 'social')) ?: false;
    }

    public function training(Employee $employee)
    {
        return (array_get($this->permissions, 'training')) ?: false;
    }

    public function coach(Employee $employee)
    {
        return (array_get($this->permissions, 'coach')) ?: false;
    }

    public function league(Employee $employee)
    {
        return (array_get($this->permissions, 'league')) ?: false;
    }

    public function course(Employee $employee)
    {
        return (array_get($this->permissions, 'course')) ?: false;
    }
}
