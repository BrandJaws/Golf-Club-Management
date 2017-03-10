@extends('admin.__layouts.admin-layout')
@section('heading')
	Create Social Posts
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="padding">
			<div class="notificationsCreateSec">
				<div class="row">
					<div class="col-md-4">
						<h3 class="createSocialPost">Social</h3>
					</div>
					<div class="col-md-8">
						<ul class="socialMediaConnected">
							<li class="facebook"><a href="#.">@jacob</a></li>
							<li class="twitter"><a href="#.">@jacobson</a></li>
							<li class="instagram"><a href="#.">@jacob_son</a></li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 col-xs-12">
						<form>
							<div class="form-group">
								<label class="form-control-label">Message</label>
								<textarea name="" id="" cols="30" rows="6" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label class="form-control-label">Image</label> <input
									type="file" class="form-control" />
							</div>
							<div class="row row-sm">
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Facebook
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Twitter
										</label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="ui-check ui-check-md"> <input type="checkbox"
											class="has-value" checked /> <i class="dark-white"></i>
											Instagram
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<button class="btn-def btn">
									<i class="fa fa-paper-plane-o"></i> &nbsp;Post
								</button>
								<button class="btn btn-outline b-primary text-primary">
									<i class="fa fa-ban"></i> &nbsp;Cancel
								</button>
							</div>
						</form>
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
