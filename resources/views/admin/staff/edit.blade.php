@extends('admin.__layouts.admin-layout') @section('main')
<div id="content" class="app-content box-shadow-z0" role="main">
	<div class="app-header white box-shadow">
		<div class="navbar">
			<!-- Open side - Naviation on mobile -->
			<a data-toggle="modal" data-target="#aside"
				class="navbar-item pull-left hidden-lg-up"> <i
				class="material-icons">&#xe5d2;</i>
			</a>
			<!-- / -->
			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h5"
				ng-bind="$state.current.data.title" id="pageTitle"></div>
			<!-- navbar right -->
			<ul class="nav navbar-nav pull-right">
				<li class="nav-item dropdown pos-stc-xs"><a class="nav-link" href
					data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span
						class="label label-sm up warn">3</span>
				</a></li>
				<li class="nav-item dropdown"><a class="nav-link clear" href
					data-toggle="dropdown"> <span class="avatar w-32"> <img
							src="../../assets/images/a0.jpg" alt="..."> <i
							class="on b-white bottom"></i>
					</span>
				</a>
					<div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
						<a class="dropdown-item" href="{{route('admin.profile.profile')}}">
							<span>Profile</span>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#/access/signin">Sign out</a>
					</div></li>
				<li class="nav-item hidden-md-up"><a class="nav-link"
					data-toggle="collapse" data-target="#collapse"> <i
						class="material-icons">&#xe5d4;</i>
				</a></li>
			</ul>
			<!-- / navbar right -->

			<!-- navbar collapse -->
			<div class="collapse navbar-toggleable-sm" id="collapse">
				<div class="main-page-heading">
					<h3>
						<span>Add Staff Member</span>
					</h3>
				</div>
			</div>
			<!-- / navbar collapse -->
		</div>
	</div>

	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="{{route('admin.staff.update',['employee_id',$employee['id']])}}" method="post">
				@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
					<input type="hidden" name="_method" value="put" />
				   {{ csrf_field() }}
					<div class="col-md-8">
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">First Name</label> 
							<input type="text" class="form-control" name="firstName"  value="{{$employee['firstName']}}"/>
							 @if($errors->has('firstName')) <span class="help-block">{{$errors->first('firstName') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">Last Name</label> 
							<input type="text" class="form-control" name="lastName" value="{{$employee['lastName']}}" />
							 @if($errors->has('lastName')) <span class="help-block">{{$errors->first('lastName') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
							<label class="form-control-label">Email</label> 
							<input type="email" class="form-control" name="email" value="{{$employee['email']}}"/>
							 @if($errors->has('email')) <span class="help-block">{{$errors->first('email') }}</span> @endif
						</div>
						<div class="form-group {{($errors->has('phone'))?'has-error':''}}">
							<label class="form-control-label">Contact Number</label> 
							<input type="tel" class="form-control" name="phone" value="{{$employee['phone']}}"/>
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
												<input type="checkbox" value="member" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('member',array_flip($employee['permissions'])))?'checked':''}}>
												Members
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="reservation" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('reservation',array_flip($employee['permissions'])))?'checked':''}}>
												Reservations
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="shop" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('shop',array_flip($employee['permissions'])))?'checked':''}}>
												Shop
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="segment" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('segment',array_flip($employee['permissions'])))?'checked':''}}>
												Segments
											</label>
										</div>
									</div>
								</div>
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="beacon" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('beacon',array_flip($employee['permissions'])))?'checked':''}}>
												Beacon
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="offer" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('offer',array_flip($employee['permissions'])))?'checked':''}}>
												Offers/Rewards
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="notification" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('notification',array_flip($employee['permissions'])))?'checked':''}}>
												Notifications
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="social" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('social',array_flip($employee['permissions'])))?'checked':''}}>
												Social
											</label>
										</div>
									</div>
								</div>
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="staff" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('staff',array_flip($employee['permissions'])))?'checked':''}}>
												Staff
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="training" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('training',array_flip($employee['permissions'])))?'checked':''}}>
												Trainings
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="coache" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('coache',array_flip($employee['permissions'])))?'checked':''}}>
												Coaches
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="checkbox">
											<label>
												<input type="checkbox" value="league" name="permissions[]" {{(is_array($employee['permissions']) && array_key_exists('league',array_flip($employee['permissions'])))?'checked':''}}>
												Leagues
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
							<img src="../../assets/images/user.png"
								class="img-responsive img-circle defaultImg" />
							<div class="form-group">
								<label class="form-control-label">Add Image</label> <input
									type="file" class="form-control" />
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endSection
