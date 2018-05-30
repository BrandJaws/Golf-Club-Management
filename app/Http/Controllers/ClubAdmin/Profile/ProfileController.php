<?php

namespace App\Http\Controllers\ClubAdmin\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {
	public function index() {
		return view ( 'admin.profile.profile' );
	}
	public function edit() {
		return view ( 'admin.profile.edit' );
	}
	public function update(Request $request) {
		$validator = Validator::make($request->all(), [
//			'firstName' => 'required|min:1,max:40',
//			'lastName' => 'required|min:1,max:40',
//			'email' => 'required|email',
//			'phone' => 'numeric',
//			'password' => 'required|min:4,max:15',
			'profilePic' => 'sometimes|image|mimes:jpeg,bmp,png,jpg|max:1024'
		]);

		try {
			$employee = Auth::user();
			$data = $request->except([
				'profilePic'
			]);

			if ($request->has('password')) {
				$employee->password = bcrypt($request->get('password'));
			}
			if ($request->hasFile('profilePic')) {
				$image = $request->file('profilePic');
				$fileName = time() . '.' . $image->getClientOriginalExtension();
				$image->move('uploads/employee/', $fileName);
				if (! is_null($employee->profilePic) && $employee->profilePic != '' && file_exists($employee->profilePic)) {
					@unlink($employee->profilePic);
				}
				$employee->profilePic = 'uploads/employee/' . $fileName;
			}

			$employee->fill($data)->update();
			return \Redirect::route('admin.profile')->with([
				'success' => \trans('message.profile_update_success')
			]);

		}catch (\Exception $exp) {

			return \Redirect::back()->withInput()->with([
				'error' => $exp->getMessage()
			]);
		}

	}
}
