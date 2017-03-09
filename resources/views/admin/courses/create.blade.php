@extends('admin.__layouts.admin-layout')
@section('heading')
    Add Course
    @endSection
@section('main')
    <div class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div class="profile-main padding" id="selectionDepHidden">
            <div class="row details-section">
                <form action="#." name="" action="">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-control-label">Course Name</label> <input type="text"
                                                                                  class="form-control" />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Open Time</label>
                                    <input type="text" class="form-control" placeholder="AM" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Close Time</label>
                                    <input type="text" class="form-control" placeholder="PM" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Booking Interval</label>
                                    <input type="number" class="form-control" placeholder="Minutes" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Booking Duration</label>
                                    <input type="number" class="form-control" placeholder="Minutes" />
                                </div>
                            </d iv>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-control-label">Number of Holes</label>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="number" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <a href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i>Add Member</a> &nbsp;&nbsp;
                                <a href="{{route('admin.courses.index')}}" class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endSection
