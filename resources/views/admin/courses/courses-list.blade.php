@extends('admin.__layouts.admin-layout')
@section('heading')
    Courses
    @endSection
@section('main')
    <div ui-view  class="app-body" id="view">
        <!-- ############ PAGE START-->
        
        <div class="profile-main padding" id="selectionDepHidden">
            <div class="row inner-header">
                <div class="col-md-6">
                    <div class="inner-page-heading text-left"><h3>Courses Listing</h3></div>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{route('admin.courses.create')}}" class="btn-def btn"><i class="fa fa-plus-circle"></i> &nbsp;Add new courses</a>
                </div>
            </div>
            <div class="row bg-white">
                <div class="col-md-12 padding-none">
                    <courses :courses="courses"></courses>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-specific-scripts')


    @include("admin.__vue_components.autocomplete.autocomplete")
    @include("admin.__vue_components.courses.courses-table");
    <script>
        
        var baseUrl = "{{url('admin/courses')}}";
        var vue = new Vue({
            el: "#selectionDepHidden",
            data: {
            	courses:({!! $courses !!}).data,
                searchQuery:"",
                lastSearchTerm:"",
                nextAvailablePage:{!! (json_encode($courses))!!}.next_page_url !== null ? 2 : null ,
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
