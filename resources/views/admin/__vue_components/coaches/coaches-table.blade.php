<script>
    Vue.component('coaches', {
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
	          				Specialities
	          			</th>
	          			<th>
	          				Actions
	          			</th>
	          		</tr>
                </thead>
                <tbody>
                    <tr v-for="coach in coaches">
                        <td>
                            @{{coach.name}}
            </td>
             <td>
                @{{coach.email}}
            </td>
            <td>
                @{{coach.contact}}
            </td>
            <td>
                @{{coach.spcl}}
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
            "coachesList"
        ],
        data: function() {
            return {
                coaches:this.coachesList
            }
        }
    })
</script>