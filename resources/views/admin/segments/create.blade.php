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
                        <h3> <span>Create Segments</span></h3>
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
                                <label class="form-control-label">Segment Title</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-control-label">Age:
                                            <input type="text" id="amount" readonly style="border:0; color:#000; font-weight:bold;">
                                        </label>
                                    </div>
                                    <div class="col-md-8 inlineFormPadding">
                                        <div id="sliderRange"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row row-sm">
                                    <div class="col-md-4">
                                        <label class="form-control-label">Gender</label>
                                    </div>
                                    <div class="col-md-2 inlineFormPadding">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="gender" value="Male" class="has-value">
                                                <i class="dark-white"></i>
                                                Male
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2 inlineFormPadding">
                                        <div class="radio">
                                            <label class="ui-check">
                                                <input type="radio" name="gender" value="Female" class="has-value">
                                                <i class="dark-white"></i>
                                                Female
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Food</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Brand</label>
                                <input type="text" class="form-control" />
                            </div>
                            <br />
                            <div class="form-group">
                                <button class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Create Segment</button>
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
            $( "#sliderRange" ).slider({
                range: true,
                min: 18,
                max: 85,
                values: [22,50],
                slide: function( event, ui ) {
                    $( "#amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                }
            });
            $( "#amount" ).val($( "#sliderRange" ).slider( "values", 0 ) +
                " - " + $( "#sliderRange" ).slider( "values", 1 ) );
        } );

//        $( "#sliderRange" ).slider( "option", "values", [22,50] );

        $("form").submit(function(e){
            e.preventDefault();
           console.log($("#sliderRange").slider( "option", "values" ));
        });
    </script>
    @endSection