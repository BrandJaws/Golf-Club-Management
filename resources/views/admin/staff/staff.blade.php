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
                        <h3> <span>Staff</span></h3>
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
            <div id="staff-list-table" class="segments-main padding">
                <div class="row">
                    <div class="segments-inner">
                        <div class="box">
                            <div class="inner-header">
                                <div class="">
                                    <div class="col-md-8">
                                        <div class="search-form">
                                            <form action="#." method="post">
                                                <div class="search-field">
	                                    	<span class="search-box">
	                                        	<input type="text" name="search" class="search-bar">
	                                            <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
	                                        </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="{{Request::url()}}/create" class="btn-def btn"><i class="fa fa-plus-circle"></i>&nbsp;Add Members</a>
                                        <button class="btn-def btn"><i class="fa fa-upload"></i>&nbsp;Import CSV</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div><!-- inner header -->
                            <staff :staff-list="staffList">
                            </staff>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin.__vue_components.staff.staff-table")
    <script>
        var baseUrl = "{{url('')}}";
        _staffList = [{name:'John Wick',email:'john.wick@mail.com',contact:'0423456783',role:'Keeper'},
            {name:'Johnny Depp',email:'johnny.depp@mail.com',contact:'042343284',role:'Actor'},
            {name:'Emma Watson',email:'emma.watson@mail.com',contact:'98323456783',role:'Actress'},
            {name:'Emma Brows',email:'emma.brown@mail.com',contact:'0423656783',role:'Actress'},
            {name:'Harry Potter',email:'harry.potter@mail.com',contact:'34563456783',role:'Wizard'},
            {name:'Atif Aslam',email:'atif.aslam@mail.com',contact:'65472836399',role:'Singer'},
            {name:'Emma Watson',email:'emma.watson@mail.com',contact:'98323456783',role:'Actress'},
            {name:'Emma Brows',email:'emma.brown@mail.com',contact:'0423656783',role:'Actress'},
            {name:'Harry Potter',email:'harry.potter@mail.com',contact:'34563456783',role:'Wizard'},];

        var vue = new Vue({
            el: "#staff-list-table",
            data: {
                staffList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_staffList.length; x++){
                            this.staffList.push(_staffList[x]);
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