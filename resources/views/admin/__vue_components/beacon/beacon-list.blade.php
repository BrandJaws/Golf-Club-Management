<script>
    Vue.component('beacon', {
        template: `
            <table class="table table-hover">
                <thead>
                    <tr>
	          			<th>
	          				Name
	          			</th>
	          			<th>
	          				Court
	          			</th>
	          			<th>
	          				Major
	          			</th>
	          			<th>
	          				Minor
	          			</th>
	          			<th>
	          				Check in Duration
	          			</th>
	          			<th>
	          				Status
	          			</th>
	          			<th>
	          				Action
	          			</th>
	          		</tr>
                </thead>
                <tbody>
                    <tr v-for="beacon in beacons">
                        <td>
                            @{{beacon.name}}
                        </td>
                         <td>
                            @{{beacon.court}}
                        </td>
                        <td>
                            @{{beacon.major}}
                        </td>
                        <td>
                            @{{beacon.minor}}
                        </td>
                        <td>
                            @{{beacon.duration}}
                        </td>
                        <td>
                            @{{beacon.status}}
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
            "beaconList"
        ],
        data: function() {
            return {
                beacons:this.beaconList
            }
        }
    })
</script>