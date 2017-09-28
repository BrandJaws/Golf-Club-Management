@extends('admin.__layouts.admin-layout')
@section('heading')
    Orders Archive
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="orders-vue-container" class="segments-main padding">

			<div class="row">
				<div class="segments-inner">
					<div class="box">
						<div class="inner-header">
                            <div class="filters">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Member</label>
                                                <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="member_id" property-for-name="member_name"
                                                                   filtered-from-source="true"
                                                                   include-id-in-list="true"
                                                                   initial-text-value="" search-query-key="search" field-name="memberId" enable-explicit-selection="false" v-model="filtersForBinding.memberId"> </auto-complete-box>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-control-label">Date</label>
                                                <input id="date" type="text" name="" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-group ">
                                                    <label class="form-control-label">From</label>
                                                    <input id="timeFrom" type="time" class="form-control" placeholder="AM" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-group ">
                                                    <label class="form-control-label">To</label>
                                                    <input id="timeTo" type="time" class="form-control" placeholder="AM" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <div class="form-group">
                                                <br><br>
                                                <input type="submit" name="" value="Search" class="btn-def btn btn-full-width" @click.prevent="loadNextPage(true)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						@if(Session::has('error'))
                            <div class="alert alert-warning" role="alert"> {{Session::get('error')}} </div>
                        @endif
                        @if(Session::has('success'))
                        	<div class="alert alert-success" role="alert"> {{Session::get('success')}} </div>
                        @endif
						<!-- inner header -->
						<orders-archive :orders-list="orderList" :base-url="baseUrl"></orders-archive>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('page-specific-scripts')
@include("admin.__vue_components.restaurant.orders-archive")
@include("admin.__vue_components.autocomplete.autocomplete")
<script>


    var _baseUrl = "{{url('')}}";
    var vue = new Vue({
        el: "#orders-vue-container",
        data: {
            //Null filters as at the time of initialization to send null value with the request if the filtersForBinding Equals these
            //This will save us where query clauses at the server
            nullFilters:{ memberId:-1,  date:"", timeFrom:"",timeTo:"", },
            filtersReceived : ({!! $ordersWithFilters !!}).filters != null ? ({!! $ordersWithFilters !!}).filters : { memberId:-1,  date:"", timeFrom:"",timeTo:"", },
            filtersForBinding:{
                memberId:-1,
                date:"",
                timeFrom:"",
                timeTo:"",

            },
            orderList: ({!! $ordersWithFilters !!}).data,
            ajaxRequestInProcess:false,
            nextAvailablePage:({!! $ordersWithFilters!!}).next_page_url !== null ? 2 : null ,
            searchRequestHeld:false,
            baseUrl:_baseUrl,




        },
        mounted:function(){
            $( "#date" ).datepicker()
                    .on('changeDate', function(e) {

                        vue.filtersForBinding.date = $( "#date" ).val();



                    });
            $( "#date" ).datepicker()
                    .on('input', function(e) {

                        vue.filtersForBinding.date = $( "#date" ).val();



                    });

            $( "#timeFrom" ).on('input',function(){
                vue.filtersForBinding.timeFrom =$( "#timeFrom" ).val();
            })

            $( "#timeTo" ).on('input',function(){
                vue.filtersForBinding.timeTo =$( "#timeTo" ).val();
            })

        },
        methods: {


            loadNextPage:function(isSearchQuery){

                var _data ={};

                if(isSearchQuery){

                    if(JSON.stringify(this.filtersForBinding) ==  JSON.stringify(this.filtersReceived)){

                        return;
                    }

                    if(this.ajaxRequestInProcess){
                        this.searchRequestHeld=true;
                        return;
                    }

                    //If is search query we need to set filters equal to filters for binding that have been selected by the user
                    //Also we need to reset nextAvailablePage so that the method doesn't return void since  the nextAvailablePage
                    //might have been set to null due to previous scrolling or search results

                    _data.filters = JSON.stringify(this.filtersForBinding) == JSON.stringify(this.nullFilters) ? null : JSON.stringify(this.filtersForBinding);
                    this.nextAvailablePage = 1;
                    _data.current_page = this.nextAvailablePage;


                }else{
                    //If is scroll query we need to set filters equal to filters received last time
                    //might have been set to null due to previous scrolling or search results

                    _data.filters =  JSON.stringify(this.filtersReceived) == JSON.stringify(this.nullFilters) ? null : JSON.stringify(this.filtersReceived);
                    _data.current_page = this.nextAvailablePage;

                }

                //Return void if there is no available next page. Placed here so that in case of search query when we need to refresh the counter
                //we can set it to a non null value i-e 1 before we reach this check.
                if(this.nextAvailablePage === null){
                    return;
                }





                if(!this.ajaxRequestInProcess){
                    this.ajaxRequestInProcess = true;
                    var request = $.ajax({

                        url: this.baseUrl+'/admin/restaurant/orders-archive',
                        method: "GET",
                        data:_data,
                        success:function(msg){

                            this.ajaxRequestInProcess = false;
                            if(this.searchRequestHeld){

                                this.searchRequestHeld=false;
                                this.loadNextPage(true);

                            }

                            pageDataReceived = JSON.parse(msg);
                            orderList = pageDataReceived.data ;
                            this.filtersReceived = pageDataReceived.filters != null ? pageDataReceived.filters : { memberId:-1,  date:"", timeFrom:"",timeTo:"", };

                            //Success code to follow
                            if(pageDataReceived.next_page_url !== null){
                                this.nextAvailablePage = pageDataReceived.current_page+1;
                            }else{
                                this.nextAvailablePage = null;
                            }

                            if(isSearchQuery){

                                this.orderList=orderList;
                            }else{

                                appendArray(this.orderList,orderList);
                            }



                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow


                        }.bind(this)
                    });
                }
            },


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
