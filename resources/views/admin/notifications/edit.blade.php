@extends('admin.__layouts.admin-layout')
@section('heading')
    Edit Notifications
    @endSection
@section('main')
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="row notificationsCreateSec">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-control-label">Title</label>
                            <input type="text" class="form-control" value="Fantastic Start to Sunday Lunch Promotion" />
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Description/Message</label>
                            <textarea class="form-control" rows="8">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque cumque deserunt dicta, distinctio dolor esse ex explicabo laborum magnam necessitatibus odit perspiciatis, placeat porro repudiandae saepe soluta tenetur voluptatem, voluptatum.</textarea>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Notification Type</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="ui-check">
                                            <input type="radio" checked name="notificationType" value="general" class="has-value">
                                            <i class="dark-white"></i>
                                            General
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="ui-check">
                                            <input type="radio" name="notificationType" value="bookingOnly" class="has-value">
                                            <i class="dark-white"></i>
                                            Booking Only
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn-def btn">
                                <i class="fa fa-floppy-o"></i> &nbsp;Save
                            </button>
                            <a href="{{route("admin.notifications.notifications")}}" class="btn btn-outline b-primary text-primary">Cancel</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{--
                        <div id="datePicker">--}} {{--</div>
					--}} <input type="date" value="2017-01-08" class="hide-replaced" />
                    </div>
                </div>
            </div>
        </div>

    <script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection
