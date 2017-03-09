<script>
    Vue.component('courses', {
        template: `
            <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                Course Name
                            </th>
                            <th>
                                Open Time
                            </th>
                            <th>
                                Close Time
                            </th>
                            <th>
                                Booking Interval
                            </th>
                            <th>
                                Booking Duration
                            </th>
                            <th>
                                Number of Holes
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(course, bIndex) in listData">
                            <td>
                                @{{ course.name }}
                            </td>
                            <td>
                                @{{ course.openTime }}
                            </td>
                            <td>
                                @{{ course.closeTime }}
                            </td>
                            <td>
                                @{{ course.bookingInterval }}
                            </td>
                            <td>
                                @{{ course.bookingDuration/60 }} Hours
                            </td>
                            <td>
                                @{{ course.numberOfHoles }}
                            </td>
                            <td>
                                <a :href="generateEditRoute('{{Request::url()}}',course.id)" class="blue-cb" >edit</a>
						        &nbsp;&nbsp;
						        <a href="#." @click="deleteObject('{{Request::url()}}',course.id,bIndex)" class="del-icon"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>

                    </tbody>
                </table>
        `,
        props: [
            "courses"
        ],
        computed: {
                            listData: function () {
                                                return this.courses;
                                              }
                },
		methods: {
			generateEditRoute: function(baseRouteToCurrentPage,id){
                            return baseRouteToCurrentPage+'/edit/'+id;
			},
			deleteObject:function(baseRouteToCurrentPage,id,bIndex){
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
                                                      this.listData.splice(bIndex,1);
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