@extends('admin.__layouts.admin-layout')
@section('heading')
	Reservations
	@endSection
@section('main')
	<div ui-view class="app-body" id="view">

		<!-- ############ PAGE START-->


		<div class="padding">
			<div class="dashboard-tsheet">
				<div class="row">
					<div class="tsheet-header padd-15">
						<div class="col-md-8">
							<h2>Tee Sheet</h2>
						</div>
						<!-- col-6 -->
						<div class="col-md-4">
							<div class="input-group">
								<input type="text" class="form-control"
									placeholder="Search for..."> <span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
								</span>
							</div>
							<!-- /input-group -->
						</div>
						<!-- col-6 -->
					</div>
				</div>
				<!-- row -->
				<div id="reservations-vue-container" class="row">
					<div class="col-md-12">
						<reservations-container :reservations="reservationsParent"
                                                                            for-reservations-page="true">
                                                        
                                                    </reservations-container>

					</div>
				</div>
			</div>
			<!-- dashboard tee sheet -->
		</div>
		<!-- padding -->
	</div>


@include("admin.__vue_components.reservations.reservations-container")
<script>
var _reservationsParent = {!!$reservations!!};
           
    var vue = new Vue({
        el: "#reservations-vue-container",
        data: {
            reservationsParent: _reservationsParent,
            
        }
        
    });
</script>



@endSection
