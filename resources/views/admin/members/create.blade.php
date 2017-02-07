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
                        <h3> <span>Add Member</span></h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <div class="col-md-8">
                        <form action="#." name="" action="">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="email" class="form-control" />
                            </div>
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="form-group form-group-inline">
                                        <label class="form-control-label">Gender</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check">
                                                        <input type="radio" name="a" value="option1" class="has-value" checked>
                                                        <i class="dark-white"></i>
                                                        Male
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
                                                    <input type="radio" name="a" value="option1" class="has-value">
                                                    <i class="dark-white"></i>
                                                    Female
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="form-group form-group-inline">
                                        <label class="form-control-label">Member Type</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check">
                                                        <input v-model="memberType" type="radio" name="relation" value="parent" class="has-value" @change="affiliate()" />
                                                        <i class="dark-white"></i>
                                                        Parent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check">
                                                        <input v-model="memberType" type="radio" name="relation" value="affiliate" class="has-value" @change="affiliate()" />
                                                        <i class="dark-white"></i>
                                                        Affiliate Member
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm animated fadeInUp" v-cloak v-if="showParentSelector">
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-9">
                                    <div class="row row-sm">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Select Parent Member</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <auto-complete-box url="{{asset('people.json')}}"
                                                                   property-for-id="email"
                                                                   property-for-name="name"
                                                                   filtered-from-source="false"
                                                                   include-id-in-list="true"
                                                                   v-model="selectedId"
                                                                   initial-text-value="">
                                                </auto-complete-box>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <a href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i> &nbsp;Add Member</a>
                                &nbsp;&nbsp;
                                <a href="#." class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin.__vue_components.autocomplete.autocomplete")
    <script>
        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showParentSelector:false,
                memberType:'parent',
                selectedId: '',
            },
            methods: {
                affiliate:function() {
                    console.log(this.memberType);
                    if (this.memberType == 'affiliate') {
                        this.showParentSelector = true;
                    }
                    else {
                        this.showParentSelector = false;
                    }
                }
            }
        });
    </script>
    <script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection