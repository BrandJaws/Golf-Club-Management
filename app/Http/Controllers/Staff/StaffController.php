<?php
namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Employee;

class StaffController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $employees = (new Employee())->listClubEmployeesPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        
        
        return view('admin.staff.staff', compact('employees'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }
}
