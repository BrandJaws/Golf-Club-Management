@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Course
    @endSection
@section('main')
    <div class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div class="profile-main padding" id="selectionDepHidden">
            <div class="row details-section">
                <form action="{{route('admin.courses.store')}}" name="" method="post">
               		@if(Session::has('error'))
                    <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                    @endif
                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                    @endif
                    <input type="hidden" name="_method" value="POST" />
				    	{{ csrf_field() }}
                    <div class="col-md-8">
                        <div class="form-group {{($errors->has('name'))?'has-error':''}}">
                            <label class="form-control-label">Course Name</label> 
                            <input type="text"  class="form-control" name="name" value="{{Request::old('name')}}"/>
                            @if($errors->has('name')) <span class="help-block errorProfilePic">{{$errors->first('name') }}</span> @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('openTime'))?'has-error':''}}">
                                    <label class="form-control-label">Open Time</label>
                                    <input type="time" class="form-control" placeholder="AM" name="openTime" value="{{Request::old('openTime')}}"/>
                                    @if($errors->has('openTime')) <span class="help-block errorProfilePic">{{$errors->first('openTime') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('closeTime'))?'has-error':''}}">
                                    <label class="form-control-label">Close Time</label>
                                    <input type="time" class="form-control" placeholder="PM" name="closeTime" value="{{Request::old('closeTime')}}"/>
                                    @if($errors->has('closeTime')) <span class="help-block errorProfilePic">{{$errors->first('closeTime') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('bookingInterval'))?'has-error':''}}">
                                    <label class="form-control-label">Booking Interval</label>
                                    <input type="number" class="form-control" placeholder="Minutes" name="bookingInterval" value="{{Request::old('bookingInterval')}}"/>
                                    @if($errors->has('bookingInterval')) <span class="help-block errorProfilePic">{{$errors->first('bookingInterval') }}</span> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('bookingDuration'))?'has-error':''}}">
                                    <label class="form-control-label">Booking Duration</label>
                                    <input type="number" class="form-control" placeholder="Minutes" name="bookingDuration" value="{{Request::old('bookingDuration')}}"/>
                                    @if($errors->has('bookingDuration')) <span class="help-block errorProfilePic">{{$errors->first('bookingDuration') }}</span> @endif
                                </div>
                            </d iv>
                        </div>
                        <div class="">
                            <div class="col-md-6">
                                <div class="form-group {{($errors->has('numberOfHoles'))?'has-error':''}}">
                                    <label class="form-control-label">Number of Holes</label>
                                    <input type="number" class="form-control" name="numberOfHoles" value="{{Request::old('numberOfHoles')}}"/>
                                    @if($errors->has('numberOfHoles')) <span class="help-block errorProfilePic">{{$errors->first('numberOfHoles') }}</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="">
                       		<div class="col-md-12">
									<div class="form-group clearfix">
                                        <div class="checkbox">
                                            <label class="ui-check">
                                                <input type="checkbox" value="open" name="status">
                                                <i class="dark-white"></i>
                                                Is Open?
                                            </label>
                                        </div>
										<div class="checkbox-inline">
											<span class="pull-left"><label>

											</span>
										</div>
									</div>
								</div>
                                
								<!--Tees HTML-->
                                <div class="col-md-6">
                                    <div class="form-group {{($errors->has('numberOfTees'))?'has-error':''}}">
                                        <label class="form-control-label">Number of Tees</label>
                                        <input type="number" class="form-control" name="numberOfTees" value="{{Request::old('numberOfTees')}}"/>
                                        @if($errors->has('numberOfTees')) <span class="help-block errorProfilePic">{{$errors->first('numberOfTees') }}</span> @endif
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-12">
                                    <div class="form-group {{($errors->has('configureTees'))?'has-error':''}}">
                                        <label class="form-control-label">Configure Tees</label>
                                        <input type="number" class="form-control" name="configureTees" value="{{Request::old('configureTees')}}"/>
                                        @if($errors->has('configureTees')) <span class="help-block errorProfilePic">{{$errors->first('configureTees') }}</span> @endif
                                    </div>
                                    <div class="row">
                                    <div class="col-sm-12"><label class="form-control-label">Select Tees Color</label></div>
                                    	<div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        	<div class="form-group">
                                                <select name="tees" id="" class="form-control tees-colors">
                                                    <option value="">Please Select</option>
                                                    <option value="#309b00" class="hole"> Hole </option>
                                                    <option value="#ffcccb" class="pink"> Pink </option>
                                                    <option value="#000000" class="black"> Black </option>
                                                    <option value="#ffcc00" class="gold">Gold</option>
                                                    <option value="#0100fc" class="blue">Blue</option>
                                                    <option value="#eeeeee" class="silver">Silver</option>
                                                    <option value="#006600" class="green">Green</option>
                                                    <option value="#ffffff" class="white">White</option>
                                                    <option value="#6734ff" class="purple">Purple</option>
                                                    <option value="#ff6600" class="orange">Orange</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-tees">
                                    <div class="inner-header row">
                                      <div>
                                        <div class="col-md-4">
                                          <div class="inner-page-heading text-left">
                                            <h3>Configure Tees</h3>
                                          </div>
                                        </div>
                                        <div class="col-md-8">
                                          <div class="search-form text-right">
                                            <form action="#." method="post">
                                              <div class="search-field"><span class="search-box">
                                                <input type="text" name="search" class="search-bar">
                                                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
                                                </span></div>
                                            </form>
                                          </div>
                                        </div>
                                        <div class="clearfix"></div>
                                      </div>
                                    </div>

                                    <label class="form-control-label">Hole 1</label>
                                    <ul class="list-tees">
                                        <li class="hole"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Yellow</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="pink"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Blue</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="black"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Green</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="gold"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="blue"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="silver"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="green"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="white"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="purple"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                        <li class="orange"><div class="form-group {{($errors->has('hole1'))?'has-error':''}} yellow">
                                            <div class="col-sm-10"><label class="form-control-label">Hole</label></div>
                                            <div class="col-sm-2"><input type="number" class="form-control" name="hole1" value="{{Request::old('hole1')}}"/></div>
                                        </div></li>
                                    </ul>
                                       <div class="row"> <div class="col-sm-6">
                                        <div class="form-group {{($errors->has('menHandiCap'))?'has-error':''}}">
                                            <label class="form-control-label">Men's Handicap Tees</label>
                                            <input type="number" class="form-control" name="menHandiCap" value="{{Request::old('menHandiCap')}}"/>
                                            @if($errors->has('menHandiCap')) <span class="help-block errorProfilePic">{{$errors->first('menHandiCap') }}</span> @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{($errors->has('menPar'))?'has-error':''}}">
                                            <label class="form-control-label">Men's Par</label>
                                            <input type="number" class="form-control" name="menPar" value="{{Request::old('menPar')}}"/>
                                            @if($errors->has('menPar')) <span class="help-block errorProfilePic">{{$errors->first('menPar') }}</span> @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{($errors->has('womenHandiCap'))?'has-error':''}}">
                                            <label class="form-control-label">Women's Handicap Tees</label>
                                            <input type="number" class="form-control" name="womenHandiCap" value="{{Request::old('womenHandiCap')}}"/>
                                            @if($errors->has('womenHandiCap')) <span class="help-block errorProfilePic">{{$errors->first('womenHandiCap') }}</span> @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{($errors->has('womenPar'))?'has-error':''}}">
                                            <label class="form-control-label">Women's Par</label>
                                            <input type="number" class="form-control" name="womenPar" value="{{Request::old('womenPar')}}"/>
                                            @if($errors->has('menHandiCap')) <span class="help-block errorProfilePic">{{$errors->first('womenPar') }}</span> @endif
                                        </div>
                                    </div></div>
                                    <div class="form-group text-right">
                                    <a href="#." class="btn btn-def disabled">
                                        <i class="fa fa-arrow-left"></i> &nbsp;Prev
                                    </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="#." class="btn btn-def">
                                        Next &nbsp; <i class="fa fa-arrow-right"></i>
                                    </a>
                                   
                                    
                                </div>
                                    </div>
                                </div>
                                <div class="col-sm-12"><div class="form-group">
                                <button type="submit" class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Add Course</button> &nbsp;&nbsp;
                                <a href="{{route('admin.courses.index')}}" class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div></div>
                                
                        
                        
                        
                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endSection
