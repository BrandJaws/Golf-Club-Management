<?php
namespace App\Http\Controllers\ClubAdmin\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Employee;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    public function index(Request $request)
    {
        if (Auth()->user()->canNot('staff', 'App\Model')) {
            return Redirect::route('admin.dashboard');
        }
        $search = $request->has('search') ? $request->get('search') : false;
        $currentPage = $request->has('current_page') ? $request->get('current_page') : 0;
        $perPage = $request->has('per_page') ? $request->get('per_page') : \Config::get('global.portal_items_per_page');
        $employees = (new Employee())->listClubEmployeesPaginated(Auth::user()->club_id, $currentPage, $perPage, $search);
        if ($employees->count() > 0) {
            $employees = json_encode($employees);
        }
        if ($request->ajax()) {
            return $employees;
        }
        
        return view('admin.staff.staff', compact('employees'));
    }

    public function create()
    {
        if (Auth()->user()->canNot('staff', 'App\Model')) {
            return Redirect::to('/dashboard');
        }
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:1,max:40',
            'lastName' => 'required|min:1,max:40',
            'email' => 'required|email',
            'phone' => 'numeric',
            'password' => 'required|min:4,max:15'
        ]);
        
        if ($validator->fails()) {
            $this->error = $validator->errors();
            return \Redirect::back()->withInput()->withErrors($this->error);
        }
        try {
            $employee = new Employee();
            $data = $request->only([
                'firstName',
                'lastName',
                'email',
                'phone',
                'password',
                'permissions'
            ]);
            $data['club_id'] = \Auth::user()->club_id;
            $employee->fill($data)->save();
            return \Redirect::route('admin.staff.index')->with([
                'success' => \trans('messages.member_update_success')
            ]);
        } catch (\Exception $exp) {
            return \Redirect::back()->withInput()->with([
                'error' => $exp->getMessage()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if (Auth()->user()->canNot('staff', 'App\Model')) {
            return Redirect::to('/dashboard')->with([
                'error' => \trans('messages.unauthorized_access')
            ]);;
        }
        try {
            $employee = Employee::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exp) {
            return Redirect::back()->with([
                'error' => \trans('messages.not_found')
            ]);
        } catch (\Exception $exp) {
            return Redirect::back()->with([
                'error' => $exp->getMessage()
            ]);
        }
        if(empty($request->old())){
            $employee = $employee->toArray();
            if(!is_null($employee['permissions']))
            $employee['permissions'] = array_keys(json_decode($employee['permissions'], true),true);
        }else{
            $employee = $request->old();
        }
        return view('admin.staff.edit',compact('employee'));
    }

    public function update(Request $request, $id)
    {}

    public function destroy($id)
    {}
}
