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
							<a href="{{route('admin.profile.profile')}}" class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a> &nbsp;&nbsp; <a
								href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Save</a>
						</div>
						<div class="profile-content">
							<form action="#." method="post" class="profile-create">
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
