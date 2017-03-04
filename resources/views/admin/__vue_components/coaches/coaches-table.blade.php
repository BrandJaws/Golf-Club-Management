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
                    <tr v-for="coach in coachesListData">
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
                    <a :href="generateEditCoachRoute('{{Request::url()}}',coach.id)" class="blue-cb" >edit</a>
                    &nbsp;&nbsp;
                    <a href="#." class="del-icon" @click="deleteCoach('{{Request::url()}}',coach.id,coachIndex)"><i class="fa fa-trash"></i></a>
            </td>
        </tr>

    </tbody>
</table>
`,
        props: [
            "coachesList"
        ],
		
        computed: {
                    coachesListData: function () {
                                        return this.coachesList;
                                      }
        },
        methods: {
                generateEditCoachRoute: function(baseRouteToCurrentPage,id){
                    return baseRouteToCurrentPage+'/edit/'+id;
                },
                deleteCoach:function(baseRouteToCurrentPage,id,coachIndex){
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
                                              this.coachesListData.splice(coachIndex,1);
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