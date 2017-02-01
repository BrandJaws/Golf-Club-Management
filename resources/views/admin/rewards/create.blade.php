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
                        <h3> <span>Create Offer/Reward</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="row notificationsCreateSec">
                    <div class="col-md-8">
                        <form action="#." method="post">
                            <div class="form-group">
                                <label class="form-control-label">Offer/Reward Title</label>
                                <input type="text" name="offerTitle" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-group-label">Offer/Reward Description</label>
                                <textarea class="form-control" row="6"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Start Date</label>
                                        <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">End Date</label>
                                        <input type="date" class="form-control" data-date-inline-picker="false" data-date-open-on-focus="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Image</label>
                                <input type="file" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Choose Segment</label>
                                <select name="segmentName" id="segmentName" class="form-control select2-bootstrap-append">
                                    <option>Choose Segment</option>
                                    <option>Segment 01</option>
                                    <option>Segment 02</option>
                                    <option>Segment 03</option>
                                </select>
                            </div>
                            <br />
                            <div class="form-group">
                                <button class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Create Offer</button>
                                &nbsp; &nbsp;
                                <button class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">

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