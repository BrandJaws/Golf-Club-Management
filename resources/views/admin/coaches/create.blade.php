@extends('admin.__layouts.admin-layout')
@section('heading')
	Add Coach
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="{{route('admin.coaches.store')}}"  method="post" enctype="multipart/form-data">
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
                              <input type="text" name="firstName"  class="form-control" value="{{Request::old('firstName')}}" />
                              @if($errors->has('firstName')) <span class="help-block errorProfilePic">{{$errors->first('firstName') }}</span> @endif
                        </div>
                        <div class="form-group {{($errors->has('lastName'))?'has-error':''}}">
                              <label class="form-control-label">Last Name</label> 
                              <input type="text" name="lastName" class="form-control" value="{{Request::old('lastName')}}" />
                              @if($errors->has('lastName')) <span class="help-block errorProfilePic">{{$errors->first('lastName') }}</span> @endif
                        </div>
                        <div class="form-group {{($errors->has('email'))?'has-error':''}}">
                             <label class="form-control-label">Email</label> 
                             <input type="email" class="form-control" name="email" value="{{Request::old('email')}}" />
                             @if($errors->has('email')) <span class="help-block errorProfilePic">{{$errors->first('email') }}</span> @endif
                        </div>
						<div class="form-group {{($errors->has('phone'))?'has-error':''}}">
							<label class="form-control-label">Contact Number</label> 
							<input type="tel" class="form-control" name="phone" value="{{Request::old('phone')}}"/>
							@if($errors->has('phone')) <span class="help-block errorProfilePic">{{$errors->first('phone') }}</span> @endif
						</div>
						<div class="form-group">
							<label class="form-control-label">Specialities</label> 
							<input type="text" class="form-control" id="coachSpecialities" data-role="tagsinput" name="specialities" value="{{Request::old('specialities')}}" />
							<span class="help-block m-b-none" style="font-style: italic">Each separated with a comma.</span>
							@if($errors->has('phone')) <span class="help-block errorProfilePic">{{$errors->first('phone') }}</span> @endif
						</div>
						<br />
						<div class="form-group">
							<button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Add Coach</button> &nbsp;&nbsp; <a
								href="{{route('admin.coaches.coaches')}}"
								class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a>
						</div>
					</div>
						<div class="col-md-4">
						<div class="text-center">
							<img src="{{asset('assets/images/user.png')}}" class="img-responsive img-circle defaultImg" />
							<div class="form-group">
								<label class="form-control-label">Add Image</label> 
								<input type="file" class="form-control" name="profilePic" />
								@if($errors->has('profilePic')) <span class="help-block errorProfilePic">{{$errors->first('profilePic') }}</span> @endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


@endSection
@section('page-specific-scripts')
	<script>
        $("#coachSpecialities").tagsinput({
            tagClass: 'label red',
            confirmKeys: [13, 44],
            trimValue: true
        });
        $("#coachSpecialities").on('itemAdded', function(event) {
            var $field = $(this).siblings('.bootstrap-tagsinput').find('input')
            setTimeout(function(){
                $field.val('');
            }, 1);
        });
	</script>

@endsection