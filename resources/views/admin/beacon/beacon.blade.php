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
							src="../assets/images/a0.jpg" alt="..."> <i
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
						<span>Beacon</span>
					</h3>
				</div>
			</div>
			<!-- / navbar collapse -->
		</div>
	</div>
	{{--
	<div class="app-footer">
		--}} {{--
		<div class="p-a text-xs">
			--}} {{--
			<div class="pull-right text-muted">
				&copy; Copyright <strong>Grit Golf</strong> <span
					class="hidden-xs-down">- Built with Love v1.1.3</span> <a
					ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
			</div>
			--}} {{--
			<div class="nav">
				<a class="nav-link" href="../">About</a> <span class="text-muted">-</span>
				<a class="nav-link label accent"
					href="http://themeforest.net/user/flatfull/portfolio?ref=flatfull">Get
					it</a>
			</div>
			--}} {{--
		</div>
		--}} {{--
	</div>
	--}}
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="beacon-list-table" class="segments-main padding">
			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
							<div class="">
								<div class="col-md-8">
									<div class="search-form">
										<form action="#." method="post">
											<div class="search-field">
												<span class="search-box"> <input type="text" name="search"
													class="search-bar">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{Request::url()}}/create" class="btn-def btn"><i
										class="fa fa-cogs"></i>&nbsp;Configure Beacon</a> {{--
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
									--}}
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<beacon :beacon-list="beaconList"> </beacon>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('admin.__vue_components.beacon.beacon-list');
<script>
        var baseUrl = "{{url('')}}";
        _beaconList = [{name:'John Wick',court:'Test Court One',major:'0',minor:'0',duration:'12:15',status:'Available'},
            {name:'Johnny Depp',court:'Test Court Two',major:'0',minor:'0',duration:'12:15',status:'Available'},
            {name:'Hugh Jackman',court:'Test Court Three',major:'0',minor:'0',duration:'12:15',status:'Busy'},
            {name:'Rupert Grint',court:'Test Court Four',major:'0',minor:'0',duration:'12:15',status:'Busy'},
            {name:'Daniel Radcliffe',court:'Test Court Five',major:'0',minor:'0',duration:'12:15',status:'Available'},
            {name:'Robert Dworney Jr.',court:'Test Court Six',major:'0',minor:'0',duration:'12:15',status:'Busy'},
            {name:'Evan Jogia',court:'Test Court Seven',major:'0',minor:'0',duration:'12:15',status:'Busy'},
            {name:'Robert Atinkson',court:'Test Court Eight',major:'0',minor:'0',duration:'12:15',status:'Available'},
            {name:'Chris Black',court:'Test Court Nine',major:'0',minor:'0',duration:'12:15',status:'Busy'}];

        var vue = new Vue({
            el: "#beacon-list-table",
            data: {
                beaconList:[],
                latestPageLoaded:0,
                ajaxRequestInProcess:false
            },
            methods: {
                loadNextPage:function(){
                    if(this.latestPageLoaded == 0){
                        for(x=0; x<_beaconList.length; x++){
                            this.beaconList.push(_beaconList[x]);
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