<script>
    Vue.component('orders-table-cotainer', {
        template: `
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
				<slot></slot>
			</table>
		`,
    });


    Vue.component('orders-table', {
        template: `
			<tbody>
				<tr v-for="(order,orderIndex) in ordersListData">
					<td>
					    <a href="">@{{ order.member_name }}</a>
                    </td>
					<td>$ @{{ order.gross_total }}</td>
					<td>@{{ order.created_at }}</td>
					<td>
					    <button class="btn btn-sm btn-outline rounded b-primary text-primary">In Process</button>
					    <!--<button class="btn btn-sm rounded rounded red">In Process</button>-->
					    <button class="btn btn-sm btn-outline rounded b-info text-info">Is Ready</button>
					    <!--<button class="btn btn-sm rounded rounded blue">Is Ready</button>-->
					    <button class="btn btn-sm btn-outline rounded b-success text-success">Is Served</button>
					    <!--<button class="btn btn-sm rounded rounded green">Is Served</button>-->
                    </td>
				</tr>
			</tbody>
		`,
        props: [
            "ordersList"
        ],

        computed: {
            ordersListData: function () {
                return this.ordersList;
            }
        },
        methods: {
            generateEditMemberRoute: function(baseRouteToCurrentPage,id){
                return baseRouteToCurrentPage+'/edit/'+id;
            },
            deleteMemeber:function(baseRouteToCurrentPage,id,memberIndex){
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
                            this.membersListData.splice(memberIndex,1);
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