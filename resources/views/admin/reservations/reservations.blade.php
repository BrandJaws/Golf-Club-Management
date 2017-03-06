@extends('admin.__layouts.admin-layout')
@section('heading')
	Reservations
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">

		<!-- ############ PAGE START-->


		<div class="padding">
			<div class="dashboard-tsheet">
				<div class="row">
					<div class="tsheet-header padd-15">
						<div class="col-md-4">
							<h2>Tee Sheet</h2>
						</div>
						<!-- col-6 -->
						<div class="col-md-8 text-right">
							<div class="col-md-7">
								<div class="input-group">
									<input type="text" class="form-control"
										   placeholder="Search for..."> <span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
							</div>
							<button class="btn btn-def" id="filterResults"><i class="fa fa-filter"></i> &nbsp;Filter Results</button>
							<button class="btn btn-outline b-primary text-primary"><i class="fa fa-mail-reply"></i> &nbsp;Reset Filters</button>
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
                        <div id="slider-range"></div>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <label for="amount">Number of Empty Slots:</label>
                            <input type="text" id="amountmin" readonly style="border:0; color:#C4061F; font-weight:bold;">
                        </p>
                        <div id="slider-range-min"></div>
                    </div>
                </div>
				<!-- row -->
				<div id="reservations-vue-container" class="row">
					<div class="col-md-12">
						<reservations-container :reservations="reservationsParent"
                                                                            for-reservations-page="true">
                                                        
                                                    </reservations-container>

					</div>
				</div>
			</div>
			<!-- dashboard tee sheet -->
		</div>
		<!-- padding -->
	</div>


@include("admin.__vue_components.reservations.reservations-container")
<script>
var _reservationsParent = {!!$reservations!!};
           
    var vue = new Vue({
        el: "#reservations-vue-container",
        data: {
            reservationsParent: _reservationsParent,
            
        }
        
    });
</script>
<script>
    $("#filterResults").click(function(){
        $("#reservationsFilter").slideToggle("fast");
    });
    $( function() {
        $( "#slider-range" ).slider({
            range: true,
            min: 9,
            max: 24,
            values: [ 9, 12 ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
            " - " + $( "#slider-range" ).slider( "values", 1 ) );
    } );
    $( function() {
        $( "#slider-range-min" ).slider({
            range: "max",
            min: 0,
            max: 4,
            value: 1,
            slide: function( event, ui ) {
                $( "#amountmin" ).val( ui.value );
            }
        });
        $( "#amountmin" ).val( $( "#slider-range-min" ).slider( "value" ) );
    } );
</script>



@endSection
