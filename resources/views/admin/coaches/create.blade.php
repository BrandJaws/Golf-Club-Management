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
                        <h3> <span>Add Coaches</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form action="#." name="" action="">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="email" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Contact Number</label>
                                <input type="tel" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Specialities</label>
                                <input type="text" class="form-control" />
                                <span class="help-block m-b-none">Each separated with a comma.</span>
                            </div>
                            <br />
                            <div class="form-group">
                                <a href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Add Member</a>
                                &nbsp;&nbsp;
                                <a href="{{route('admin.coaches.coaches')}}" class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="../../assets/images/user.png" class="img-responsive img-circle defaultImg" />
                                <div class="form-group">
                                    <label class="form-control-label">Add Image</label>
                                    <input type="file" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endSection