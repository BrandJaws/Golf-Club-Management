@extends('admin.__layouts.admin-layout')
@section('heading')
    Add orders
    @endSection
@section('main')
	<div ui-view class="app-body" id="view">
		<!-- ############ PAGE START-->
		<div id="orders-vue-container" class="segments-main padding">
            <div class="filters">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{--<input type="text" placeholder="Instructor Name" class="form-control" />--}}
                                <auto-complete-box url="{{url('/portal/coaches/search-list')}}" property-for-id="id" property-for-name="name"
                                                   filtered-from-source="true"
                                                   {{--include-id-in-list="true"--}}
                                                   initial-text-value="" search-query-key="search" field-name="coachId" enable-explicit-selection="false" v-model="filtersForBinding.coachId"> </auto-complete-box>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" v-model="filtersForBinding.age">
                                    <option value="">Age</option>
                                    <option>18-25</option>
                                    <option>26-32</option>
                                    <option>33-45</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="" class="form-control datepicker" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" v-model="filtersForBinding.gender">
                                    <option value="{{\Illuminate\Support\Facades\Config::get('global.gender.all')}}">All</option>
                                    <option value="{{\Illuminate\Support\Facades\Config::get('global.gender.male')}}" >Male</option>
                                    <option value="{{\Illuminate\Support\Facades\Config::get('global.gender.female')}}">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" v-model="filtersForBinding.price">
                                    <option>Price</option>
                                    <option>High Price</option>
                                    <option>Low Price</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="submit" name="" value="Search" class="customFilterBtn" @click.prevent="loadNextPage(true)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
													v-on:input="loadNextPage(true)">
													<button type="submit" class="search-btn">
														<i class="fa fa-search"></i>
													</button>
												</span>
											</div>
										</form>
									</div>
								</div>
								<div class="col-md-4 text-right">
									<a href="" class="btn-def btn"><i
										class="fa fa-plus-circle"></i>&nbsp;Add orders</a>
									<button class="btn-def btn">
										<i class="fa fa-upload"></i>&nbsp;Import CSV
									</button>
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
						<orders-archive :orders-list="ordersList"></orders-archive>
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
            nullFilters:{ coachId:-1, age:"",  date:"{{\Carbon\Carbon::today()->format("m/d/Y")}}", gender:"{{Config::get('global.gender.all')}}",sort:"", },
            filtersReceived : ({!! $ordersWithFilters !!}).filters != null ? ({!! $ordersWithFilters !!}).filters : { coachId:-1, age:"",  date:"{{\Carbon\Carbon::today()->format("m/d/Y")}}", gender:"{{Config::get('global.gender.all')}}",sort:"", },
            filtersForBinding:{
                coachId:-1,
                age:"",
                date:"",
                gender:"{{Config::get('global.gender.all')}}",
                sort:"",

            },
            orderList: ({!! $ordersWithFilters !!}).data,
            ajaxRequestInProcess:false,
            nextAvailablePage:({!! $ordersWithFilters!!}).next_page_url !== null ? 2 : null ,
            searchRequestHeld:false,
            baseUrl:_baseUrl,
            messageBar:{
                type:"",
                message:""
            }




        },
        methods: {


            loadNextPage:function(isSearchQuery){

                var _data ={};

                if(isSearchQuery){
                    this.hideMessageBar();
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

                        url: this.baseUrl+'/portal/order',
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
                            this.filtersReceived = pageDataReceived.filters != null ? pageDataReceived.filters : { coachId:-1, age:"",  date:"{{\Carbon\Carbon::today()->format("m/d/Y")}}", gender:"{{Config::get('global.gender.all')}}",sort:"", };

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
            reservationSuccess:function(updatedorder){
                for(x=0; x<this.orderList.length; x++){
                    if(this.orderList[x].id == updatedorder.id){

                        this.orderList[x].age = updatedorder.age;
                        this.orderList[x].coach = updatedorder.coach;
                        this.orderList[x].dates = updatedorder.dates;
                        this.orderList[x].promotionContent = updatedorder.image;
                        this.orderList[x].name = updatedorder.name;
                        this.orderList[x].seatsAvailable = updatedorder.seatsAvailable;
                        this.orderList[x].reservation_player_id = updatedorder.reservation_player_id;



                    }
                }
                this.showMessageInMessageBar("success",updatedorder.reservationStatusMessage);
            },
            reservationFailure:function(message){

                this.showMessageInMessageBar("error",message);
            },
            showMessageInMessageBar:function(type,message){
                this.messageBar.type = type;
                this.messageBar.message = message;
            },
            hideMessageBar:function(){
                this.messageBar.type = "";
                this.messageBar.message = "";
            }
        },





    });

    vue.$nextTick(function(){


        $( ".datepicker" ).datepicker()
                .on('changeDate', function(e) {

                    vue.filtersForBinding.date = $( ".datepicker" ).val();



                });
        $( ".datepicker" ).datepicker()
                .on('input', function(e) {

                    vue.filtersForBinding.date = $( ".datepicker" ).val();



                });



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
