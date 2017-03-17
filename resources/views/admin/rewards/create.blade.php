@extends('admin.__layouts.admin-layout')
@section('heading')
	Create Rewards
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<div class="padding">
			<div class="row notificationsCreateSec">
				<div class="col-md-8">
					<form action="#." method="post">
						<div class="form-group">
							<label class="form-control-label">Offer/Reward Title</label> <input
								type="text" name="offerTitle" class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-group-label">Offer/Reward Description</label>
							<textarea class="form-control" row="6"></textarea>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">Start Date</label> <input
										type="date" class="form-control"
										data-date-inline-picker="false" data-date-open-on-focus="true" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-control-label">End Date</label> <input
										type="date" class="form-control"
										data-date-inline-picker="false" data-date-open-on-focus="true" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Image</label> <input
								type="file" class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Choose Segment</label> <select
								name="segmentName" id="segmentName"
								class="form-control select2-bootstrap-append">
								<option>Choose Segment</option>
								<option>Segment 01</option>
								<option>Segment 02</option>
								<option>Segment 03</option>
							</select>
						</div>
						<br />
						<div class="form-group">
							<button class="btn btn-def">
								<i class="fa fa-floppy-o"></i> &nbsp;Create Offer
							</button>
							&nbsp; &nbsp;
							<button class="btn btn-outline b-primary text-primary">
								<i class="fa fa-ban"></i> &nbsp;Cancel
							</button>
						</div>
					</form>
				</div>
				<div class="col-md-4">
					<img src="{{asset('/assets/images/offer-preview.jpg')}}" class="img-responsive" alt="" />
				</div>
			</div>
		</div>
	</div>

@endsection

@section('page-specific-scripts')
<script>
        $( function() {
            $( "#datePicker" ).datepicker();
        } );
    </script>
@endSection
