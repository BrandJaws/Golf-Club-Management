@include("admin.__vue_components.reservation-detail-dashboard")
<script>

Vue.component('reservation-tab-tables', {
    template: `
              		
                <div class="tab-content p-a m-b-md">
                    <div :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationIndex == 0 ? 'active' : '']" v-for="(reservationByDate,reservationIndex) in reservationsByDateData" :id="'tab'+(reservationIndex+1)">
                      <div class="tab-pane-content">

                       <reservation-detail-dashboard  :reservationsByTimeSlot="reservationByDate.reservationsByTimeSlot"></reservation-detail-dashboard>

                      </div>
                    </div>
                </div>
                        
          
            `,
    props: [
            "reservationsByDate"
            
            
    ],
    data: function () {
      console.log(this.reservationsByDate);
      return {
          reservationsByDateData:this.reservationsByDate
      }
    }
  
});
</script>
