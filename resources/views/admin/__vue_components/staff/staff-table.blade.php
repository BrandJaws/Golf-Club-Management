<script>
    Vue.component('staff', {
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
	          				Contact Number
	          			</th>
	          			<th>
	          				Role
	          			</th>
	          			<th>
	          				Actions
	          			</th>
	          		</tr>
                </thead>
                <tbody>
                    <tr v-for="staff in staffMembers">
                        <td>
                            @{{staff.name}}
                        </td>
                         <td>
                            @{{staff.email}}
                        </td>
                        <td>
                            @{{staff.contact}}
                        </td>
                        <td>
                            @{{staff.role}}
                        </td>
                        <td>
                            <a href="#." class="blue-cb">edit</a>&nbsp;&nbsp;&nbsp;
                            <a href="#." class="del-icon"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>

                </tbody>
            </table>
        `,
        props: [
            "staffList"
        ],
        data: function() {
            return {
                staffMembers:this.staffList
            }
        }
    })
</script>