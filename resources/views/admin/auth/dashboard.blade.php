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
                                                                            for-reservations-page="false">
                                                        
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
			},
            change: function( event, ui ) {}
		});
		$( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
			" - " + $( "#slider-range" ).slider( "values", 1 ) );
	} );
    $( "#slider-range" ).on( "slidechange", function( event, ui ) { console.log("working!") } );
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
