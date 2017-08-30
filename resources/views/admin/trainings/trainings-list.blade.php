@extends('admin.__layouts.admin-layout')
@section('heading')
    Trainings
    @endSection
@section('main')
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div id="trainings-list-table" class="segments-main padding">
                <div class="row">
                    <div class="segments-inner">
                        <div class="box">
                            <div class="inner-header">
                                <div class="">
                                    <div class="col-md-8">
                                        <h3>
                                            <span>Trainings List</span>
                                        </h3>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a class="btn-def btn" href="{{route("admin.trainings.create")}}"><i
                                                    class="fa fa-plus-circle"></i>&nbsp;Add New Training</a>
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
                            <trainings :trainings-list="trainingsList"> </trainings>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.trainings.trainings-table")
    <script>
var baseUrl = "{{url('admin/trainings')}}";
var vue = new Vue({
    el: "#trainings-list-table",
    data: {
    	trainingsList:({!! $trainings !!}).data,
        searchQuery:"",
        lastSearchTerm:"",
        nextAvailablePage:{!! (json_encode($trainings))!!}.next_page_url !== null ? 2 : null ,
        searchRequestHeld:false,
        ajaxRequestInProcess:false,
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
                                productsList = pageDataReceived.data ;
                                
                                //Success code to follow
                                    if(pageDataReceived.next_page_url !== null){
                                            this.nextAvailablePage = pageDataReceived.current_page+1;
                                    }else{
                                        this.nextAvailablePage = null;
                                    }
                                
                                    if(isSearchQuery){
                                        
                                         this.trainingsList=productsList;
                                    }else{
                                        
                                       appendArray(this.trainingsList,productsList);
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
