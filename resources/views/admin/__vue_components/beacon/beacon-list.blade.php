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
	          			<?php /* ?><th>
	          				Check in Duration
	          			</th>
	          			<?php */ ?>
	          			<th>
	          				Status
	          			</th>
	          			<th>
	          				Action
	          			</th>
	          		</tr>
                </thead>
                <tbody>
                    <tr v-for="beacon in beaconsListData">
                        <td>
                            @{{beacon.name}}
                        </td>
                         <td>
                            @{{beacon.courseName}}
                        </td>
                        <td>
                            @{{beacon.major}}
                        </td>
                        <td>
                            @{{beacon.minor}}
                        </td>
                        <?php /* ?> <td>
                            @{{beacon.duration}}
                        </td>
                        <?php */ ?>
                        <td>
                            @{{beacon.status}}
                        </td>
                        <td>
                            <a :href="generateEditBeaconRoute('{{Request::url()}}',beacon.id)" class="blue-cb" >edit</a>
                            &nbsp;&nbsp;&nbsp;
                            <a href="#." class="del-icon" @click="deleteBeacon('{{Request::url()}}',beacon.id,beaconIndex)"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>

                </tbody>
            </table>
            `,
        props: [
            "beaconList"
        ],
        computed: {
                            beaconsListData: function () {
                                                return this.beaconList;
                                              }
                },
		methods: {
			generateEditBeaconRoute: function(baseRouteToCurrentPage,id){
                            return baseRouteToCurrentPage+'/edit/'+id;
			},
                        deleteBeacon:function(baseRouteToCurrentPage,id,beaconIndex){
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
                                                      this.beaconsListData.splice(beaconIndex,1);
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