@extends('admin.__layouts.admin-layout')
@section('heading')
	Edit Profile
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding">
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="details-section">
						<div class="image-thumb text-center">
							<img src="../../assets/images/profile.jpg"
								class="img-circle img-responsive profileImg" alt="profile">
						</div>
						<!-- image thumb -->
						<div class="detail-content text-center">
							<a href="#."
								class="btn btn-outline b-primary text-primary m-y-xs">Change/Edit</a>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-8">
					<div class="profile-details">
						<div class="edit-btn">
							<a href="#." class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a> &nbsp;&nbsp; <a
								href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Save</a>
						</div>
						<div class="profile-content">
							<form action="#." method="post" class="profile-create">
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>Booking Differences</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<select class="form-control">
													<option value="10min">10 Min</option>
													<option value="20min">20 Min</option>
													<option value="30min">30 Min</option>
													<option value="40min">40 Min</option>
													<option value="50min">50 Min</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>Courses</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<select class="form-control">
													<option value="9">9 Holes</option>
													<option value="18">18 Holes</option>
													<option value="20">20 Holes</option>
													<option value="30">30 Holes</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-12">
											<div class="form-inline">
												<div class="col-md-4">
													<label>Opening Hours </label> <select class="form-control">
														<option value="4am">4 am</option>
														<option value="5am">5 am</option>
														<option value="6am">6 am</option>
														<option value="7am">7 am</option>
														<option value="8am">8 am</option>
														<option value="9am">9 am</option>
														<option value="10am">10 am</option>
														<option value="11am">11 am</option>
														<option value="12am">12 am</option>
													</select>
												</div>
												<div class="col-md-4">
													<label>To</label> <select class="form-control">
														<option value="4am">4 am</option>
														<option value="5am">5 am</option>
														<option value="6am">6 am</option>
														<option value="7am">7 am</option>
														<option value="8am">8 am</option>
														<option value="9am">9 am</option>
														<option value="10am">10 am</option>
														<option value="11am">11 am</option>
														<option value="12am">12 am</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="heading-inner">
											<h3>Message Settings</h3>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>Late Coming</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<input type="text" name="late-coming" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>Before Time</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<input type="text" name="before-time" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>On Time</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<input type="text" name="on-time" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>Acceptable Time</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<input type="text" name="accept-time" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>No Acceptable Time</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<input type="text" name="no-accept-time"
													class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="margin-20-tb">
										<div class="col-md-4">
											<div class="text-left">
												<label>No Acceptable Time</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="">
												<textarea name="no-accept-time" class="form-control"
													placeholder="Your Message"></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="heading-inner">
											<h3>About Me</h3>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="heading-inner">
											<textarea class="form-control" rows="4"></textarea>
										</div>
									</div>
								</div>
							</form>
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
            $( "#datePicker" ).datepicker();
        } );
    </script>
@endSection
