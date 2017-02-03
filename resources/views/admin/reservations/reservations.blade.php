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
                <li class="nav-item dropdown"> <a class="nav-link clear" href data-toggle="dropdown"> <span class="avatar w-32"> <img src="{{asset('assets/images/a0.jpg')}}" alt="..."> <i class="on b-white bottom"></i> </span> </a>
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
            <div class="search__bar">
                <!--<div ui-include="'../views/blocks/navbar.form.right.html'"></div>-->
                <!-- link and dropdown -->
                <div class="row">
                    <div class="col-md-11">	
                        <ul class="nav navbar-nav">
                            <li class="nav-item dropdown"> 
                                <h3><span>Reservations</span></h3>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- / --> 
            </div>
            <!-- / navbar collapse --> 
        </div>
    </div>
    {{--<div class="app-footer">--}}
        {{--<div class="p-a text-xs">--}}
            {{--<div class="pull-right text-muted"> &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span> <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a> </div>--}}
            {{--<div class="nav"> <a class="nav-link" href="../">About</a> <span class="text-muted">-</span> <a class="nav-link label accent" href="">Get it</a> </div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div ui-view class="app-body" id="view"> 

        <!-- ############ PAGE START-->
        <div class="padding">


        </div>

        <div class="padding"> 
            <div class="dashboard-tsheet">
                <div class="row">
                    <div class="tsheet-header padd-15">
                        <div class="col-md-8">
                            <h2>Tee Sheet</h2>
                        </div><!-- col-6 -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">Go!</button>
                                </span>
                            </div><!-- /input-group -->
                        </div><!-- col-6 -->
                    </div>
                </div><!-- row -->
                <div id="reservations-vue-container" class="row">
                    <div class="col-md-12">
                        <reservation-popup v-if="showPopup" :reservation="reservationToEdit" @close-popup="closePopupTriggered"></reservation-popup>
                        <reservation-tabs :reservations-parent="reservationsParent" style-for-show-more-tab="true">
                            <reservation-tab-heads :reservations-by-date="reservationsParent.reservationsByDate" show-more-tab="true"></reservation-tab-heads>
                            <reservation-tab-divs :reservations-by-date="reservationsParent.reservationsByDate" @edit-reservation="editReservationEventTriggered"></reservation-tab-tables>
                        </reservation-tabs>

                    </div>
                </div>
            </div><!-- dashboard tee sheet -->
        </div><!-- padding -->
    </div>
</div>


@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-divs")
@include("admin.__vue_components.reservations.reservation-popup")

<script>
    var _reservationsParent =
            {
                course_id: '',
                reservationsByDate: [
                    {
                        date: '29',
                        day: 'THURSDAY',
                        reservationsByTimeSlot: [
                            {
                                timeSlot: '06:00 AM',
                                players: [
                                    {
                                        playerName: 'Kashif Chishti',
                                        playerId: '1'
                                    },
                                    {
                                        playerName: 'Bilal Bin Nisar',
                                        playerId: '2'
                                    }
                                ]

                            },
                            {
                                timeSlot: '07:00 AM',
                                players: [
                                    {
                                        playerName: 'Bilal Khalid',
                                        playerId: '3'
                                    },
                                    {
                                        playerName: 'Fahad Mansoor',
                                        playerId: '4'
                                    },
                                    {
                                        playerName: 'Bilal Khalid',
                                        playerId: '3'
                                    },
                                    {
                                        playerName: 'Fahad Mansoor',
                                        playerId: '4'
                                    },
                                    {
                                        playerName: 'Bilal Khalid',
                                        playerId: '3'
                                    },
                                    {
                                        playerName: 'Fahad Mansoor',
                                        playerId: '4'
                                    },
                                    {
                                        playerName: 'Bilal Khalid',
                                        playerId: '3'
                                    },
                                    {
                                        playerName: 'Fahad Mansoor',
                                        playerId: '4'
                                    }
                                ]

                            },
                            {
                                timeSlot: '08:00 AM',
                                players: [
                                ]

                            },
                            {
                                timeSlot: '09:00 AM',
                                players: [
                                ]

                            },
                            {
                                timeSlot: '10:00 AM',
                                players: [
                                ]

                            }
                        ]

                    },
                    {
                        date: '30',
                        day: 'FRIDAY',
                        reservationsByTimeSlot: [
                            {
                                timeSlot: '01:00 PM',
                                players: [
                                    {
                                        playerName: 'Kashif Chishti',
                                        playerId: '1'
                                    },
                                    {
                                        playerName: 'Bilal Bin Nisar',
                                        playerId: '2'
                                    }
                                ]

                            },
                            {
                                timeSlot: '02:00 PM',
                                players: [
                                    {
                                        playerName: 'Bilal Khalid',
                                        playerId: '3'
                                    },
                                    {
                                        playerName: 'Fahad Mansoor',
                                        playerId: '4'
                                    }
                                ]

                            },
                            {
                                timeSlot: '03:00 PM',
                                players: [
                                ]

                            },
                            {
                                timeSlot: '04:00 PM',
                                players: [
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
            reservationToEdit: null
        },
        methods: {
            editReservationEventTriggered: function (reservation) {
                console.log("Edit Button clicked");
                this.showPopup = true;
                this.reservationToEdit = reservation;
            },
            closePopupTriggered: function () {
                console.log("Edit Button clicked");
                this.showPopup = false;
                this.reservationToEdit = null;
            }
        }
    });

</script>

@endSection  