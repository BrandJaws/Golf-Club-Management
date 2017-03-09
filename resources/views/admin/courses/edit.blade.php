@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Course
    @endSection
@section('main')
    <div class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div class="profile-main padding" id="selectionDepHidden">
            <div class="row details-section">
                <form action="{{route('admin.courses.update',$course['id'])}}" name="" method="post">
               		@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
                    <input type="hidden" name="_method" value="PUT" />
				    	{{ csrf_field() }}
                    <div class="col-md-8">
                        <div class="form-group {{($errors->has('name'))?'has-error':''}}">
                            <label class="form-control-label">Course Name</label> 
                            <input type="text"  class="form-control" name="name" value="{{$course['name']}}"/>
                            @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('openTime'))?'has-error':''}}">
                                    <label class="form-control-label">Open Time</label>
                                    <input type="time" class="form-control" placeholder="AM" name="openTime" value="{{$course['openTime']}}"/>
                                    @if($errors->has('openTime')) <span class="help-block errorProfilePic">{{$errors->first('openTime') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('closeTime'))?'has-error':''}}">
                                    <label class="form-control-label">Close Time</label>
                                    <input type="time" class="form-control" placeholder="PM" name="closeTime" value="{{$course['closeTime']}}"/>
                                    @if($errors->has('closeTime')) <span class="help-block errorProfilePic">{{$errors->first('closeTime') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('bookingInterval'))?'has-error':''}}">
                                    <label class="form-control-label">Booking Interval</label>
                                    <input type="number" class="form-control" placeholder="Minutes" name="bookingInterval" value="{{$course['bookingInterval']}}"/>
                                    @if($errors->has('bookingInterval')) <span class="help-block errorProfilePic">{{$errors->first('bookingInterval') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('bookingDuration'))?'has-error':''}}">
                                    <label class="form-control-label">Booking Duration</label>
                                    <input type="number" class="form-control" placeholder="Minutes" name="bookingDuration" value="{{$course['bookingDuration']}}"/>
                                    @if($errors->has('bookingDuration')) <span class="help-block errorProfilePic">{{$errors->first('bookingDuration') }}</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('numberOfHoles'))?'has-error':''}}">
                                    <label class="form-control-label">Number of Holes</label>
                                    <input type="number" class="form-control" name="numberOfHoles" value="{{$course['numberOfHoles']}}"/>
                                    @if($errors->has('numberOfHoles')) <span class="help-block errorProfilePic">{{$errors->first('numberOfHoles') }}</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                       		<div class="col-md-12">
									<div class="form-group clearfix">
										<div class="checkbox-inline">
											<span class="pull-left"><label>
												<input type="checkbox" value="open" name="status" {{(isset($course['status']) && $course['status'] == config('global.status.open'))?'checked="checked"':''}}> &nbsp;Is Open?</label>
											</span>
										</div>
									</div>
								</div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Update Course</button> &nbsp;&nbsp;
                                <a href="{{route('admin.courses.index')}}" class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endSection
