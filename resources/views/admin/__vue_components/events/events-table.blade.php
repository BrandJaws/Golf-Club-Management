<script>

    Vue.component('events', {

        template: `
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Lesson Name</th>
                        <th>Coach</th>
                        <th>Total Seats</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Seats Reserved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(event,memberIndex) in eventListData">
                        <td>
                            @{{ event.name }}
                        </td>
                        <td>
                            @{{ event.coach }}
                        </td>
                        <td>
                            @{{ event.seats }}
                        </td>

                        <td>
                            @{{ event.startDate }}
                        </td>

                        <td>
                            @{{ event.endDate }}
                        </td>
                        <td>
                            @{{ event.seatsReserved }}
                        </td>
                        <td>
                            <a :href="generateEditRoute('{{Request::url()}}',event.id)" class="blue-cb">edit</a>
                            &nbsp;&nbsp;
                            <a href="#." class="del-icon" @click="destroy('{{Request::url()}}',event.id,memberIndex)"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                </table>
                `,
        props: [
            "eventsList"
        ],
        data: function(){
            return {
                eventListData:this.eventsList
            }
        },
        methods: {
        	generateEditRoute: function(baseRouteToCurrentPage,id){
                return baseRouteToCurrentPage+'/edit/'+id;
            },
            destroy:function(baseRouteToCurrentPage,id,memberIndex){
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
                                          this.eventListData.splice(memberIndex,1);
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