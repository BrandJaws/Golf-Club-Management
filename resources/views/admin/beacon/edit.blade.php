@extends('admin.__layouts.admin-layout')
@section('heading')
	Edit Beacon
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<div class="beacon-configure">
						<form action="{{route('admin.beacon.update', $beacon->id)}}" method="post" enctype="multipart/form-data">
    						@if(Session::has('error'))
                            	<div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                            @endif
                            @if(Session::has('success'))
                            	<div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                            @endif
                        <input type="hidden" name="_method" value="PUT" />
				    	{{ csrf_field() }}
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('course'))?'has-error':''}}">
										<label>Course</label> 
										<select name="course" class="form-control">
											<option value="0">Please Select</option>
											@foreach($courses as $key=>$value)
												<option value="{{$key}}" {{($key == $beacon->course_id)?'selected="selected"':''}}>{{$value}}</option>
											@endforeach
										</select>
										 @if($errors->has('course')) <span class="help-block errorProfilePic">{{$errors->first('course') }}</span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{($errors->has('name'))?'has-error':''}}">
										<label>Beacon Name</label> 
										<input type="text" class="form-control" name="name" value="{{(old('name')?old('name'):$beacon->name)}}">
										 @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('UUID'))?'has-error':''}}">
										<label>UDID</label> <input type="text" class="form-control" name="UUID" value="{{(old('UUID')?old('UUID'):$beacon->UUID)}}">
										 @if($errors->has('UUID')) <span class="help-block errorProfilePic">{{$errors->first('UUID') }}</span> @endif
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group {{($errors->has('major'))?'has-error':''}}">
										<label>Major</label> 
										<input type="text" class="form-control" name="major" value="{{(old('major')?old('major'):$beacon->major)}}">
										 @if($errors->has('major')) <span class="help-block errorProfilePic">{{$errors->first('major') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group {{($errors->has('minor'))?'has-error':''}}">
										<label>Minor</label> 
										<input type="text" class="form-control" name="minor" value="{{(old('minor')?old('minor'):$beacon->minor)}}">
										 @if($errors->has('minor')) <span class="help-block errorProfilePic">{{$errors->first('minor') }}</span> @endif
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Near.action'))?'has-error':''}}">
										<label>Near</label> 
										<select name="Near[action]" class="form-control" id="near">
											<option value="welcome" {{(old('Near.action') == 'welcome')?'selected="selected"':($configuration->hasAction('Near','welcome'))?'selected="selected"':''}}>Wellcome Message</option>
											<option value="checkin" {{(old('Near.action') == 'checkin')?'selected="selected"':($configuration->hasAction('Near','checkin'))?'selected="selected"':''}}>Checkin</option>
											<option value="checkout" {{(old('Near.action') == 'checkout')?'selected="selected"':($configuration->hasAction('Near','checkout'))?'selected="selected"':''}}>Checkout</option>
											<option value="custom" {{(old('Near.action') == 'custom')?'selected="selected"':($configuration->hasAction('Near','custom'))?'selected="selected"':''}}>Custom Message</option>
										</select>
										 @if($errors->has('Near.action')) <span class="help-block errorProfilePic">{{$errors->first('Near.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-near" style="{{(old('Near.action') == 'custom')?'':($configuration->hasAction('Near','custom'))?'':'display:none'}}">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Near.custom'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Near[message]" class="form-control" placeholder="Your Message">{{(old('Near.message'))?old('Near.message'):$configuration->getMessage('Near')}}</textarea>
											 @if($errors->has('Near.message')) <span class="help-block errorProfilePic">{{$errors->first('Near.message') }}</span> @endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Immediate.action'))?'has-error':''}}">
										<label>Immediate</label> 
										<select name="Immediate[action]" class="form-control" id="immediate">
											<option value="welcome" {{(old('Immediate.action') == 'welcome')?'selected="selected"':($configuration->hasAction('Immediate','welcome'))?'selected="selected"':''}}>Wellcome Message</option>
											<option value="checkin" {{(old('Immediate.action') == 'checkin')?'selected="selected"':($configuration->hasAction('Immediate','checkin'))?'selected="selected"':''}}>Checkin</option>
											<option value="checkout" {{(old('Immediate.action') == 'checkout')?'selected="selected"':($configuration->hasAction('Immediate','checkout'))?'selected="selected"':''}}>Checkout</option>
											<option value="custom" {{(old('Immediate.action') == 'custom')?'selected="selected"':($configuration->hasAction('Immediate','custom'))?'selected="selected"':''}}>Custom Message</option>
										</select>
										 @if($errors->has('Immediate.action')) <span class="help-block errorProfilePic">{{$errors->first('Immediate.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-immediate" style="{{(old('Immediate.action') == 'custom')?'':($configuration->hasAction('Immediate','custom'))?'':'display:none'}}">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Immediate.message'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Immediate[message]" class="form-control" placeholder="Your Message" >{{(old('Immediate.message'))?old('Immediate.message'):$configuration->getMessage('Immediate')}}</textarea>
											 @if($errors->has('Immediate.message')) <span class="help-block errorProfilePic">{{$errors->first('Immediate.message') }}</span> @endif
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group {{($errors->has('Far.action'))?'has-error':''}}">
										<label>Far</label> 
										<select name="Far[action]" class="form-control" id="far">
											<option value="welcome" {{(old('Far.action') == 'welcome')?'selected="selected"':($configuration->hasAction('Far','welcome'))?'selected="selected"':''}}>Wellcome Message</option>
											<option value="checkin" {{(old('Far.action') == 'checkin')?'selected="selected"':($configuration->hasAction('Far','checkin'))?'selected="selected"':''}}>Checkin</option>
											<option value="checkout" {{(old('Far.action') == 'checkout')?'selected="selected"':($configuration->hasAction('Far','checkout'))?'selected="selected"':''}}>Checkout</option>
											<option value="custom" {{(old('Far.action') == 'custom')?'selected="selected"':($configuration->hasAction('Far','custom'))?'selected="selected"':''}}>Custom Message</option>
										</select>
										 @if($errors->has('Far.action')) <span class="help-block errorProfilePic">{{$errors->first('Far.action') }}</span> @endif
									</div>
								</div>
							</div>
							<div id="custom-message-far" style="{{(old('Far.action') == 'custom')?'':($configuration->hasAction('Far','custom'))?'':'display:none'}}">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group {{($errors->has('Far.custom'))?'has-error':''}}">
											<label>Custom Message</label>
											<textarea name="Far[message]" class="form-control" placeholder="Your Message">{{(old('Far.message'))?old('Far.message'):$configuration->getMessage('Far')}}</textarea>
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
												<input type="checkbox" value="Active" name="status" {{($beacon->status == config('global.status.active'))?'checked="checked"':''}}>Activate Beacon</label>
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
