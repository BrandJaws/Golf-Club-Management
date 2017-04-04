{{--<div id="cancelTest">--}}
    {{--<reservation-cancel-popup v-if="showCancelPopup"></reservation-cancel-popup>--}}
{{--</div>--}}
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
                                  <tr v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot" :key="timeSlotIndex" v-if="timeSlot.isVisibleUnderFilter"> 
                                    <td >@{{timeSlot.timeSlot}}</td>
                                    <td width="80%">
                                      <ul class="members-add">
                                          <reservation-player-tag  v-for="reservationPlayer in timeSlot.reservations[0].players " :reservationPlayer="reservationPlayer" ></reservation-player-tag>
                                          <li class="add-btn" @click="editReservationClicked(reservationByDate.reserved_at,timeSlot.timeSlot,timeSlot.reservations[0])"><a href="#."><i class="fa fa-plus"></i></a></li>
                                      </ul>
                                    </td>
                                    <td>
                                      <div class="ts-action-btn">
                                          {{--<a href="#." class="cancel-btn" @click="deleteReservationClicked(timeSlot.reservations[0].reservation_id)"><i class="fa fa-ban"></i></a>--}}
                                    <a href="#." class="cancel-btn" @click="deleteReservationClicked(timeSlot.reservations[0].reservation_id)"><i class="fa fa-ban"></i></a>
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
          //reservationsByDateData:this.reservationsByDate
      }
    },
    computed:{
        reservationsByDateData:function(){
            return this.reservationsByDate;
        }
    },
    methods: {
        cancelPopupEmit:function(){
            this.$emit('display-cancel-popup');
//            console.log('emitted');
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
        deleteReservationClicked(reservation_id){
            this.$emit('delete-reservation',reservation_id);

        }
    },

  
});
</script>
