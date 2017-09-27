<template id="ordersArchiveTemplate">
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
               Vat
            </th>
            <th>
                Net Total
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
            <td>@{{ '$ ' + order.vat }}</td>
            <td>@{{ '$ ' + order.net_total }}</td>
            <td>@{{ order.time }}</td>
            <td>
                Served
            </td>
        </tr>
        </tbody>
    </table>
</template>
<script>
	


	Vue.component('orders-archive', {
		template: "#ordersArchiveTemplate",
		props: [
			"ordersList",
            "baseUrl"
		],
		
                computed: {
                            ordersListData: function () {
                                                return this.ordersList;
                                              }
                },
		methods: {
			generateEditorderRoute: function(baseRouteToCurrentPage,id){
                            return baseRouteToCurrentPage+'/edit/'+id;
			},
                        deleteMemeber:function(baseRouteToCurrentPage,id,orderIndex){
                            _url = baseRouteToCurrentPage+'/'+id
                            var request = $.ajax({
                                        
                                        url: _url,
                                        method: "POST",
                                        headers: {
                                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                                        },
                                        data:{
                                            
                                            _method:"DELETE",
                                            _token: "{{ csrf_token() }}",
                                            
                                        },
                                        success:function(msg){
                                            
                                                  if(msg=="success"){
                                                      this.ordersListData.splice(orderIndex,1);
                                                  }else{
                                                      
                                                  }
                                                    
                                                }.bind(this),

                                        error: function(jqXHR, textStatus ) {
                                                    this.ajaxRequestInProcess = false;
                                                    $("body").append(jqXHR.responseText);
                                                    //Error code to follow


                                               }.bind(this)
                                    }); 
                        }
		}
	});
</script>