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
				<tr v-for="member in membersListData">
					<td>@{{ member.firstName }} @{{member.lastName}}</td>
					<td>@{{ member.email }}</td>
					<td>@{{ member.gender }}</td>
					<td>@{{ member.warnings }}</td>
					<td>
						<a :href="generateEditMemberRoute('{{Request::url()}}',member.id)" class="blue-cb" >edit</a>
						&nbsp;&nbsp;
						<a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			</tbody>
		`,
		props: [
			"membersList"
		],
		
                computed: {
                            membersListData: function () {
                                                return this.membersList;
                                              }
                },
		methods: {
			generateEditMemberRoute: function(baseRouteToCurrentPage,id){
                            return baseRouteToCurrentPage+'/edit/'+id;
			}
		}
	});
</script>