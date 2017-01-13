@include("admin.__vue_components.reservations.reservation-player-tag")
<script>

Vue.component('reservation-tab-tables', {
    template: `
              		
                <div class="tab-content p-a m-b-md">
                    <div v-for="(reservationByDate,reservationIndex) in reservationsByDateData" :id="'tab'+(reservationIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationIndex == 0 ? 'active' : '']"  >
                      <div class="tab-pane-content">

                        <div class="table-responsive">
                            <table class="table table-hover b-t">
                                <tbody>
                                  <tr v-for="(reservation,reservationIndex) in reservationByDate.reservationsByTimeSlot" :key="reservationIndex">
                                    <td >@{{reservation.timeSlot}}</td>
                                    <td width="80%">
                                      <ul class="members-add">
                                          <reservation-player-tag v-for="reservationPlayer in reservation.players" :reservationPlayer="reservationPlayer" @deletePlayer="deletePlayer(reservationPlayer,$event)"></reservation-player-tag>
                                          <li class="add-btn" @click="editReservationClicked(reservation)"><a href="#."><i class="fa fa-plus"></i></a></li>
                                      </ul>
                                    </td>
                                    <td>
                                      <div class="ts-action-btn">
                                          <a href="#." class="save-btn"><i class="fa fa-save"></i></a>&nbsp;
                                          <a href="#." class="cancel-btn"><i class="fa fa-ban"></i></a>
                                      </div>
                                    </td>

                                  </tr>

                                </tbody>
                            </table>
                        </div>

                      </div>
                    </div>
                </div>
                        
          
            `,
    props: [
            "reservationsByDate"
            
            
    ],
    data: function () {
      
      return {
          reservationsByDateData:this.reservationsByDate
      }
    },
    methods: {
        deletePlayer: function(abc,event){
        
            console.log(event);
           // this.$emit('delete-player');
            
        },
        editReservationClicked: function(reservation){
            
             this.$emit('edit-reservation',reservation);
        }
    }
  
});
</script>
