
<template id="ordersTable">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>
                Member Name
            </th>
            <th>
                Gross Total
            </th>
            <th>
                Created at
            </th>
            <th>
                Status
            </th>
        </tr>
        </thead>
        <tbody v-if="ordersList.length < 1">
            <tr><td>No Orders Found</td></tr>
        </tbody>
        <tbody v-else>
        <tr v-for="(order,orderIndex) in ordersList">
            <td>
                <a :href="baseUrl+'/admin/restaurant/orders/'+order.id">@{{ order.member_name }}</a>
            </td>
            <td>$ @{{ order.gross_total }}</td>
            <td>@{{ order.time }}</td>
            <td>
                <button v-if="order.in_process == 'NO'" class="btn btn-sm btn-outline rounded b-primary text-primary" v-on:click = "markAsInProcess(order)">In Process</button>
                <button v-else class="btn btn-sm rounded rounded red disabled">In Process</button>

                <button v-if="order.is_ready == 'NO'" class="btn btn-sm btn-outline rounded b-info text-info" v-on:click = "markAsIsReady(order)">Is Ready</button>
                <button v-else class="btn btn-sm rounded rounded blue disabled">Is Ready</button>

                <button v-if="order.is_served == 'NO'"class="btn btn-sm btn-outline rounded b-success text-success" v-on:click = "markAsIsServed(order)">Is Served</button>
                <button v-else class="btn btn-sm rounded rounded green disabled">Is Served</button>
            </td>
        </tr>
        </tbody>
    </table>



</template>
<script>

    Vue.component('orders-table', {
        template: "#ordersTable",
        props: [
            "baseUrl",
            "ordersList",

        ],
        methods: {

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

                        this.$emit('order-updated',msg.response);

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

                        this.$emit('order-updated',msg.response);

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

                        this.$emit('order-updated',msg.response);

                    }.bind(this),

                    error: function(jqXHR, textStatus ) {
                        //this.ajaxRequestInProcess = false;
                        console.log(jqXHR.responseText);
                        //Error code to follow


                    }.bind(this)
                });
            },

        }
    });
</script>