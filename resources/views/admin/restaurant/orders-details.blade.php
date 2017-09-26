@extends('admin.__layouts.admin-layout')
@section('heading')
    Order
@endSection
@section('main')
    <div ui-view class="app-body" id="view">
        <!-- ############ PAGE START-->
        <div id="order-view" class="segments-main padding">
            <div class="row">
                <div class="segments-inner">
                    <div class="box">
                        <div class="inner-header">
                            <div class="">
                                <div class="col-md-8">
                                    <div class="search-form">
                                        <h3>Order Details</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="" class="btn btn-def pull-right hidden-print" onclick="window.print();"> <i class="fa fa-print"></i> Print</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="padding">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <p class="text-sm">Customer Name:</p>
                                        <h2>@{{ orderDetail.member_name }}</h2>
                                        <p class="m-b-lg"></p>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p class="text-md">@{{ '#'+orderDetail.id }}</p>
                                    </div>
                                </div>
                                <p>
                                    <span class="m-b-10">Order date: <strong>@{{ orderDetail.time }}</strong></span>
                                    <br />
                                    <span class="m-b-10 hidden-print">
                                        Order status:
                                        <button v-if="orderDetail.in_process == 'NO'" class="btn btn-sm btn-outline rounded b-primary text-primary" v-on:click = "markAsInProcess(orderDetail)">In Process</button>
                                        <button v-else class="btn btn-sm rounded rounded red disabled">In Process</button>

                                        <button v-if="orderDetail.is_ready == 'NO'" class="btn btn-sm btn-outline rounded b-info text-info" v-on:click = "markAsIsReady(orderDetail)">Is Ready</button>
                                        <button v-else class="btn btn-sm rounded rounded blue disabled">Is Ready</button>

                                        <button v-if="orderDetail.is_served == 'NO'"class="btn btn-sm btn-outline rounded b-success text-success" v-on:click = "markAsIsServed(orderDetail)">Is Served</button>
                                        <button v-else class="btn btn-sm rounded rounded green disabled">Is Served</button>
                                    </span>
                                    <br />
                                    <span class="m-b-10">
                                        Order ID: <strong>@{{ '#'+orderDetail.id }}</strong>
                                    </span>
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-striped white b-a">
                                        <thead>
                                        <tr>
                                            <th style="width: 60px">QTY</th>
                                            <th>DESCRIPTION</th>
                                            <th style="width: 140px">UNIT PRICE</th>
                                            <th style="width: 90px">TOTAL</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(detail, index) in orderDetail.restaurant_order_details">
                                            <td>@{{ detail.quantity }}</td>
                                            <td>@{{ detail.restaurant_product_name }}</td>
                                            <td>@{{ detail.price }}</td>
                                            <td>@{{ '$ ' + detail.sale_total }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                                            <td>@{{ '$ ' + orderDetail.gross_total }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-border"><strong>VAT Included in Total</strong></td>
                                            <td>$0.00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right no-border"><strong>Total</strong></td>
                                            <td><strong>@{{ '$ ' + orderDetail.gross_total }}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-specific-scripts')
    <script>
        //var baseUrl = "{{url('admin/member')}}";
        var _baseUrl = "{{url('')}}";
        var order = {!! $order !!};

        var vue = new Vue({
            el: "#order-view",
            data: {
                baseUrl:_baseUrl,
                orderDetail:order,
                entityBasedNotificationIdForReservationUpdation:{{ $entity_based_notification_id }},
            },
            methods:{
                markAsInProcess:function(restaurantOrder){
                    if(restaurantOrder.in_process == "YES"){
                        return;
                    }
                    _url = this.baseUrl+'/admin/restaurant/orders/mark-in-process';
                    var request = $.ajax({

                        url: _url,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{

                            restaurant_order_id:restaurantOrder.id,

                        },
                        success:function(msg){

                            this.updateOrdersListOnUpdateActionByAdmin(msg.response);

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            //this.ajaxRequestInProcess = false;
                            console.log(jqXHR.responseText);
                            //Error code to follow


                        }.bind(this)
                    });
                },
                markAsIsReady:function(restaurantOrder){
                    if(restaurantOrder.is_ready == "YES"){
                        return;
                    }
                    _url = this.baseUrl+'/admin/restaurant/orders/mark-is-ready';
                    var request = $.ajax({

                        url: _url,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{

                            restaurant_order_id:restaurantOrder.id,

                        },
                        success:function(msg){

                            this.updateOrdersListOnUpdateActionByAdmin(msg.response);

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            //this.ajaxRequestInProcess = false;
                            console.log(jqXHR.responseText);
                            //Error code to follow


                        }.bind(this)
                    });
                },
                markAsIsServed:function(restaurantOrder){
                    if(restaurantOrder.is_served == "YES"){
                        return;
                    }
                    _url = this.baseUrl+'/admin/restaurant/orders/mark-is-served';
                    var request = $.ajax({

                        url: _url,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{

                            restaurant_order_id:restaurantOrder.id,

                        },
                        success:function(msg){

                            this.updateOrdersListOnUpdateActionByAdmin(msg.response);

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            //this.ajaxRequestInProcess = false;
                            console.log(jqXHR.responseText);
                            //Error code to follow


                        }.bind(this)
                    });
                },
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
                            load_order_details:true,

                        },
                        success:function(msg){

                            this.updateOrderOnReceivingAdminPushNotification(msg.response);
                            this.entityBasedNotificationIdForReservationUpdation = msg.response.entity_based_notification_id;

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            this.ajaxRequestInProcess = false;

                            //Error code to follow
                            console.log(jqXHR);

                        }.bind(this)
                    });



                },
                updateOrderOnReceivingAdminPushNotification:function(updatedOrdersData){

                    //Redirect to orders if the opened order was deleted
                    if(updatedOrdersData.deletedOrders.indexOf(this.orderDetail.id) > -1){
                        window.location = this.baseUrl+'/admin/restaurant/orders';
                        return;
                    }

                    //Update Order if it was edited
                    //Insert New Orders
                    for(var orderIndex in updatedOrdersData.updatedOrNew){

                        if(this.orderDetail.id == updatedOrdersData.updatedOrNew[orderIndex].id ){

                            this.orderDetail.gross_total = updatedOrdersData.updatedOrNew[orderIndex].gross_total;
                            this.orderDetail.in_process = updatedOrdersData.updatedOrNew[orderIndex].in_process;
                            this.orderDetail.is_ready = updatedOrdersData.updatedOrNew[orderIndex].is_ready;
                            this.orderDetail.is_served = updatedOrdersData.updatedOrNew[orderIndex].is_served;
                            this.orderDetail.restaurant_order_details = updatedOrdersData.updatedOrNew[orderIndex].restaurant_order_details;
                            break;
                        }


                    }



                },
                updateOrdersListOnUpdateActionByAdmin:function(order){


                        this.orderDetail.gross_total = order.gross_total;
                        this.orderDetail.in_process = order.in_process;
                        this.orderDetail.is_ready = order.is_ready;
                        this.orderDetail.is_served = order.is_served;


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
