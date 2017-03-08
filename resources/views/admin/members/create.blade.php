@extends('admin.__layouts.admin-layout')
@section('heading')
	Add Member
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="{{route('admin.member.store')}}"  method="post" enctype="multipart/form-data">
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
                            <div class="form-group {{($errors->has('password'))?'has-error':''}}">
    							<label class="form-control-label">Password</label> 
    							<input type="password" class="form-control" name="password" />
    							 @if($errors->has('password')) <span class="help-block errorProfilePic">{{$errors->first('password') }}</span> @endif
							</div>
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="form-group form-group-inline">
                                        <label class="form-control-label">Gender</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check"> 
                                                    	<input type="radio" name="gender" value="{{Config::get('global.gender.male')}}" class="has-value" {{(Request::old('gender') == Config::get('global.gender.male'))?'checked':(!Request::old('gender')?'checked':'' )}}> <i class="dark-white"></i> Male
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
													<input type="radio" name="gender"  value="{{Config::get('global.gender.female')}}" class="has-value" {{(Request::old('gender') == Config::get('global.gender.female'))?'checked':''}}> <i class="dark-white"></i>
                                                    Female
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<div class="row row-sm">
							<div class="col-md-3">
								<div class="form-group form-group-inline">
									<label class="form-control-label">Member Type</label>
								</div>
							</div>
							<div class="col-md-8">
								<div class="row row-sm">
									<div class="col-md-3">
										<div class="form-group">
											<div class="radio">
												<label class="ui-check"> 
												<input v-model="memberType" type="radio" name="relation" value="parent" class="has-value" @change="affiliate()" /> <i class="dark-white"></i> Parent
												</label>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<div class="radio">
												<label class="ui-check"> <input v-model="memberType"
													type="radio" name="relation" value="affiliate"
													class="has-value" @change="affiliate()" /> <i
													class="dark-white"></i> Affiliate Member
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-sm animated fadeInUp" v-cloak
							v-if="showParentSelector">
							<div class="col-md-3"></div>
							<div class="col-md-9">
								<div class="row row-sm">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-control-label">Select Parent Member</label>
										</div>
									</div>
									<div class="col-md-8">
										<div class="form-group" id="membersPageAutoCom">
											<auto-complete-box url="{{asset('people.json')}}"
												property-for-id="member_id" property-for-name="member_name"
												filtered-from-source="false" include-id-in-list="true"
												v-model="selectedId" initial-text-value=""> </auto-complete-box>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br />
						<div class="form-group">
							<button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Add Member</button> &nbsp;&nbsp; <a
								href="{{route('admin.member.index')}}"
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

@include("admin.__vue_components.autocomplete.autocomplete")
<script>
        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showParentSelector:false,
                memberType:'parent',
                selectedId: '',
            },
            methods: {
                affiliate:function() {
                    console.log(this.memberType);
                    if (this.memberType == 'affiliate') {
                        this.showParentSelector = true;
                    }
                    else {
                        this.showParentSelector = false;
                    }
                }
            }
        });
    </script>
<script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
@endSection
