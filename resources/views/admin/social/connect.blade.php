@extends('admin.__layouts.admin-layout')
@section('heading')
	Social
	@endSection
@section('main')
	<div ui-view="" class="app-body" id="view">
		<div class="padding">
			<div class="row notificationsCreateSec">
				<div class="col-md-2"></div>
				<div class="col-md-8 text-center">
					<br />
					<h3>Connect your social profiles</h3>
					<ul class="socialMediaConnect">
						<li class="facebook"><a href="#.">Facebook</a></li>
						<li class="twitter"><a href="#.">Twitter</a></li>
						<li class="instagram"><a href="#.">Instagram</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

@endSection
