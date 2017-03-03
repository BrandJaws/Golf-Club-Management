@extends('admin.__layouts.admin-layout')
@section('heading')
    Beacon
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="beacon-list-table" class="segments-main padding">
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
										class="fa fa-cogs"></i>&nbsp;Configure Beacon</a> {{--
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
									--}}
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						@if(Session::has('error'))
                            <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                        @endif
                        @if(Session::has('success'))
                        	<div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                        @endif
						<!-- inner header -->
						<beacon :beacon-list="beaconList"> </beacon>
					</div>
				</div>
			</div>
		</div>
	</div>


@include('admin.__vue_components.beacon.beacon-list');
<script>
        
        var baseUrl = "{{url('admin/beacon')}}";
        var vue = new Vue({
            el: "#beacon-list-table",
            data: {
            	beaconList:({!! $beacon !!}).data,
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
                                        membersList = pageDataReceived.data ;
                                        
                                        //Success code to follow
                                            if(pageDataReceived.next_page_url !== null){
                                                    this.nextAvailablePage = pageDataReceived.current_page+1;
                                            }else{
                                                this.nextAvailablePage = null;
                                            }
                                        
                                            if(isSearchQuery){
                                                
                                                 this.membersList=membersList;
                                            }else{
                                                
                                               appendArray(this.membersList,membersList);
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
