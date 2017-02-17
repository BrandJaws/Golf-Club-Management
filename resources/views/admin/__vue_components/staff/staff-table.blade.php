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
	          				Actions
	          			</th>
	          		</tr>
                </thead>
                <tbody>
                    <tr v-for="staff in staffMembers">
                        <td>
                            @{{staff.firstName}} @{{staff.lastName}}
                        </td>
                         <td>
                            @{{staff.email}}
                        </td>
                        <td>
                            @{{staff.phone}}
                        </td>
                        
                        <td>
                            <a href="#." class="blue-cb" :href="generateEditMemberRoute('{{Request::url()}}',staff.id)">edit</a>&nbsp;&nbsp;&nbsp;
                            <a href="#." class="del-icon" @click="deleteMemeber('{{Request::url()}}',staff.id,memberIndex)"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>

                </tbody>
            </table>
        `,
        props: [
            "staffList"
        ],
		
        computed: {
                    staffMembers: function () {
                                        return this.staffList;
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
                                              this.staffMembers.splice(memberIndex,1);
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
    })
</script>