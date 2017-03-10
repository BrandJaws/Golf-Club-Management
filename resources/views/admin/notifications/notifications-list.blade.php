@extends('admin.__layouts.admin-layout')
@section('heading')
    Notifications
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="notifications-list-table" class="segments-main padding">
			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
							<div class="">
								<div class="col-md-8">
									<h3>
										<span>Notifications List</span>
									</h3>
								</div>
								<div class="col-md-4 text-right">
									<a class="btn-def btn" href="{{Request::url()}}/create"><i
										class="fa fa-plus-circle"></i>&nbsp;Create Notification</a>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<notifications :notifications-list="notificationsList"> </notifications>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-specific-scripts')
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
