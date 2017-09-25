@extends('admin.__layouts.admin-layout')
@section('heading')
    Orders
@endSection
@section('main')
    <div ui-view class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div id="orders-list-table" class="segments-main padding">
            <div class="row">
                <div class="segments-inner">
                    <div class="box">
                        <div class="inner-header">
                            <div class="">
                                <div class="col-md-8">
                                    <div class="search-form">
                                        <h3>In Process Orders</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">

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
                        <orders-table :orders-list="ordersList" :base-url="baseUrl" v-on:order-updated="updateOrdersListOnUpdateActionByAdmin($event)"></orders-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-specific-scripts')
    @include("admin.__vue_components.restaurant.orders")
    <script>
        var _baseUrl = "{{url('')}}";
        var orders = {!! $orders !!};

        var vue = new Vue({
            el: "#orders-list-table",
            data: {
                ordersList: orders,
                entityBasedNotificationIdForReservationUpdation:{{ $entity_based_notification_id }},
                baseUrl:_baseUrl,
            },
            methods:{
                getAdminNotificationsForRestaurantUpdationEvent:function(){


                    var request = $.ajax({

                        url: "{{url('admin/live-notifications/restaurant-order-updation')}}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{
                            _token: "{{ csrf_token() }}",
                            entity_based_notification_id:this.entityBasedNotificationIdForReservationUpdation,
                            load_order_details:false,

                        },
                        success:function(msg){
                            console.log(msg);
                            this.updateOrdersListOnReceivingAdminPushNotification(msg.response);
                            this.entityBasedNotificationIdForReservationUpdation = msg.response.entity_based_notification_id;

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow
                            console.log(jqXHR);

                        }.bind(this)
                    });



                },
                updateOrdersListOnReceivingAdminPushNotification:function(updatedOrdersData){

                    //Remove Deleted Orders
                    for(var orderToDeleteIndex in updatedOrdersData.deletedOrders){
                        for(var orderIndex in this.ordersList){
                            if(this.ordersList[orderIndex].id == updatedOrdersData.deletedOrders[orderToDeleteIndex] ){
                                this.ordersList.splice(orderIndex,1);
                                break;
                            }
                        }
                    }

                    //Update Existing Orders
                    for(var orderToUpdateIndex = updatedOrdersData.updatedOrNew.length -1;  orderToUpdateIndex>= 0; orderToUpdateIndex--){

                        for(var orderIndex in this.ordersList){

                            if(this.ordersList[orderIndex].id == updatedOrdersData.updatedOrNew[orderToUpdateIndex].id ){

                                if(updatedOrdersData.updatedOrNew[orderToUpdateIndex].in_process == "YES"
                                   &&  updatedOrdersData.updatedOrNew[orderToUpdateIndex].is_ready == "YES"
                                   &&  updatedOrdersData.updatedOrNew[orderToUpdateIndex].is_served == "YES"){


                                    this.ordersList.splice(orderIndex,1);

                                }else {
                                    this.ordersList[orderIndex].gross_total = updatedOrdersData.updatedOrNew[orderToUpdateIndex].gross_total;
                                    this.ordersList[orderIndex].in_process = updatedOrdersData.updatedOrNew[orderToUpdateIndex].in_process;
                                    this.ordersList[orderIndex].is_ready = updatedOrdersData.updatedOrNew[orderToUpdateIndex].is_ready;
                                    this.ordersList[orderIndex].is_served = updatedOrdersData.updatedOrNew[orderToUpdateIndex].is_served;
                                }

                                updatedOrdersData.updatedOrNew.splice(orderToUpdateIndex,1);
                                break;


                            }


                        }
                    }


                    //Insert New Orders
                    for(var newOrderIndex in updatedOrdersData.updatedOrNew){

                        if(updatedOrdersData.updatedOrNew[newOrderIndex].is_served == "NO"){

                            this.ordersList.push(updatedOrdersData.updatedOrNew[newOrderIndex]);

                        }

                    }



                },
                updateOrdersListOnUpdateActionByAdmin:function(order){

                        for(var orderIndex in this.ordersList){
                            if(this.ordersList[orderIndex].id == order.id ){

                                if(order.in_process == "YES"
                                        &&  order.is_ready == "YES"
                                        &&  order.is_served == "YES"){

                                    this.ordersList.splice(orderIndex,1);

                                }else {
                                    this.ordersList[orderIndex].gross_total = order.gross_total;
                                    this.ordersList[orderIndex].in_process = order.in_process;
                                    this.ordersList[orderIndex].is_ready = order.is_ready;
                                    this.ordersList[orderIndex].is_served = order.is_served;
                                }


                                break;


                            }
                        }

                }

            },
        });


        var socketUrl = "{{env("SOCKET_URL")}}";
        var socket = io(socketUrl);
        socket.on('reconnect', function(){

            vue.getAdminNotificationsForRestaurantUpdationEvent();
        });
        socket.on('admin-notifications:RestaurantOrderUpdation{{Auth::user()->club_id}}',function(data){

            if(data){
                vue.getAdminNotificationsForRestaurantUpdationEvent();
            }


        });


    </script>
@endSection
