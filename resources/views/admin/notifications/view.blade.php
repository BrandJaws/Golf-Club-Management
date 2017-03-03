@extends('admin.__layouts.admin-layout') @section('main')
    <div id="content" class="app-content box-shadow-z0" role="main">
        <div class="app-header white box-shadow">
            <div class="navbar">
                <!-- Open side - Naviation on mobile -->
                <a data-toggle="modal" data-target="#aside"
                   class="navbar-item pull-left hidden-lg-up"> <i
                            class="material-icons">&#xe5d2;</i>
                </a>
                <!-- / -->
                <!-- Page title - Bind to $state's title -->
                <div class="navbar-item pull-left h5"
                     ng-bind="$state.current.data.title" id="pageTitle"></div>
                <!-- navbar right -->
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item dropdown pos-stc-xs"><a class="nav-link" href
                                                                data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span
                                    class="label label-sm up warn">3</span>
                        </a>
                        <div ui-include="'../views/blocks/dropdown.notification.html'"></div>
                    </li>
                    <li class="nav-item dropdown"><a class="nav-link clear" href
                                                     data-toggle="dropdown"> <span class="avatar w-32"> <img
                                        src="../../assets/images/a0.jpg" alt="..."> <i
                                        class="on b-white bottom"></i>
					</span>
                        </a>
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" ui-sref="app.inbox.list"
                               href="{{route('admin.profile.profile')}}"> <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" ui-sref="access.signin"
                               href="#/access/signin">Sign out</a>
                        </div></li>
                    <li class="nav-item hidden-md-up"><a class="nav-link"
                                                         data-toggle="collapse" data-target="#collapse"> <i
                                    class="material-icons">&#xe5d4;</i>
                        </a></li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3>
                            <span>View Notification</span>
                        </h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="row notificationsCreateSec">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Title:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Fantastic Start to Sunday Lunch Promotion
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Sent on:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Jan 25, 2017
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Status:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData statusSuccess">
                                    Sent
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="notiViewInfo">Description:</p>
                            </div>
                            <div class="col-md-8">
                                <p class="notiViewData">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi atque dolorem doloremque excepturi laborum, necessitatibus officia officiis repellendus! Distinctio fugit, ipsum mollitia nobis odio perspiciatis tempore veritatis? A fugiat, pariatur?
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn-def btn">
                                <i class="fa fa-repeat"></i> &nbsp;Resend
                            </button>
                            <button class="btn-def btn">
                                <i class="fa fa-ban"></i> &nbsp;Cancel
                            </button>
                            <a href="{{route("admin.notifications.notifications")}}" class="btn btn-outline b-primary text-primary">Return to Notifications</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        {{--<input type="date" class="hide-replaced" />--}}
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
