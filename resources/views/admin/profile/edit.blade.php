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
                        <div ui-include="'../views/blocks/dropdown.notification.html'"></div>
                    </li>
                    <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="../../assets/images/a0.jpg" alt="..."> <i class="on b-white bottom"></i> </span> </a>
                        <div ui-include="'../views/blocks/dropdown.user.html'"></div>
                    </li>
                    <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3> <span>Edit Profile</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding">
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="details-section">
                            <div class="image-thumb text-center">
                                <img src="../../assets/images/profile.jpg" class="img-circle img-responsive profileImg" alt="profile">
                            </div><!-- image thumb -->
                            <div class="detail-content text-center">
                                <a href="#." class="btn btn-outline b-primary text-primary m-y-xs">Change/Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-8">
                        <div class="profile-details">
                            <div class="edit-btn">
                                <a href="#." class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                                &nbsp;&nbsp;
                                <a href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Save</a>
                            </div>
                            <div class="profile-content">
                                <form action="#." method="post" class="profile-create">
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>Booking Differences</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <select class="form-control">
                                                        <option value="10min">10 Min</option>
                                                        <option value="20min">20 Min</option>
                                                        <option value="30min">30 Min</option>
                                                        <option value="40min">40 Min</option>
                                                        <option value="50min">50 Min</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>Courses</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <select class="form-control">
                                                        <option value="9">9 Holes</option>
                                                        <option value="18">18 Holes</option>
                                                        <option value="20">20 Holes</option>
                                                        <option value="30">30 Holes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-12">
                                                <div class="form-inline">
                                                    <div class="col-md-4">
                                                        <label>Opening Hours </label>
                                                        <select class="form-control">
                                                            <option value="4am">4 am</option>
                                                            <option value="5am">5 am</option>
                                                            <option value="6am">6 am</option>
                                                            <option value="7am">7 am</option>
                                                            <option value="8am">8 am</option>
                                                            <option value="9am">9 am</option>
                                                            <option value="10am">10 am</option>
                                                            <option value="11am">11 am</option>
                                                            <option value="12am">12 am</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>To</label>
                                                        <select class="form-control">
                                                            <option value="4am">4 am</option>
                                                            <option value="5am">5 am</option>
                                                            <option value="6am">6 am</option>
                                                            <option value="7am">7 am</option>
                                                            <option value="8am">8 am</option>
                                                            <option value="9am">9 am</option>
                                                            <option value="10am">10 am</option>
                                                            <option value="11am">11 am</option>
                                                            <option value="12am">12 am</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="heading-inner">
                                                <h3>Message Settings</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>Late Coming</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input type="text" name="late-coming" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>Before Time</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input type="text" name="before-time" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>On Time</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input type="text" name="on-time" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>Acceptable Time</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input type="text" name="accept-time" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>No Acceptable Time</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <input type="text" name="no-accept-time" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="margin-20-tb">
                                            <div class="col-md-4">
                                                <div class="text-left">
                                                    <label>No Acceptable Time</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="">
                                                    <textarea name="no-accept-time" class="form-control" placeholder="Your Message"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="heading-inner">
                                                <h3>About Me</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="heading-inner">
                                                <textarea class="form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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