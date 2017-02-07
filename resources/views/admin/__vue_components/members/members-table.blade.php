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
					<td>@{{ member.name }}</td>
					<td>@{{ member.email }}</td>
					<td>@{{ member.gender }}</td>
					<td>@{{ member.warnings }}</td>
					<td>
						<a href="#." class="blue-cb" @click="editMember(member.email)">edit</a>
						<a href="#."><i class="fa fa-trash"></i></a>
					</td>
				</tr>
			</tbody>
		`,
		props: [
			"membersList"
		],
		data: function(){
			return {
				membersListData:this.membersList
			}
		},
		methods: {
			editMember: function(id){

			}
		}
	});
</script>