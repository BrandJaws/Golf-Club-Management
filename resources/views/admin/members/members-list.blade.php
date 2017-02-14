@extends('admin.__layouts.admin-layout') @section('main')
<div id="content" class="app-content box-shadow-z0" role="main">
	<div class="app-header white box-shadow">
		<div class="navbar">
			<!-- Open side - Naviation on mobile -->
			<a data-toggle="modal" data-target="#aside"
				class="navbar-item pull-left hidden-lg-up"> <i
				class="material-icons">&#xe5d2;</i>
			</a>
			<!-- / -->
			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h5"
				ng-bind="$state.current.data.title" id="pageTitle"></div>
			<!-- navbar right -->
			<ul class="nav navbar-nav pull-right">
				<li class="nav-item dropdown pos-stc-xs"><a class="nav-link" href
					data-toggle="dropdown"> <i class="material-icons">&#xe7f5;</i> <span
						class="label label-sm up warn">3</span>
				</a>
					<div ui-include="'../views/blocks/dropdown.notification.html'"></div>
				</li>
				<li class="nav-item dropdown"><a class="nav-link clear" href
					data-toggle="dropdown"> <span class="avatar w-32"> <img
							src="../assets/images/a0.jpg" alt="..."> <i
							class="on b-white bottom"></i>
					</span>
				</a>
					<div class="dropdown-menu pull-right dropdown-menu-scale ng-scope">
						<a class="dropdown-item" ui-sref="app.inbox.list"
							href="{{route('admin.profile.profile')}}"> <span>Profile</span>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" ui-sref="access.signin"
							href="#/access/signin">Sign out</a>
					</div></li>
				<li class="nav-item hidden-md-up"><a class="nav-link"
					data-toggle="collapse" data-target="#collapse"> <i
						class="material-icons">&#xe5d4;</i>
				</a></li>
			</ul>
			<!-- / navbar right -->

			<!-- navbar collapse -->
			<div class="collapse navbar-toggleable-sm" id="collapse">
				<div class="main-page-heading">
					<h3>
						<span>Members</span>
					</h3>
				</div>
			</div>
			<!-- / navbar collapse -->
		</div>
	</div>
	{{--
	<div class="app-footer">
		--}} {{--
		<div class="p-a text-xs">
			--}} {{--
			<div class="pull-right text-muted">
				&copy; Copyright <strong>Grit Golf</strong> <span
					class="hidden-xs-down">- Built with Love v1.1.3</span> <a
					ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
			</div>
			--}} {{--
			<div class="nav">
				<a class="nav-link" href="../">About</a> <span class="text-muted">-</span>
				<a class="nav-link label accent"
					href="http://themeforest.net/user/flatfull/portfolio?ref=flatfull">Get
					it</a>
			</div>
			--}} {{--
		</div>
		--}} {{--
	</div>
	--}}
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="members-list-table" class="segments-main padding">
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
													class="search-bar" v-model="searchQuery" @input="loadNextPage(true)">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<a href="{{Request::url()}}/add" class="btn-def btn"><i
										class="fa fa-plus-circle"></i>&nbsp;Add Members</a>
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- inner header -->
						<members-table-cotainer> <members-table
							:members-list="membersList"></members-table> </members-table-cotainer>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include("admin.__vue_components.members.members-table")
<script>
    
	var baseUrl = "{{url('admin/members')}}";
	var vue = new Vue({
		el: "#members-list-table",
		data: {
			membersList:{!! $members !!},
			ajaxRequestInProcess:false,
                        searchQuery:"",
                        lastSearchTerm:"",
                        nextAvailablePage:2,
                        
                        
                        
		},
		methods: {
                    
			loadNextPage:function(isSearchQuery){
                               
				
                                if(isSearchQuery){
                                    
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
                                                    pageDataReceived = JSON.parse(msg);
                                                    membersList = pageDataReceived.data;
                                                    
                                                    //Success code to follow
                                                        if(pageDataReceived.next_page_url !== null){
                                                                this.nextAvailablePage = pageDataReceived.current_page+1;
                                                        }else{
                                                            this.nextAvailablePage = null;
                                                        }
                                                    
                                                        if(isSearchQuery){
                                                            
                                                            for(x=this.membersList.data.length;x>=0;x--){
                                                                this.membersList.data.splice(x,1);
                                                            }

                                                            for(x=0; x<membersList.length; x++){

                                                                this.membersList.data.push(membersList[x]);

                                                            }
                                                            
                                                        
                                                        }else{
                                                            
                                                           
                                                            for(x=0; x<membersList.length; x++){

                                                                this.membersList.data.push(membersList[x]);

                                                            }
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
