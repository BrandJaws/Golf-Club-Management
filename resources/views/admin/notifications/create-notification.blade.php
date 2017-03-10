@extends('admin.__layouts.admin-layout')
@section('heading')
	Create Notifications
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		{{--
		<div id="notifications-list-table" class="segments-main padding">
			--}} {{--
			<div class="row">
				--}} {{--
				<div class="segments-inner">
					--}} {{--
					<div class="box">
						--}} {{--
						<div class="inner-header">
							--}} {{--
							<div class="">
								--}} {{--
								<div class="col-md-8">
									--}} {{--
									<h3>
										<span>Create Notification</span>
									</h3>
									--}} {{--
								</div>
								--}} {{--
								<div class="col-md-4 text-right">
									--}} {{--<a class="btn-def btn"
										href="{{Request::url()}}/create"><i class="fa fa-plus-circle"></i>&nbsp;Create
										Notification</a>--}} {{--
								</div>
								--}} {{--
								<div class="clearfix"></div>
								--}} {{--
							</div>
							--}} {{--
						</div>
						<!-- inner header -->
						--}} {{----}} {{--
					</div>
					--}} {{--
				</div>
				--}} {{--
			</div>
			--}} {{--
		</div>
		--}}
		<div class="padding">
			<div class="row notificationsCreateSec">
				<div class="col-md-8">
					<div class="form-group">
						<lable class="form-control-label">Title</lable>
						<input type="text" class="form-control" />
					</div>
					<div class="form-group">
						<label class="form-control-label">Description/Message</label>
						<textarea class="form-control" rows="8"></textarea>
					</div>
					<div class="form-group">
					    <div class="row">
							<div class="col-md-4">
								<label>Notification Type</label>
							</div>
							<div class="col-md-4">
								<div class="radio">
									<label class="ui-check">
										<input type="radio" checked name="notificationType" value="general" class="has-value">
										<i class="dark-white"></i>
										General
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="radio">
									<label class="ui-check">
										<input type="radio" name="notificationType" value="bookingOnly" class="has-value">
										<i class="dark-white"></i>
										Booking Only
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button class="btn-def btn">
							<i class="fa fa-paper-plane-o"></i> &nbsp;Send Now
						</button>
						<a href="{{route("admin.notifications.notifications")}}" class="btn btn-outline b-primary text-primary">Cancel</a>
					</div>
				</div>
				<div class="col-md-4">
					{{--
					<div id="datePicker">--}} {{--</div>
					--}} <input type="date" class="hide-replaced" />
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
