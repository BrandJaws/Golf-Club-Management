@extends('admin.__layouts.admin-layout')
@section('heading')
	Social
	@endSection
@section('main')
	<div ui-view="" class="app-body" id="view">
		<div class="padding">
			<div class="row notificationsCreateSec">
				<div class="col-md-12 text-center">
					<br />
					<h3>Connect your social profiles</h3>
					<ul class="socialMediaConnect">
						<li class="facebook">
							<a href="#."><i class="fa fa-facebook fa-lg"></i> &nbsp;Facebook</a>
						</li>
						<li class="twitter">
							<a href="#."><i class="fa fa-twitter fa-lg"></i> &nbsp;Twitter</a>
						</li>
						<li class="instagram">
							<a href="#."><i class="fa fa-instagram fa-lg"></i> &nbsp;Instagram</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

@endSection
