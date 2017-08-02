@extends('admin.__layouts.admin-layout')
@section('heading')
	Create Segments
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<div class="padding">
			<div class="row notificationsCreateSec">
				<div class="col-md-8">
					<form action="#." method="post">
						<div class="form-group">
							<label class="form-control-label">Segment Title</label> <input
								type="text" class="form-control" />
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-4">
									<label class="form-control-label">Age: <input type="text"
										id="amount" readonly
										style="border: 0; color: #000; font-weight: bold;">
									</label>
								</div>
								<div class="col-md-8 inlineFormPadding">
									<div id="sliderRange"></div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row row-sm">
								<div class="col-md-4">
									<label class="form-control-label">Gender</label>
								</div>
								<div class="col-md-2 inlineFormPadding">
									<div class="radio">
										<label class="ui-check"> <input type="radio" name="gender"
											value="Male" class="has-value"> <i class="dark-white"></i>
											Male
										</label>
									</div>
								</div>
								<div class="col-md-2 inlineFormPadding">
									<div class="radio">
										<label class="ui-check"> <input type="radio" name="gender"
											value="Female" class="has-value"> <i class="dark-white"></i>
											Female
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Food</label> <input type="text"
								class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Brand</label> <input
								type="text" class="form-control" />
						</div>
						<br />
						<div class="form-group">
							<button class="btn btn-def">
								<i class="fa fa-floppy-o"></i> &nbsp;Create Segment
							</button>
							&nbsp; &nbsp;
							<a href="{{route('admin.segments.index')}}" class="btn btn-outline b-primary text-primary">
								<i class="fa fa-ban"></i> &nbsp;Cancel
							</a>
						</div>
					</form>
				</div>
				<div class="">
					<div class="col-sm-6 col-md-4">
						<div class="box">
							<div class="box-header">
								<h3>Pie</h3>
								<small>Full fill</small>
							</div>
							<div class="box-body">
								<div ui-jp="plot" ui-refresh="app.setting.color" ui-options="
              [{data: 75, label: &#x27;iPhone&#x27;}, {data: 20, label: &#x27;iPad&#x27;}],
              {
                series: { pie: { show: true, innerRadius: 0, stroke: { width: 0 }, label: { show: true, threshold: 0.05 } } },
                legend: {backgroundColor: 'transparent'},
                colors: ['#0cc2aa','#fcc100'],
                grid: { hoverable: true, clickable: true, borderWidth: 0, color: 'rgba(120,120,120,0.5)' },
                tooltip: true,
                tooltipOpts: { content: '%s: %p.0%' }
              }
            " style="height:200px"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('page-specific-scripts')
<script>
        $( function() {
            $( "#sliderRange" ).slider({
                range: true,
                min: 18,
                max: 85,
                values: [22,50],
                slide: function( event, ui ) {
                    $( "#amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                }
            });
            $( "#amount" ).val($( "#sliderRange" ).slider( "values", 0 ) +
                " - " + $( "#sliderRange" ).slider( "values", 1 ) );
        } );

//        $( "#sliderRange" ).slider( "option", "values", [22,50] );

        $("form").submit(function(e){
            e.preventDefault();
           console.log($("#sliderRange").slider( "option", "values" ));
        });
    </script>
<script src="{{asset("/libs/jquery/flot/jquery.flot.js")}}"></script>
@endSection
