<?php
namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Gate;
use App\Http\Models\Employee;

class AdminPolicy
{
    use HandlesAuthorization;

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
        return true;
    }

    /**
     * Check if current user can manage staff
     * 
     * @param Employee $employee            
     * @return boolean
     */
    public function staff(Employee $employee)
    {
        return false;
    }
}
