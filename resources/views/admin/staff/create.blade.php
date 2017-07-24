@extends('admin.__layouts.admin-layout')
@section('heading')
	Add Staff Members
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="{{route('admin.staff.store')}}" method="post" enctype="multipart/form-data">
				@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
					<input type="hidden" name="_method" value="POST" />
				   {{ csrf_field() }}
					<div class="col-md-8">
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">First Name</label> 
							<input type="text" class="form-control" name="firstName"  value="{{ Request::old('firstName')}}"/>
							 @if($errors->has('firstName')) <span class="help-block">{{$errors->first('firstName') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">Last Name</label> 
							<input type="text" class="form-control" name="lastName" value="{{Request::old('lastName')}}" />
							 @if($errors->has('lastName')) <span class="help-block">{{$errors->first('lastName') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">Email</label> 
							<input type="email" class="form-control" name="email" value="{{Request::old('email')}}"/>
							 @if($errors->has('email')) <span class="help-block">{{$errors->first('email') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('phone'))?'has-error':''}}">
							<label class="form-control-label">Contact Number</label> 
							<input type="tel" class="form-control" name="phone" value="{{Request::old('phone')}}"/>
							 @if($errors->has('phone')) <span class="help-block">{{$errors->first('phone') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('password'))?'has-error':''}}">
							<label class="form-control-label">Password</label> 
							<input type="password" class="form-control" name="password" />
							 @if($errors->has('password')) <span class="help-block">{{$errors->first('password') }}</span> @endif
						</div>
						<div class="row row-sm">
							<div class="col-md-12">
								<h3>Responsible to manage</h3>
								<hr />
							</div>
						</div>
						<div class="row row-sm">
							<div class="col-md-12">
								<div class="row row-sm">
								<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="course" name="permissions[]" {{(Request::old('permissions') && array_key_exists('course',array_flip(Request::old('permissions'))))?'checked':''}}>
												Courses
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="member" name="permissions[]" {{(Request::old('permissions') && array_key_exists('member',array_flip(Request::old('permissions'))))?'checked':''}}>
												Members
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="reservation" name="permissions[]" {{(Request::old('permissions') && array_key_exists('reservation',array_flip(Request::old('permissions'))))?'checked':''}}>
												Reservations
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="shop" name="permissions[]" {{(Request::old('permissions') && array_key_exists('shop',array_flip(Request::old('permissions'))))?'checked':''}}>
												Shop
											</label>
										</div>
									</div>
									
								</div>
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="segment" name="permissions[]" {{(Request::old('permissions') && array_key_exists('segment',array_flip(Request::old('permissions'))))?'checked':''}}>
												Segments
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="beacon" name="permissions[]" {{(Request::old('permissions') && array_key_exists('beacon',array_flip(Request::old('permissions'))))?'checked':''}}>
												Beacon
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="offer" name="permissions[]" {{(Request::old('permissions') && array_key_exists('offer',array_flip(Request::old('permissions'))))?'checked':''}}>
												Offers/Rewards
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="notification" name="permissions[]" {{(Request::old('permissions') && array_key_exists('notification',array_flip(Request::old('permissions'))))?'checked':''}}>
												Notifications
											</label>
										</div>
									</div>
									
								</div>
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="social" name="permissions[]" {{(Request::old('permissions') && array_key_exists('social',array_flip(Request::old('permissions'))))?'checked':''}}>
												Social
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="staff" name="permissions[]" {{(Request::old('permissions') && array_key_exists('staff',array_flip(Request::old('permissions'))))?'checked':''}}>
												Staff
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="training" name="permissions[]" {{(Request::old('permissions') && array_key_exists('training',array_flip(Request::old('permissions'))))?'checked':''}}>
												Trainings
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="coache" name="permissions[]" {{(Request::old('permissions') && array_key_exists('coache',array_flip(Request::old('permissions'))))?'checked':''}}>
												Coaches
											</label>
										</div>
									</div>
									
								</div>
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="league" name="permissions[]" {{(Request::old('permissions') && array_key_exists('league',array_flip(Request::old('permissions'))))?'checked':''}}>
												Leagues
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="event" name="permissions[]" {{(Request::old('permissions') && array_key_exists('event',array_flip(Request::old('permissions'))))?'checked':''}}>
												Events
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br />
						<div class="form-group">
							<button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Add Staff</button> &nbsp;&nbsp; <a
								href="{{route('admin.staff.index')}}"
								class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="text-center">
							<img src="{{asset('assets/images/user.png')}}" class="img-responsive img-circle defaultImg" />
							<div class="form-group {{($errors->has('profilePic'))?'has-error':''}}">
								<label class="form-control-label">Add Image</label> 
								<input type="file" class="form-control" name="profilePic"/>
								@if($errors->has('profilePic')) <span class="help-block">{{$errors->first('profilePic') }}</span> @endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endSection
