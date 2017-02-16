@extends('admin.__layouts.admin-layout') @section('main')
    <div id="content" class="app-content box-shadow-z0" role="main">
        <div class="app-header white box-shadow">
            <div class="navbar">
                <!-- Open side - Naviation on mobile -->
                <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up"> <i class="material-icons">&#xe5d2;</i>
                </a> 
                <!-- / -->
                <!-- Page title - Bind to $state's title -->
                <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
                <!-- navbar right -->
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item dropdown pos-stc-xs"><a class="nav-link" href="" data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span
                                    class="label label-sm up warn">3</span>
                        </a></li>
                    <li class="nav-item dropdown"><a class="nav-link clear" href
                                                     data-toggle="dropdown"> <span class="avatar w-32"> <img
                                        src="../../assets/images/a0.jpg" alt="..."> <i
                                        class="on b-white bottom"></i>
					</span>
                        </a>
                        <div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
                            <a class="dropdown-item" href="{{route('admin.profile.profile')}}">
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#/access/signin">Sign out</a>
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
                            <span>Edit Member</span>
                        </h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row details-section">
                    <form name="" action="{{route('admin.member.update',['member_id'=>$member->id])}}" method="post">
                    	<input type="hidden" name="_method" value="PUT" />
                    	{{ csrf_field() }}
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-control-label">First Name</label> <input type="text" name="firstName"  class="form-control" value="{{$member->firstName}} " />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Last Name</label> <input type="text" name="lastName" class="form-control" value="{{$member->lastName}} " />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Email</label> 
                                <input type="email" class="form-control" name="email" value="{{$member->email}}" />
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
                                                    	<input type="radio" name="gender" value="{{Config::get('global.gender.male')}}" class="has-value" {{($member->gender == Config::get('global.gender.male'))?'checked':''}}> <i class="dark-white"></i> Male
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <label class="ui-check">
													<input type="radio" name="gender"  value="{{Config::get('global.gender.female')}}" class="has-value" {{($member->gender == Config::get('global.gender.female'))?'checked':''}}> <i class="dark-white"></i>
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
                                                    <label class="ui-check"> <input v-model="memberType"  type="radio" name="relation" value="parent"  class="has-value" @change="affiliate()"  /> <i class="dark-white"></i> Parent
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label class="ui-check"> <input v-model="memberType"
                                                                                    type="radio" name="relation" value="affiliate"
                                                                                    class="has-value" @change="affiliate()" /> <i
                                                                class="dark-white"></i> Affiliate Member
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-sm animated fadeInUp" v-cloak
                                 v-if="showParentSelector">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <div class="row row-sm">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Select Parent Member</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <auto-complete-box url="{{asset('people.json')}}" property-for-id="email" property-for-name="name"
                                                                   filtered-from-source="false" include-id-in-list="true"
                                                                   v-model="selectedId" initial-text-value=""> </auto-complete-box>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <button type="submit"  class="btn btn-def"><i class="fa fa-floppy-o"></i>&nbsp;Update Member</button> &nbsp;&nbsp; <a
                                        href="{{route('admin.member.index')}}"
                                        class="btn btn-outline b-primary text-primary"><i
                                            class="fa fa-ban"></i> &nbsp;Cancel</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="../../assets/images/user.png"
                                     class="img-responsive img-circle defaultImg" />
                                <div class="form-group">
                                    <label class="form-control-label">Add Image</label> <input
                                            type="file" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="padding-small"></div>
                <div class="row bg-white">
                    <div class="col-md-12">
                        <div class="main-page-heading">
                            <h3>
                                <span>Warnings</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-12 padding-none">
                        <warnings :warnings="warnings"></warnings>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.warnings.warnings");
    <script>

        var baseUrl = "{{url('')}}";
        _warnings = [{name:'FORES',description:'Lorem impsul dolar esmit...',date:'Dec 9 2016 - 2:13:00 AM'},
                    {name:'NINE',description:'Lorem impsul dolar esmit...',date:'Dec 6 2016 - 2:13:00 AM'},
                    {name:'SOD',description:'Lorem impsul dolar esmit...',date:'Dec 2 2016 - 2:13:00 AM'},
                    {name:'APRON',description:'Lorem impsul dolar esmit...',date:'Jan 9 2017 - 2:13:00 AM'},
                    {name:'PAR',description:'Lorem impsul dolar esmit...',date:'Jan 4 2017 - 2:13:00 AM'},
                    {name:'PLAYBY',description:'Lorem impsul dolar esmit...',date:'Jan 10 2017 - 2:13:00 AM'},
                    {name:'TEE',description:'Lorem impsul dolar esmit...',date:'Jan 12 2017 - 2:13:00 AM'},
                    {name:'ROUGH',description:'Lorem impsul dolar esmit...',date:'Jan 19 2017 - 2:13:00 AM'},
                    ];

        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
                showParentSelector:false,
                memberType:'{{($member->main_member_id == 0)?'parent':'affiliate'}}',
                selectedId: '',
                warnings:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false,
            },
            methods: {
                affiliate:function() {
                    if (this.memberType == 'affiliate') {
                        this.showParentSelector = true;
                    }
                    else {
                        this.showParentSelector = false;
                    }
                },
                loadNextPage:function() {
                    //add sample data to array to check scroll functionality
                    if (this.latestPageLoaded == 0) {
                        for (x = 0; x < _warnings.length; x++) {
                            this.warnings.push(_warnings[x]);
                        }

                    }
                    return;
                }
            }
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
    <script>


        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
    @endSection
