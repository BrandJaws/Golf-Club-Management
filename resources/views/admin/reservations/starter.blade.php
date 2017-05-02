@extends('admin.__layouts.admin-layout')
@section('heading')
    Checkins
    @endSection
@section('main')
    <div ui-view class="app-body" id="view">

        <!-- ############ PAGE START-->
        <div class="padding">
            <div class="">
                <div class="dashboard-tsheet" id="reservations-vue-container">
                    <div class="row">
                        <div class="tsheet-header padd-15">
                            <div class="col-md-4">
                                <h2>Tee Sheet</h2>
                            </div>
                            <!-- col-6 -->
                            <div class="col-md-8 text-right">
                                <div class="form-group col-md-7">
                                    <select class="form-control" v-model="coursesSelectedValue" @change="courseSelectionChanged">
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}" >{{$course->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-def" id="filterResults"><i class="fa fa-filter"></i> &nbsp;Filter Results</button>
                                <button id = "reset-filters" class="btn btn-outline b-primary text-primary"><i class="fa fa-mail-reply"></i> &nbsp;Reset Filters</button>
                                <!-- /input-group -->
                            </div>
                            <!-- col-6 -->
                        </div>
                    </div>
                    <div class="row padd-15" id="reservationsFilter" style="display: none;">
                        <div class="col-md-6">
                            <p>
                                <label for="amount">From:</label>
                                <input type="text" id="amount" readonly style="border:0; color:#C4061F; font-weight:bold;">
                            </p>
                            <div id="slider-time-range"></div>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <label for="amount">Minimum number of Empty Slots:</label>
                                <input type="text" id="amountmin" readonly style="border:0; color:#C4061F; font-weight:bold;">
                            </p>
                            <div id="slider-empty-slots"></div>
                        </div>
                    </div>
                    <!-- row -->
                    <div  class="row">
                        <div class="col-md-12">


                            <reservations-checkins-container :reservations="reservationsParentComputed"
                                                    @update-reservations="updateReservations">

                            </reservations-checkins-container>

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




    @endSection


@section('page-specific-scripts')

    @include("admin.__vue_components.reservations.starter.reservations-checkins-container")
    <script>
        var _reservationsParent = {!!$reservations!!};


        $courseOpenTime = moment(_reservationsParent.courseOpenTime,"HH:mm:ss").hour();
        $courseCloseTime = moment(_reservationsParent.courseCloseTime,"HH:mm:ss").hour();
        var vue = new Vue({
            el: "#reservations-vue-container",
            data: {
                reservationsParent: _reservationsParent,
                coursesSelectedValue: _reservationsParent.course_id,
                currentSelectedDate:null,
                filters:{
                    timeStart:$courseOpenTime,
                    timeEnd:$courseCloseTime,
                    minEmptySlots:5,
                    showDefaultDates:true,
                },




            },
            computed:{
                reservationsParentComputed:function(){

                    var tempReservations = JSON.parse(JSON.stringify(this.reservationsParent));

                    for(dateCount=0; dateCount<tempReservations.reservationsByDate.length; dateCount++){

                        if(this.filters.showDefaultDates){
                            if(dateCount < 4  ){
                                tempReservations.reservationsByDate[dateCount].dateIsVisible = true;

                            }else{
                                tempReservations.reservationsByDate[dateCount].dateIsVisible = false;
                            }

                        }else{
                            if(dateCount < 4 ){
                                tempReservations.reservationsByDate[dateCount].dateIsVisible = false;
                            }else{
                                tempReservations.reservationsByDate[dateCount].dateIsVisible = true;
                            }
                        }

                        for(timeSlotCount=0; timeSlotCount<tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot.length; timeSlotCount++ ){
                            $timeSlotAsHourNumber = moment(tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount].timeSlot,"h:mm A");

                            if( $timeSlotAsHourNumber >= moment(this.filters.timeStart,'HH') &&
                                $timeSlotAsHourNumber <= moment(this.filters.timeEnd,'HH') &&
                                (4-(tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount].reservations[0].players.length) >= this.filters.minEmptySlots || this.filters.minEmptySlots == 5)){

                                tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount].isVisibleUnderFilter = true;

                            }else{
                                tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount].isVisibleUnderFilter = false;
                            }

                            tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount].gameStarted = false;
                            //console.log(tempReservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotCount]);
                        }
                    }

                    return tempReservations;
                }
            },
            methods:{

                courseSelectionChanged:function(){

                    var request = $.ajax({

                        url: "{{url('admin/reservations/starter')}}",
                        method: "GET",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{
                            _token: "{{ csrf_token() }}",
                            course_id:this.coursesSelectedValue,

                        },
                        success:function(msg){

                            msg = JSON.parse(msg);
                            this.reservationsParent = msg;
                            this.filters.showDefaultDates = true;


                        }.bind(this),

                        error: function(jqXHR, textStatus ) {


                            //Error code to follow
                            console.log(jqXHR);

                        }.bind(this)
                    });
                },
                updateReservations:function(newOrUpdatedReservation){

                    if(newOrUpdatedReservation[0].course_id == this.reservationsParent.course_id){

                        for(dateCount=0;dateCount<this.reservationsParent.reservationsByDate.length;dateCount++){
                            if(this.reservationsParent.reservationsByDate[dateCount].reserved_at == newOrUpdatedReservation[0].reserved_at){

                                for(timeSlotOriginalReservationsCount=0;timeSlotOriginalReservationsCount<this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot.length;timeSlotOriginalReservationsCount++ ){

                                    for(timeSlotsReceivedCount=0;timeSlotsReceivedCount<newOrUpdatedReservation[0].reservationsByTimeSlot.length;timeSlotsReceivedCount++){

                                        if(newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].timeSlot == this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].timeSlot
                                                &&
                                                (this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type == "App\\Http\\Models\\RoutineReservation" ||
                                                        this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type == ""
                                                )
                                        ){


                                            this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_id = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].reservation_id;
                                            this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].reservation_type;
                                            this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].players = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].players;
                                            this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].status = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].status;
                                            this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].game_status = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].game_status;
                                            console.log(this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].players);

                                        }
                                    }
                                }
                                break;
                            }
                        }
                    }



                },

            }

        });

        vue.$nextTick(function(){

            $( "#date-reserv" ).datepicker()
                .on('changeDate', function(e) {
                    vue.getReservationsForSelectedDate(e.date);

                });


        });

        $("#filterResults").click(function(){
            $("#reservationsFilter").slideToggle("fast");
        });
        $( function() {
            $( "#slider-time-range" ).slider({
                range: true,
                min: $courseOpenTime,
                max: $courseCloseTime,
                values: [ $courseOpenTime, $courseCloseTime],
                slide: function( event, ui ) {
                    var value1 = ui.values[ 0 ] <= 12 ? ui.values[ 0 ]+ ":00 "+ (ui.values[ 0 ] < 12 ? "AM" : "PM") : ui.values[ 0 ]%12 + ":00 PM";
                    var value2 = ui.values[ 1 ] <= 12 ? ui.values[ 1 ]+ ":00 "+ (ui.values[ 1 ] < 12 ? "AM" : "PM") : ui.values[ 1 ]%12 + ":00 PM";
                    $( "#amount" ).val( "" + value1+ " - " + value2 );

                    vue.filters.timeStart = ui.values[ 0 ];
                    vue.filters.timeEnd = ui.values[ 1 ];

                }
            });
            var value1 = $( "#slider-time-range" ).slider( "values", 0 ) <= 12 ? $( "#slider-time-range" ).slider( "values", 0 )+ ":00 "+ ($( "#slider-time-range" ).slider( "values", 0 ) < 12 ? "AM" : "PM") : $( "#slider-time-range" ).slider( "values", 0 )%12 + ":00 PM";
            var value2 = $( "#slider-time-range" ).slider( "values", 1 ) <= 12 ? $( "#slider-time-range" ).slider( "values", 1 )+ ":00 "+ ($( "#slider-time-range" ).slider( "values", 1 ) < 12 ? "AM" : "PM")  : $( "#slider-time-range" ).slider( "values", 1 )%12 + ":00 PM";
            $( "#amount" ).val( "" + value1+ " - " + value2 );
            $( "#slider-empty-slots" ).slider({
                range: "max",
                min: 0,
                max: 5,
                value: 5,
                slide: function( event, ui ) {
                    if(ui.value == 5){
                        $( "#amountmin" ).val("All");
                    }else{
                        $( "#amountmin" ).val( ui.value );
                    }

                    vue.filters.minEmptySlots = ui.value;
                }
            });
            if($( "#slider-empty-slots" ).slider( "value" ) == 5){
                $( "#amountmin" ).val( "All" );
            }else{
                $( "#amountmin" ).val( $( "#slider-empty-slots" ).slider( "value" ) );
            }


            //Reset Sliders
            $("#reset-filters").on("click",function(){

                //reset time range slider
                $( "#slider-time-range" ).slider( "values",0, $courseOpenTime);
                $( "#slider-time-range" ).slider( "values",1, $courseCloseTime);
                $( "#amount" ).val( "" + $( "#slider-time-range" ).slider( "values", 0 ) +":00"+
                    " - " + $( "#slider-time-range" ).slider( "values", 1 )+":00" );

                vue.filters.timeStart = $( "#slider-time-range" ).slider( "values",0 );
                vue.filters.timeEnd = $( "#slider-time-range" ).slider( "values",1 );

                //reset empty slots slider
                $( "#slider-empty-slots" ).slider( "value",5 );

                if($( "#slider-empty-slots" ).slider( "value" ) == 5){
                    $( "#amountmin" ).val( "All" );
                }else{
                    $( "#amountmin" ).val( $( "#slider-empty-slots" ).slider( "value" ) );
                }
                vue.filters.minEmptySlots = $( "#slider-empty-slots" ).slider( "value" );

            });


        } );

    </script>
    @endSection