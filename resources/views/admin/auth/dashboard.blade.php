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
							href="{{route('admin.logout')}}">Sign out</a>
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
						<span>Dashboard</span>
					</h3>
				</div>
			</div>
			<!-- / navbar collapse -->
		</div>
	</div>

	<div ui-view class="app-body" id="view">
        <!-- chat -->
        <div class="chat_box chat-shadow" style="z-index:99;">
            <div class="chat_head"> Chat Box</div>
            <div class="chat_body">
                <div class="user"> Krishna Teja</div>
            </div>
        </div>

        <div class="msg_box chat-shadow" style="right:290px;z-index:99;">
            <div class="msg_head">Krishna Teja
                <div class="close">x</div>
            </div>
            <div class="msg_wrap">
                <div class="msg_body">
                    <div class="msg_a">This is from A	</div>
                    <div class="msg_b">This is from B, and its amazingly kool nah... i know it even i liked it :)</div>
                    <div class="msg_a">Wow, Thats great to hear from you man </div>
                    <div class="msg_push"></div>
                </div>
                <div class="msg_footer"><textarea class="msg_input" rows="4"></textarea></div>
            </div>
        </div>
        <!-- chat -->
		<!-- ############ PAGE START-->
		<div class="padding">
			<div class="row dashboardQuickView">
				<div class="col-sm-6 col-md-4">
					<div class="box p-a" style="height: 80px;">
						<div class="pull-left m-r">
							<span ui-jp="sparkline" ui-refresh="app.setting.color"
								ui-options="[20,50,30], {type:'pie', height:36, sliceColors:['#f1f2f3','#0cc2aa','#fcc100']}"
								class="sparkline inline"><canvas width="36" height="36"
									style="display: inline-block; width: 36px; height: 36px; vertical-align: top;"></canvas></span>
						</div>
						<div class="clear">
							<h4 class="m-a-0 text-md">
								<a href="">50 <span class="text-sm">Members</span></a>
							</h4>
							<small class="text-muted">30 Guests. 20 Unused</small>
						</div>
					</div>
					{{--
					<div class="box">
						--}} {{--
						<div class="box-body">
							--}} {{--
							<div ui-jp="plot" ui-refresh="app.setting.color"
								ui-options="--}}
              {{--[{data: 20, label:&#x27;Members&#x27;}, {data: 50, label: &#x27;Guests&#x27;}, {data: 30, label:&#x27;Employees&#x27;}],--}}
              {{--{--}}
                {{--series: { pie: { show: true, innerRadius: 0.6, stroke: { width: 0 }, label: { show: true, threshold: 0.05 } } },--}}
                {{--legend: {backgroundColor: 'transparent'},--}}
                {{--colors: ['#0cc2aa','#fcc100'],--}}
                {{--grid: { hoverable: true, clickable: true, borderWidth: 0, color: 'rgba(120,120,120,0.5)' },   --}}
                {{--tooltip: true,--}}
                {{--tooltipOpts: { content: '%s: %p.0%' }--}}
              {{--}--}}
            {{--"
								style="height: 118px"></div>
							--}} {{--
						</div>
						--}} {{--
					</div>
					--}}
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="box p-a" style="height: 80px;">
						<div class="pull-left m-r">
							<span class="w-48 rounded  accent"> <i class="material-icons">&#xE8B4;</i>
							</span>
						</div>
						<div class="clear">
							<h4 class="m-a-0 text-lg _300">
								<a href>125 <span class="text-sm">Checkins</span></a>
							</h4>
							<small class="text-muted">6 new arrivals.</small>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-4">
					<div class="box p-a" style="height: 80px;">
						<div class="pull-left m-r">
							<span class="w-48 rounded primary"> <i class="material-icons">&#xE8D3;</i>
							</span>
						</div>
						<div class="clear">
							<h4 class="m-a-0 text-lg _300">
								<a href>400 <span class="text-sm">Members</span></a>
							</h4>
							<small class="text-muted">38 new.</small>
						</div>
					</div>
				</div>
			</div>


			<div class="">
				<div class="dashboard-tsheet">
					<div class="row">
						<div class="tsheet-header padd-15">
							<div class="col-md-8">
								<h2>Tee Sheet</h2>
							</div>
							<!-- col-6 -->
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="form-control"
										placeholder="Search for..."> <span class="input-group-btn">
										<button class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
								<!-- /input-group -->
							</div>
							<!-- col-6 -->
						</div>
					</div>
					<!-- row -->
					<div id="reservations-vue-container" class="row">
						<div class="col-md-12">
							<reservation-popup v-if="showPopup"
								:reservation="reservationToEdit" 
                                                                :reservation-type="reservationType" 
								@close-popup="closePopupTriggered"></reservation-popup>
							<reservation-tabs :reservations-parent="reservationsParent"
								style-for-show-more-tab="false"> <reservation-tab-heads
								:reservations-by-date="reservationsParent.reservationsByDate"
								show-more-tab="false"></reservation-tab-heads> <reservation-tab-tables
								:reservations-by-date="reservationsParent.reservationsByDate"
								@edit-reservation="editReservationEventTriggered" 
                                                                @new-reservation="newReservationEventTriggered"></reservation-tab-tables>
							</reservation-tabs>

						</div>
					</div>
				</div>
				<!-- dashboard tee sheet -->
			</div>
			<!-- padding -->
		</div>
		<!-- padding -->
	</div>
</div>
<div></div>

@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-tables")
@include("admin.__vue_components.reservations.reservation-popup")

<script>
var _reservationsParent =
            {
                club_id:'1',
                course_id: '1',
                reservationsByDate: [
                    {
                        reserved_at:'',
                        date: '29',
                        day: 'THURSDAY',
                        reservationsByTimeSlot: [
                            {
                                timeSlot: '06:00 AM',
                                reservations:[
                                    {
                                        reservation_id:3,
                                        status:'RESERVED',
                                        players: [
                                                    {
                                                        playerName: 'Kashif Chishti',
                                                        playerId: '1'
                                                    },
                                                    {
                                                        playerName: 'Bilal Bin Nisar',
                                                        playerId: '2'
                                                    }
                                        ],
                                        guests:0,
                                    }
                                ]
                                    
                                

                            },
                            {
                                timeSlot: '07:00 AM',
                                reservations:[
                                   {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '08:00 AM',
                                reservations:[
                                    {
                                        reservation_id:'',
                                        status:'RESERVED',
                                        players: [
                                                    {
                                                        playerName: 'Kashif Chishti',
                                                        playerId: '1'
                                                    },
                                                    {
                                                        playerName: 'Bilal Bin Nisar',
                                                        playerId: '2'
                                                    }
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '09:00 AM',
                                reservations:[
                                    {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '10:00 AM',
                                reservations:[
                                    {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            }
                        ]

                    },
                    {
                        reserved_at:'',
                        date: '30',
                        day: 'FRIDAY',
                        reservationsByTimeSlot: [
                            {
                                timeSlot: '01:00 PM',
                                reservations:[
                                    {
                                        reservation_id:'',
                                        status:'RESERVED',
                                        players: [
                                                    {
                                                        playerName: 'Kashif Chishti',
                                                        playerId: '1'
                                                    },
                                                    {
                                                        playerName: 'Bilal Bin Nisar',
                                                        playerId: '2'
                                                    }
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '02:00 PM',
                                reservations:[
                                   {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '03:00 PM',
                                reservations:[
                                   {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            },
                            {
                                timeSlot: '04:00 PM',
                                reservations:[
                                   {
                                        reservation_id:'',
                                        status:'',
                                        players: [
                                                   
                                        ],
                                        guests:0,
                                    }
                                ]

                            }
                        ]

                    }


                ]

            }
    var vue = new Vue({
        el: "#reservations-vue-container",
        data: {
            reservationsParent: _reservationsParent,
            showPopup: false,
            reservationToEdit: null,
            reservationType:null, //possible values new or edit
        },
        methods: {
            editReservationEventTriggered: function (reservation) {
                
                reservationTemp = JSON.parse(JSON.stringify(reservation));
                this.reservationToEdit = reservationTemp;
                this.reservationType = "edit";
                this.showPopup = true;
            },
            newReservationEventTriggered:function(reservation){
               
                 reservationTemp = reservation;
                 reservationTemp.clubId = this.reservationsParent.club_id;
                 reservationTemp.courseId = this.reservationsParent.course_id;
                 this.reservationToEdit = reservationTemp;
                 this.reservationType = "new";
                 this.showPopup = true;
            },
            closePopupTriggered: function () {
                
                this.showPopup = false;
                this.reservationToEdit = null;
                this.reservationType = null;
            }
        }
    });
</script>

@endSection
