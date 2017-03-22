@include("admin.__vue_components.reservations.reservation-player-tag")
<script>

Vue.component('reservation-tab-divs', {
    template: `
              		
                <div class="tab-content m-b-md">
                      <div v-for="(reservationByDate,reservationIndex) in reservationsByDate" v-if="reservationByDate.dateIsVisible" :id="'tab'+(reservationIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationIndex == 0 ? 'active' : '']" >
                        <div class="tab-pane-content">
                            <div class="booked-list">
                                    <div class="col-md-3 timeSlots3" v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot" v-if="timeSlot.isVisibleUnderFilter">
                                            <div class="booking-box text-center" >
                                                <h3>@{{timeSlot.timeSlot}}</h3>
                                                <p class="min-height-names">
                                                    <span v-if="timeSlot.reservations[0].reservation_id == ''">
                                                        Time Slot Vacant   
                                                    </span>
                                                    <span v-else v-for="(reservationPlayer,reservationPlayerIndex) in timeSlot.reservations[0].players.slice(0,4)">
                                                       @{{reservationPlayer.member_name}}
                                                    </span>
                                                    <span v-if="timeSlot.reservations[0].players.length > 4">
                                                       @{{"..."}}
                                                     </span>


        </p>
        <p >
            <a href="#."data-toggle="modal" data-target="#m-a-a" ui-toggle-class="fade-down" ui-target="#animate" :class="timeSlot.reservations[0].reservation_id !=  '' ? 'booked' : ''" @click="editReservationClicked(reservationByDate.reserved_at,timeSlot.timeSlot,timeSlot.reservations[0])">@{{ computedButtonTitleValue(timeSlot.reservations) }}</a>\n\
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
          
      }

    },
    methods: {
        deletePlayer: function(abc,event){
        
       
            //this.$emit('deletePlayer');
            
        },
        editReservationClicked: function(_reserved_at,_timeSlot,reservation){
            //emit edit reservation event if already has reservations
            //else emit new reservation event
              if(reservation.reservation_id == 0){
                 this.$emit('new-reservation',{reserved_at:_reserved_at,timeSlot:_timeSlot,players:[],guests:0});
                 
             }else{
                 this.$emit('edit-reservation',reservation);
             }
             
        },
        computedButtonTitleValue: function(reservations) {
            if(reservations[0].reservation_id == "") {
                return "Book Now";
            }
            else {
                return "Booked";
                
            }
        }
    }
  
});
</script>
