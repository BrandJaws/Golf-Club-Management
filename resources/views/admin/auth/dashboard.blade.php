@extends('admin.__layouts.admin-layout')
@section('heading')
	Dashboard
	@endSection
@section('main')
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

                            
                                                    <reservations-container :reservations="reservationsParentComputed"
                                                                            for-reservations-page="false"
                                                                            @update-reservations="updateReservations"
                                                                            @drag-drop-operation="dragDropOperationPerformed">
                                                        
                                                    </reservations-container>

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

@include("admin.__vue_components.reservations.reservations-container")
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
                        
                    }
                }
                
                return tempReservations;
            }
        },
        methods:{
            getReservationsForSelectedDate:function(selectedDate){
                                
                                if(moment(selectedDate).format('MMMM Do YYYY, h:mm:ss a')==moment(this.currentSelectedDate).format('MMMM Do YYYY, h:mm:ss a') ){
                                    if(this.filters.showDefaultDates){
                                        if(this.reservationsParent.reservationsByDate.length == 5){
                                            this.filters.showDefaultDates = false;
                                            this.$nextTick(function(){
                                                    $('.nav-tabs a[data-target="#tab5"]').tab('show');  
                                            });
                                            
                                        }
                                    }
                                    
                                    return;  
                                    
                                    
                                }
                               
                                var request = $.ajax({
                                       
                                        url: "{{url('admin/reservations')}}"+"/date/"+encodeURIComponent(moment(selectedDate).format('MMMM Do YYYY, h:mm:ss a')),
                                        method: "GET",
                                        headers: {
                                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                                        },
                                        data:{
                                            _token: "{{ csrf_token() }}",

                                        },
                                        success:function(msg){
                                                
                                                msg = JSON.parse(msg);
                                                this.currentSelectedDate = selectedDate;
                                                if(this.reservationsParent.reservationsByDate.length > 4){
                                                    this.reservationsParent.reservationsByDate.splice(4);
                                                }
                                                this.reservationsParent.reservationsByDate.push(msg.reservationsByDate[0]);
                                                this.filters.showDefaultDates = false;
                                                this.$nextTick(function(){
                                                    $('.nav-tabs a[data-target="#tab5"]').tab('show');  
                                                });
                                                   
                                        }.bind(this),

                                        error: function(jqXHR, textStatus ) {
                                                    this.ajaxRequestInProcess = false;
                                                    
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

                                              
                                         }
                                     }
                                 }
                                 break;
                             }
                         }
                    }
                    
                    
                    
        },
            restoreDefaultDates:function(){
                
                this.filters.showDefaultDates = true;
                
            },
            dragDropOperationPerformed:function (dragDropIndicesDataObject) {
                var reservationPlayerToBeMoved = this.reservationsParent.reservationsByDate[dragDropIndicesDataObject.dateIndexDraggedFrom].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDraggedFrom].reservations[0].players[dragDropIndicesDataObject.playerIndexDragged];
                this.reservationsParent.reservationsByDate[dragDropIndicesDataObject.dateIndexDraggedFrom].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDraggedFrom].reservations[0].players.splice(dragDropIndicesDataObject.playerIndexDragged,1);
                this.reservationsParent.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDroppedInto].reservations[0].players.push(reservationPlayerToBeMoved);
            },
            courseSelectionChanged:function(){
                console.log("course selection changed"+this.coursesSelectedValue);
                var request = $.ajax({

                    url: "{{url('admin/reservations')}}",
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