@extends('admin.__layouts.admin-layout')
@section('heading')
    Segments
    @endSection
@section('main')
	<div ui-view="" class="app-body" id="view">

		<!-- Profile Page Start -->

		<div id="segments-vue-container" class="segments-main padding">
			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
							<div class="">
								<div class="col-md-4">
									<div class="inner-page-heading text-left">
										<h3>Segments Listing</h3>
									</div>
								</div>
								<div class="col-md-8">
									<div class="search-form text-right">
										<form action="#." method="post">
											<div class="search-field">
												<span class="search-box"> <input name="search"
													class="search-bar" type="text">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span> <span class=""> <a href="{{Request::url()}}/create"
													name="add-segment" class="btn-def btn"><i
														class="fa fa-plus-circle"></i>&nbsp;Add New Segment</a>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<segments-table :segments-list="segmentsList"></segments-table>
					</div>
				</div>
			</div>
		</div>
		<!-- Segments Page End -->
	</div>

@endsection

@section('page-specific-scripts')
@include("admin.__vue_components.segments.segments-table")
<script>
    var baseUrl = "{{url('')}}";
    _segmentsList = [{name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"},
                    {name:"Segment 1",description:"Sunday Members Only",coverage:"90%",dateTime:"Dec 9 2016 - 2:13:00 AM",status:"Active"}];
   
    var vue = new Vue({
        el: "#segments-vue-container",
        data: {
            segmentsList:[],
            latestPageLoaded:0,
            ajaxRequestInProcess:false,
        },
        methods: {
            loadNextPage:function(){
                //add sample data to array to check scroll functionality
                if(this.latestPageLoaded == 0){
                    for(x=0; x<_segmentsList.length; x++){
                         this.segmentsList.push(_segmentsList[x]);
                    }
                   
                }
                
                
                return;
                
                //End Check: Delete block after real data is available
                if(!this.ajaxRequestInProcess){
                    this.ajaxRequestInProcess = true;
                    var request = $.ajax({

                        url: baseUrl+'/segements?page='+(this.latestPageLoaded+1),
                        method: "GET",
                        success:function(msg){
                                    this.ajaxRequestInProcess = false;
                                    this.latestPageLoaded++;
                                    //Success code to follow

                                }.bind(this),

                        error: function(jqXHR, textStatus ) {
                                    this.ajaxRequestInProcess = false;
                                    
                                    //Error code to follow
                                    
                                    
                               }.bind(this)
                    }); 
                }
                
            }
        }
    });
    
    $(document).ready(function() {
       vue.loadNextPage();
       console.log("bottom!");
       
    });
    $(window).scroll(function() {
       if($(window).scrollTop() + $(window).height() == $(document).height()) {
           vue.loadNextPage();
           console.log("bottom!");
       }
    });
</script>

@endSection
