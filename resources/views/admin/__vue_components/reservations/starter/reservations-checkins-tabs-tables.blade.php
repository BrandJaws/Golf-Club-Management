{{--<div id="cancelTest">--}}
{{--<reservation-cancel-popup v-if="showCancelPopup"></reservation-cancel-popup>--}}
{{--</div>--}}
@include("admin.__vue_components.reservations.starter.reservation-checkins-player-tag")
<script>
    Vue.component('reservation-tab-tables', {
        template: `

                <div class="tab-content p-a m-b-md">
                    <div v-for="(reservationByDate,reservationByDateIndex) in reservationsByDateData" :id="'tab'+(reservationByDateIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationByDateIndex == 0 ? 'active' : '']"  >
                      <div class="tab-pane-content">

                        <div class="table-responsive">
                            <table class="table table-hover b-t">
                                <tbody>
                                  <tr v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot" :key="timeSlotIndex" v-if="timeSlot.isVisibleUnderFilter" v-bind:class="(timeSlot.reservations[0].game_status == 'STARTED') ? 'success' : ''">
                                    <td >@{{timeSlot.timeSlot}}</td>
                                    <td width="80%">
                                      <ul class="members-add">
                                          <reservation-player-tag  v-for="(reservationPlayer,reservationPlayerIndex) in timeSlot.reservations[0].players " :reservationPlayer="reservationPlayer" :reservation-indices="{dateIndexDraggedFrom:reservationByDateIndex,timeIndexDraggedFrom:timeSlotIndex,playerIndexDragged:reservationPlayerIndex}" :coming-on-time="reservationPlayer.comingOnTime" :club-entry="reservationPlayer.club_entry" :game-entry="reservationPlayer.game_entry" ></reservation-player-tag>
                                      </ul>
                                    </td>
                                    <td>
                                      <div class="ts-action-btn">
                                          {{--<a href="#." class="cancel-btn" @click="deleteReservationClicked(timeSlot.reservations[0].reservation_id)"><i class="fa fa-ban"></i></a>--}}
            <a class="btn btn-def" title="Start Game" @click="startGameClicked(timeSlot.reservations[0].reservation_id)"><i class="fa fa-check"></i></a>
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
//                startTheGame:false,
            }
        },
        computed:{
            reservationsByDateData:function(){
                return this.reservationsByDate;
            }
        },
        methods: {

            startGameClicked(reservation_id){
                this.$emit("start-game-clicked",reservation_id);
            }

        },


    });
</script>
