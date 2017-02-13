@extends('admin.__layouts.admin-layout')
@section('main')
    <div id="content" class="app-content box-shadow-z0" role="main">
        <div class="app-header white box-shadow">
            <div class="navbar">
                <!-- Open side - Naviation on mobile -->
                <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"> <i class="material-icons">&#xe5d2;</i> </a>
                <!-- / -->
                <!-- Page title - Bind to $state's title -->
                <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
                <!-- navbar right -->
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item dropdown pos-stc-xs"> <a class="nav-link" href data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span class="label label-sm up warn">3</span> </a>

                    </li>
                    <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="../../assets/images/a0.jpg" alt="..."> <i class="on b-white bottom"></i> </span> </a>
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" href="{{route('admin.profile.profile')}}">
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#/access/signin">Sign out</a>
                        </div>
                    </li>
                    <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3> <span>Configure Beacon</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="beacon-configure">
                            <form action="#." method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select name="course" class="form-control">
                                                <option value="1">Course 1</option>
                                                <option value="2">Course 2</option>
                                                <option value="3">Course 3</option>
                                                <option value="4">Course 4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Beacon Name</label>
                                            <input type="text" class="form-control" name="beacon-name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>UDID</label>
                                            <input type="text" class="form-control" name="udid">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Major</label>
                                            <input type="text" class="form-control" name="major">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minor</label>
                                            <input type="text" class="form-control" name="minor">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Record Check In</label>
                                            <select name="record-check-in" class="form-control">
                                                <option value="1">Near</option>
                                                <option value="2">Far</option>
                                                <option value="3">Immediate</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Near</label>
                                            <select name="near" class="form-control" id="near">
                                                <option value="1">Wellcome Message</option>
                                                <option value="2">Checkin</option>
                                                <option value="3">Checkout</option>
                                                <option value="4">Custom Message</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="custom-message-near" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Custom Message</label>
                                                <textarea name="custom-message-near" class="form-control" placeholder="Your Message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Immediate</label>
                                            <select name="immediate" class="form-control" id="immediate">
                                                <option value="1">Wellcome Message</option>
                                                <option value="2">Checkin</option>
                                                <option value="3">Checkout</option>
                                                <option value="4">Custom Message</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="custom-message-immediate" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Custom Message</label>
                                                <textarea name="custom-message-immediate" class="form-control" placeholder="Your Message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Far</label>
                                            <select name="far" class="form-control" id="far">
                                                <option value="1">Wellcome Message</option>
                                                <option value="2">Checkin</option>
                                                <option value="3">Checkout</option>
                                                <option value="4">Custom Message</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="custom-message-far" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Custom Message</label>
                                                <textarea name="custom-message-far" class="form-control" placeholder="Your Message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group clearfix">
                                            <div class="checkbox-inline">
                                                <span class="pull-left"><label><input type="checkbox" value="">Activate Beacon</label></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp; Configure Beacon</button>
                                        &nbsp;&nbsp;
                                        <a class="btn btn-outline b-primary text-primary" href="{{route('admin.beacon.beacon')}}"><i class="fa fa-ban"></i> &nbsp; Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div><!-- beacon create -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endSection