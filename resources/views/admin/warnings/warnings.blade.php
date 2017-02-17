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
                                        src="{{asset("/assets/images/a0.jpg")}}" alt="..."> <i
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
                            <span>Warnings</span>
                        </h3>
                    </div>
                </div>
                <!-- / navbar collapse -->
            </div>
        </div>

        <div class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="profile-main padding" id="selectionDepHidden">
                <div class="row inner-header">
                    <div class="col-md-6">
                        <div class="inner-page-heading text-left"><h3>Warnings Listing</h3></div>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{route('admin.warnings.create')}}" class="btn-def btn"><i class="fa fa-plus-circle"></i> &nbsp;Add new warnings</a>
                    </div>
                </div>
                <div class="row bg-white">
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
    @endSection
