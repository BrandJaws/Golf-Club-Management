<?php
namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Employee;
use Illuminate\Support\Facades\Redirect;

class StaffController extends Controller
{

    public function index(Request $request)
    {
        if(Auth()->user()->canNot('staff','App\Model')){
            return Redirect::route('admin.dashboard');
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $employees = (new Employee())->listClubEmployeesPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        if($request->ajax()){
            return $employees;
        }
        
        return view('admin.staff.staff', compact('employees'));
    }

    public function create()
    {
        if(Auth()->user()->canNot('staff','App\Model')){
            return Redirect::to('/dashboard');
        }
        return view('admin.staff.create');
    }
}
