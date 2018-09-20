@extends('admin.__layouts.admin-layout')
@section('heading') News / Events 
@endSection 
@section('main')
<div ui-view class="app-body" id="view">
	<!-- ############ PAGE START-->
	<div id="news-list-table" class="segments-main padding">
		<div class="row">
			<div class="segments-inner">
				<div class="box">
					<div class="inner-header">
						<div class="">
							<div class="col-md-8">
								<div class="search-form">
									<form action="#." method="post" v-on:click.prevent>
										<div class="search-field">
											<span class="search-box"> <input type="text" name="search"
												class="search-bar" v-model="searchQuery"
												@input="loadNextPage(true)">
												<button type="submit" class="search-btn">
													<i class="fa fa-search"></i>
												</button>
											</span>
										</div>
									</form>
								</div>
							</div>
							<div class="col-md-4 text-right">
								<a href="{{route('admin.newsfeeds.create')}}" class="btn-def btn"><i
									class="fa fa-plus-circle"></i>&nbsp;Add News</a>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					@if(Session::has('error'))
					<div class="alert alert-warning" role="alert">
						{{Session::get('error')}}</div>
					@endif @if(Session::has('success'))
					<div class="alert alert-success" role="alert">
						{{Session::get('success')}}</div>
					@endif
					<!-- inner header -->
					<news-table-cotainer> 
						<news-table :news-list="newsList"></news-table>
					</news-table-cotainer>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection @section('page-specific-scripts')
@include("admin.__vue_components.news.news-table")
<script> 
	var baseUrl = "{{url('admin/newsfeeds')}}";
	var vue = new Vue({
		el: "#news-list-table",
		data: {
			newsList:({!! $newsFeed !!}).data,
			ajaxRequestInProcess:false,
			searchQuery:"",
			lastSearchTerm:"",
			nextAvailablePage:{!! (json_encode($newsFeed))!!}.next_page_url !== null ? 2 : null ,
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
