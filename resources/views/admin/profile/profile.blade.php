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
                    <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="../assets/images/a0.jpg" alt="..."> <i class="on b-white bottom"></i> </span> </a>
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" ui-sref="app.inbox.list" href="{{route('admin.profile.profile')}}">
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" ui-sref="access.signin" href="#/access/signin">Sign out</a>
                        </div>
                    </li>
                    <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3> <span>Profile</span></h3>
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
                                <img src="../assets/images/profile.jpg" class="img-circle img-responsive profileImg" alt="profile">
                            </div><!-- image thumb -->
                            <div class="detail-content">
                                <h3>About Me</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-8">
                        <div class="profile-details">
                            <div class="edit-btn">
                                <a href="{{Request::url()}}/edit" class="btn btn-outline b-primary text-primary m-y-xs"><i class="fa fa-pencil"></i> &nbsp;Edit</a>
                            </div>
                            <div class="profile-content">
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Booking Differences</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>10 Min</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Courses</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>9 Hole</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Opening/ Closing Hours</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>10 Min</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="heading-inner">
                                            <h3>Message Settings</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Late Coming</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>You Are Late</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Before Time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>WOW! You Nailed!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>On Time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>Congratulations</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>Acceptable Time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>Well Played</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="margin-20-tb">
                                        <div class="col-md-6">
                                            <div class="text-right">
                                                <label>No Acceptable Time</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-left">
                                                <span>You Finished Late</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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