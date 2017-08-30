<script>
	Vue.component('members-table-cotainer', {
		template: `
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
				<slot></slot>
			</table>
		`,
	});


	Vue.component('members-table', {
		template: `
			<tbody>
				<tr v-for="(member,memberIndex) in membersListData">
					<td>@{{ member.firstName }} @{{member.lastName}}</td>
					<td>@{{ member.email }}</td>
					<td>@{{ member.gender }}</td>
					<td>@{{ member.warnings }}</td>
					<td>
						<a :href="generateEditMemberRoute('{{Request::url()}}',member.id)" class="blue-cb" >edit</a>
						&nbsp;&nbsp;
						<a href="#." class="del-icon" @click="deleteMemeber('{{Request::url()}}',member.id,memberIndex)"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			</tbody>
		`,
		props: [
			"membersList"
		],
		
                computed: {
                            membersListData: function () {
                                                return this.productsList;
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