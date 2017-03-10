@extends('admin.__layouts.admin-layout')
@section('heading')
    Coaches
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="coaches-list-table" class="segments-main padding">
			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
							<div class="">
								<div class="col-md-8">
									<div class="search-form">
										<form action="#." method="post">
											<div class="search-field">
												<span class="search-box"> <input type="text" name="search"
													class="search-bar">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{Request::url()}}/create" class="btn-def btn"><i
										class="fa fa-plus-circle"></i>&nbsp;Add Coaches</a>
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<coaches :coaches-list="coachesList"> </coaches>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-specific-scripts')
@include("admin.__vue_components.coaches.coaches-table")
<script>
       
        
	var baseUrl = "{{url('admin/coaches')}}";
	var vue = new Vue({
		el: "#coaches-list-table",
		data: {
			coachesList:({!! $coaches !!}).data,
			ajaxRequestInProcess:false,
                        searchQuery:"",
                        lastSearchTerm:"",
                        nextAvailablePage:2,
                        searchRequestHeld:false,
                        
                        
                        
		},
		methods: {
                    
			loadNextPage:function(isSearchQuery){
                               
				
                                if(isSearchQuery){
                                    if(this.ajaxRequestInProcess){
                                        this.searchRequestHeld=true;
                                        return;
                                    }
                                    if(this.searchQuery !== this.lastSearchTerm){
                                        this.nextAvailablePage = 1;
                                    }
                                    this.lastSearchTerm = this.searchQuery;
                                    _url = baseUrl+'?search='+this.searchQuery+'&current_page='+(this.nextAvailablePage);
                                   
                                    
                                }else if(this.searchQuery != ""){
                                    _url = baseUrl+'?search='+this.searchQuery+'&current_page='+(this.nextAvailablePage);
                                }else{
                                    _url = baseUrl+'?current_page='+(this.nextAvailablePage);
                                }
                                
                              
                                if(this.nextAvailablePage === null){
                                    return;
                                }
                                
                                if(!this.ajaxRequestInProcess){
                                    this.ajaxRequestInProcess = true;
                                    var request = $.ajax({
                                        
                                        url: _url,
                                        method: "GET",
                                        success:function(msg){
                                            
                                                    this.ajaxRequestInProcess = false;
                                                    if(this.searchRequestHeld){
                                                       
                                                        this.searchRequestHeld=false;
                                                        this.loadNextPage(true);
                                                        
                                                    }
                                                    
                                                    pageDataReceived = msg;
                                                    coachesList = pageDataReceived.data ;
                                                    
                                                    //Success code to follow
                                                        if(pageDataReceived.next_page_url !== null){
                                                                this.nextAvailablePage = pageDataReceived.current_page+1;
                                                        }else{
                                                            this.nextAvailablePage = null;
                                                        }
                                                    
                                                        if(isSearchQuery){
                                                            
                                                             this.coachesList=coachesList;
                                                        }else{
                                                            
                                                           appendArray(this.coachesList,coachesList);
                                                        }
                                                    
                                                    
                                                    
                                                }.bind(this),

                                        error: function(jqXHR, textStatus ) {
                                                    this.ajaxRequestInProcess = false;

                                                    //Error code to follow


                                               }.bind(this)
                                    }); 
                                }
			}
		},
	});

	/* $(document).ready(function() {
       vue.loadNextPage();
    }); */
    $(window).scroll(function() {
       if($(window).scrollTop() + $(window).height() == $(document).height()) {
       
            vue.loadNextPage(false);
           
       }
    });
    
 
   
</script>

@endSection
