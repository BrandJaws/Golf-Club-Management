<template id="ordersArchiveTemplate">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>
                Name
            </th>
            <th>
                Email
            </th>
            <th>
                Gender
            </th>
            <th>
                Warnings
            </th>
            <th>
                Actions
            </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(order,orderIndex) in ordersListData">
            <td>@{{ order.firstName }} @{{order.lastName}}</td>
            <td>@{{ order.email }}</td>
            <td>@{{ order.gender }}</td>
            <td>@{{ order.warnings }}</td>
            <td>
                <a :href="generateEditorderRoute('{{Request::url()}}',order.id)" class="blue-cb" >edit</a>
                &nbsp;&nbsp;
                <a href="#." class="del-icon" @click="deleteMemeber('{{Request::url()}}',order.id,orderIndex)"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>
<script>
	


	Vue.component('orders-archive', {
		template: "#ordersArchiveTemplate",
		props: [
			"ordersList"
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