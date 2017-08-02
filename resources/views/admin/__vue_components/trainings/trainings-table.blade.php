<script>

    Vue.component('trainings', {

        template: `
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Training Name</th>
                        <th>Coach</th>
                        <th>Total Seats</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Seats Reserved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(training,memberIndex) in trainingListData">
                        <td>
                            @{{ training.name }}
                        </td>
                        <td>
                            @{{ training.coach }}
                        </td>
                        <td>
                            @{{ training.seats }}
                        </td>

                        <td>
                            @{{ training.startDate }}
                        </td>

                        <td>
                            @{{ training.endDate }}
                        </td>
                        <td>
                            @{{ training.seatsReserved }}
                        </td>
                        <td>
                            <a :href="generateEditRoute('{{Request::url()}}',training.id)" class="blue-cb">edit</a>
                            &nbsp;&nbsp;
                            <a href="#." class="del-icon" @click="destroy('{{Request::url()}}',training.id,memberIndex)"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                </tbody>
                </table>
                `,
        props: [
            "trainingsList"
        ],
        data: function(){
            return {
                trainingListData:this.trainingsList
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
                                          this.trainingListData.splice(memberIndex,1);
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