@extends('admin.__layouts.admin-layout')
@section('heading')
	Configure Beacon
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<div class="beacon-configure" id="vue-config-container">
						<form action="{{route('admin.beacon.store')}}" method="post" enctype="multipart/form-data">
    						@if(Session::has('error'))
                            	<div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                            @endif
                            @if(Session::has('success'))
                            	<div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                            @endif
                        <input type="hidden" name="_method" value="POST" />
				    	{{ csrf_field() }}
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('course'))?'has-error':''}}">
										<label>Course</label> 
										<select name="course" class="form-control">
											<option value="0">Please Select</option>
											@foreach($courses as $key=>$value)
											<option value="{{$key}}">{{$value}}</option>
											@endforeach
										</select>
										 @if($errors->has('course')) <span class="help-block errorProfilePic">{{$errors->first('course') }}</span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{($errors->has('name'))?'has-error':''}}">
										<label>Beacon Name</label> 
										<input type="text" class="form-control" name="name">
										 @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('UUID'))?'has-error':''}}">
										<label>UDID</label> <input type="text" class="form-control" name="UUID">
										 @if($errors->has('UUID')) <span class="help-block errorProfilePic">{{$errors->first('UUID') }}</span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{($errors->has('major'))?'has-error':''}}">
										<label>Major</label> 
										<input type="text" class="form-control" name="major">
										 @if($errors->has('major')) <span class="help-block errorProfilePic">{{$errors->first('major') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('minor'))?'has-error':''}}">
										<label>Minor</label> 
										<input type="text" class="form-control" name="minor">
										 @if($errors->has('minor')) <span class="help-block errorProfilePic">{{$errors->first('minor') }}</span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{($errors->has('firstName'))?'has-error':''}}">
										<label>Record Check In</label> 
										<select name="record-check-in" class="form-control">
											<option value="1">Near</option>
											<option value="2">Far</option>
											<option value="3">Immediate</option>
										</select>
										 @if($errors->has('firstName')) <span class="help-block errorProfilePic">{{$errors->first('firstName') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Near.action'))?'has-error':''}}">
										<label>Near</label> 
										<select name="Near[action]" class="form-control" id="near" v-model="nearSelectedValue">
											@foreach(\Config::get ( 'global.beacon_actions') as $action=>$value)
												<option value="{{$action}}">{{$value}}</option>
											@endforeach
										</select>
										 @if($errors->has('Near.action')) <span class="help-block errorProfilePic">{{$errors->first('Near.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-near" v-if="nearCustomMessageVisibility">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Near.custom'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Near[message]" class="form-control" placeholder="Your Message" ></textarea>
											 @if($errors->has('Near.message')) <span class="help-block errorProfilePic">{{$errors->first('Near.message') }}</span> @endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Immediate.action'))?'has-error':''}}">
										<label>Immediate</label> 
										<select name="Immediate[action]" class="form-control" id="immediate" v-model="immediateSelectedValue" >
											@foreach(\Config::get ( 'global.beacon_actions') as $action=>$value)
												<option value="{{$action}}">{{$value}}</option>
											@endforeach
										</select>
										 @if($errors->has('Immediate.action')) <span class="help-block errorProfilePic">{{$errors->first('Immediate.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-immediate"  v-if="immediateCustomMessageVisibility">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Immediate.message'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Immediate[message]" class="form-control" placeholder="Your Message"></textarea>
											 @if($errors->has('Immediate.message')) <span class="help-block errorProfilePic">{{$errors->first('Immediate.message') }}</span> @endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Far.action'))?'has-error':''}}">
										<label>Far</label> 
										<select name="Far[action]" class="form-control" id="far" v-model="farSelectedValue">
											@foreach(\Config::get ( 'global.beacon_actions') as $action=>$value)
												<option value="{{$action}}">{{$value}}</option>
											@endforeach
										</select>
										 @if($errors->has('Far.action')) <span class="help-block errorProfilePic">{{$errors->first('Far.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-far" v-if="farCustomMessageVisibility">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Far.custom'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Far[message]" class="form-control" placeholder="Your Message"></textarea>
											 @if($errors->has('Far.message')) <span class="help-block errorProfilePic">{{$errors->first('Far.message') }}</span> @endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group clearfix">
										<div class="checkbox-inline">
											<span class="pull-left"><label>
												<input type="checkbox" value="Active" name="status"> &nbsp;Activate Beacon</label>
											</span>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-def">
										<i class="fa fa-floppy-o"></i> &nbsp; Configure Beacon
									</button>
									&nbsp;&nbsp; <a class="btn btn-outline b-primary text-primary"
										href="{{route('admin.beacon.index')}}"><i class="fa fa-ban"></i>
										&nbsp; Cancel</a>
								</div>
							</div>
						</form>
					</div>
					<!-- beacon create -->
				</div>
			</div>
		</div>
	</div>

@endSection
@section('page-specific-scripts')

	<script>

		var vue = new Vue({
			el: "#vue-config-container",
			data: {
				nearSelectedValue:"{{old('Near.action')  ? old('Near.action') :'' }}",
				immediateSelectedValue:"{{old('Immediate.action')  ? old('Immediate.action') :'' }}",
				farSelectedValue:"{{old('Far.action')  ? old('Far.action') :'' }}",

			},
			computed:{
				nearCustomMessageVisibility:function(){

					return this.nearSelectedValue == "customMessage" ? true : false;
				},
				immediateCustomMessageVisibility:function(){

					return this.immediateSelectedValue == "customMessage" ? true : false;
				},

				farCustomMessageVisibility:function(){

					return this.farSelectedValue == "customMessage" ? true : false;
				},

			},
			methods:{

			}

		});




	</script>

@endSection