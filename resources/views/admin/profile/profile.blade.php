@extends('admin.__layouts.admin-layout')
@section('heading')
	Profile
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding">
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<div class="details-section">
						<div class="image-thumb text-center">
							<img src="../assets/images/profile.jpg"
								class="img-circle img-responsive profileImg" alt="profile">
						</div>
						<!-- image thumb -->
						<div class="detail-content">
							<h3>About Me</h3>
							<p>Lorem Ipsum is simply dummy text of the printing and
								typesetting industry. Lorem Ipsum has been the industry's
								standard dummy text ever since the 1500s, when an unknown
								printer took a galley of type and scrambled it to make a type
								specimen book.Lorem Ipsum is simply dummy text of the printing
								and typesetting industry. Lorem Ipsum has been the industry's
								standard dummy text ever since the 1500s, when an unknown
								printer took a galley of type and scrambled.</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-8">
					<div class="profile-details">
						<div class="edit-btn">
							<a href="{{Request::url()}}/edit"
								class="btn btn-outline b-primary text-primary m-y-xs"><i
								class="fa fa-pencil"></i> &nbsp;Edit</a>
						</div>
						<div class="profile-content">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<div class="heading-inner">
										<h3>Message Settings</h3>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="margin-20-tb">
									<div class="col-md-6">
										<div class="text-right">
											<label>Late Coming</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-left">
											<span>You Are Late</span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="margin-20-tb">
									<div class="col-md-6">
										<div class="text-right">
											<label>Before Time</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-left">
											<span>WOW! You Nailed!</span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="margin-20-tb">
									<div class="col-md-6">
										<div class="text-right">
											<label>On Time</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-left">
											<span>Congratulations</span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="margin-20-tb">
									<div class="col-md-6">
										<div class="text-right">
											<label>Acceptable Time</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-left">
											<span>Well Played</span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="margin-20-tb">
									<div class="col-md-6">
										<div class="text-right">
											<label>No Acceptable Time</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="text-left">
											<span>You Finished Late</span>
										</div>
									</div>
								</div>
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
            $( "#datePicker" ).datepicker();
        } );
    </script>
@endSection
