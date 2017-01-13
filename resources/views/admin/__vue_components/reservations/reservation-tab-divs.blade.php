@include("admin.__vue_components.reservations.reservation-player-tag")
<script>

Vue.component('reservation-tab-divs', {
    template: `
              		
                <div class="tab-content m-b-md">
                      <div v-for="(reservationByDate,reservationIndex) in reservationsByDateData" :id="'tab'+(reservationIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationIndex == 0 ? 'active' : '']" >
                        <div class="tab-pane-content">
                            <div class="booked-list">
                                    <div class="col-md-3" v-for="(reservation,reservationIndex) in reservationByDate.reservationsByTimeSlot">
                                            <div class="booking-box text-center" >
                                            <h3>@{{reservation.timeSlot}}</h3>
                                                <p class="min-height-names">
                                                    <span v-if="reservation.players.length == 0">
                                                        Time Slot Vacant   
                                                    </span>
                                                    <span v-else v-for="(reservationPlayer,reservationPlayerIndex) in reservation.players" v-if="reservationPlayerIndex < 4">
                                                       @{{reservationPlayer.playerName}}
                                                       <span v-if="reservationPlayerIndex < 4 && reservationPlayerIndex < reservation.players.length -1">
                                                             @{{ reservationPlayerIndex < 3 ? ", &nbsp;" : "..." }}
                                                       </span> 
                                                    </span>
                                                    

                                                </p>
                                                <p >
                                                    <a href="#."data-toggle="modal" data-target="#m-a-a" ui-toggle-class="fade-down" ui-target="#animate" :class="reservation.players.length > 0 ? 'booked' : ''" @click.prevent="editReservationClicked(reservation)">Booked</a>\n\
                                                </p>
                                            </div>
                                    </div>
                                
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
        
       
            console.log(abc);
            console.log(event);
            //this.$emit('deletePlayer');
            
        },
        editReservationClicked: function(reservation){
            
             this.$emit('edit-reservation',reservation);
        }
    }
  
});
</script>
