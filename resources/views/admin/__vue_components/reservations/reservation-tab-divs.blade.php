@include("admin.__vue_components.reservations.reservation-player-tag")
<script>

Vue.component('reservation-tab-divs', {
    template: `
              		
                <div class="tab-content m-b-md">
                      <div v-for="(reservationByDate,reservationIndex) in reservationsByDateData" :id="'tab'+(reservationIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationIndex == 0 ? 'active' : '']" >
                        <div class="tab-pane-content">
                            <div class="booked-list">
                                    <div class="col-md-3 timeSlots3" v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot">
                                            <div class="booking-box text-center" >
                                            <h3>@{{timeSlot.timeSlot}}</h3>
                                                <p class="min-height-names">
                                                    <span v-if="timeSlot.reservations.length == 0">
                                                        Time Slot Vacant   
                                                    </span>
                                                    <span v-else v-for="(reservationPlayer,reservationPlayerIndex) in timeSlot.reservations[0].players">
                                                       @{{reservationPlayer.playerName}}
                                                    </span>
                                                    

                                                </p>
                                                <p >
                                                    <a href="#."data-toggle="modal" data-target="#m-a-a" ui-toggle-class="fade-down" ui-target="#animate" :class="timeSlot.reservations.length > 0 ? 'booked' : ''" @click.prevent="editReservationClicked(reservationByDate.date,reservationByDate.day,timeSlot.timeSlot,timeSlot.reservations)">@{{ computedValue(timeSlot.reservations.length) }}</a>\n\
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
    computed: {
//      computedValue : function() {
//          if (reservation.players.length > 0) {
//              return this.value;
//          }
//          else {
//              var value ="Booked";
//              return this.value;
//          }
//      }
    },
    methods: {
        deletePlayer: function(abc,event){
        
       
            //this.$emit('deletePlayer');
            
        },
        editReservationClicked: function(_date,_day,_timeSlot,reservationsArray){
            //emit edit reservation event if already has reservations
            //else emit new reservation event
             if(reservationsArray.length > 0){
                 
                 this.$emit('edit-reservation',reservationsArray[0]);
             }else{
                 this.$emit('new-reservation',{date:_date,day:_day,timeSlot:_timeSlot,players:[],guests:0});
             }
             
        },
        computedValue: function(initialValue) {
            if(initialValue > 0) {
                return "Booked";
            }
            else {
                return "Book Now";
            }
        }
    }
  
});
</script>
