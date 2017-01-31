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
                        <div ui-include="'../views/blocks/dropdown.user.html'"></div>
                    </li>
                    <li class="nav-item hidden-md-up"> <a class="nav-link" data-toggle="collapse" data-target="#collapse"> <i class="material-icons">&#xe5d4;</i> </a> </li>
                </ul>
                <!-- / navbar right -->

                <!-- navbar collapse -->
                <div class="collapse navbar-toggleable-sm" id="collapse">
                    <div class="main-page-heading">
                        <h3> <span>Notifications</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>
        {{--<div class="app-footer">--}}
            {{--<div class="p-a text-xs">--}}
                {{--<div class="pull-right text-muted"> &copy; Copyright <strong>Grit Golf</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>--}}
                {{--<div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="http://themeforest.net/user/flatfull/portfolio?ref=flatfull">Get it</a> </div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div id="notifications-list-table" class="segments-main padding">
                <div class="row">
                    <div class="segments-inner">
                        <div class="box">
                            <div class="inner-header">
                                <div class="">
                                    <div class="col-md-8">
                                        <h3><span>Notifications List</span></h3>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn-def btn" href="{{Request::url()}}/create"><i class="fa fa-plus-circle"></i>&nbsp;Create Notification</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- inner header -->
                            <notifications :notifications-list="notificationsList">
                            </notifications>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin.__vue_components.notifications.notifications-table")
    <script>
        var baseUrl = "{{url('')}}";
        _notificationsList = [{name:'Fantastic Start to Sunday Lunch Promotion',details:'Description goes here...',status:'sent',date:'Jan 25, 2017'},
            {name:'Our best ever full membership offer!!',details:'Description goes here...',status:'sent',date:'Jan 25, 2017'},
            {name:'Mother Day Sunday Lunch offer',details:'Description goes here...',status:'schedule',date:'Dec 25, 2016'},
            {name:'Our best ever full membership offer!!',details:'Description goes here...',status:'sent',date:'Oct 12, 2016'},
            {name:'Fantastic Start to Sunday Lunch Promotion',details:'Description goes here...',status:'schedule',date:'Aug 08, 2016'},
            {name:'Our best ever full membership offer!!',details:'Description goes here...',status:'schedule',date:'July 25, 2016'}];

        var vue = new Vue({
            el: "#notifications-list-table",
            data: {
                notificationsList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_notificationsList.length; x++){
                            this.notificationsList.push(_notificationsList[x]);
                        }

                    }
                    return;
                }
            },
        });

        $(document).ready(function() {
            vue.loadNextPage();
            console.log("bottom!");

        });
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                vue.loadNextPage();
                console.log("bottom!");
            }
        });
    </script>

    @endSection