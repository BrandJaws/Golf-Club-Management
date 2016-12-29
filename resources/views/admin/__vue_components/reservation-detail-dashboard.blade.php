<script>

Vue.component('reservation-detail-dashboard', {
    template: `
                <div class="table-responsive">
                    <table class="table table-hover b-t">
                        <tbody>
                          <tr v-for="(reservation,reservationIndex) in reservationsByTimeSlotData" :key="reservationIndex">
                            <td>@{{reservation.timeSlot}}</td>
                            <td colspan="6">
                              <ul class="members-add">
                                  <li v-for="reservationPlayer in reservation.players">@{{reservationPlayer.playerName}}<a href="#."><i class="fa fa-times"></i></a></li>
                                  <li class="add-btn"><a href="#."><i class="fa fa-plus"></i></a></li>
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
          
            `,
    props: [
            "reservationsByTimeSlot"
            
            
    ],
    data: function () {
      
      return {
          reservationsByTimeSlotData:this.reservationsByTimeSlot
      }
    }
  
});
</script>
