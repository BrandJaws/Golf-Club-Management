@extends('admin.__layouts.admin-layout')
@section('heading')
	Add Coach
	@endSection
@section('main')
	<div class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div class="profile-main padding" id="selectionDepHidden">
			<div class="row details-section">
				<form action="#." name="" action="">
					<div class="col-md-8">
						<div class="form-group">
							<label class="form-control-label">Name</label> <input type="text"
								class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Email</label> <input
								type="email" class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Contact Number</label> <input
								type="tel" class="form-control" />
						</div>
						<div class="form-group">
							<label class="form-control-label">Specialities</label> <input
								type="text" class="form-control" /> <span
								class="help-block m-b-none">Each separated with a comma.</span>
						</div>
						<br />
						<div class="form-group">
							<a href="#." class="btn btn-def"><i class="fa fa-floppy-o"></i>
								&nbsp;Add Member</a> &nbsp;&nbsp; <a
								href="{{route('admin.coaches.coaches')}}"
								class="btn btn-outline b-primary text-primary"><i
								class="fa fa-ban"></i> &nbsp;Cancel</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="text-center">
							<img src="../../assets/images/user.png"
								class="img-responsive img-circle defaultImg" />
							<div class="form-group">
								<label class="form-control-label">Add Image</label> <input
									type="file" class="form-control" />
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endSection
