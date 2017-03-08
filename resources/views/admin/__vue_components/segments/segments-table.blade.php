<script>

Vue.component('segments-table', {
    template: `
              		
                <table class="table table-hover b-t">
                    <tbody>
                        <tr v-for="segment in segmentsListData">
                            <td>
                                <div class="section-1 sec-style">
                                    <h3>@{{segment.name}}</h3>
                                    <p>@{{segment.description}}</p>
                                </div>
                            </td>
                            <td>
                                <div class="section-2 sec-style">
                                    <h3>@{{segment.coverage}}</h3>
                                    <p>Reach</p>
                                </div>
                            </td>
                            <td>
                                <div class="section-3 sec-style">
                                    <p>@{{segment.dateTime}}</p>
                                </div>
                            </td>
                            <td>
                                <div class="section-3 sec-style">
                                    <p><a href="#." class="blue-c">@{{segment.status}}</a></p>
                                </div>
                            </td>
                            <td>
                                <div class="section-3 sec-style">
                                    <p>
                                        <span><a href="#." class="blue-cb">edit</a></span>&nbsp;&nbsp;&nbsp;
                                        <span><a href="#." class="del-icon"><i class="fa fa-trash"></i></a></span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                        
          
            `,
    props: [
            "segmentsList"
            
            
    ],
    data: function () {
      
      return {
          segmentsListData:this.segmentsList
      }
    },
    methods: {
       
    }
  
});
</script>
