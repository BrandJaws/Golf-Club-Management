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
                                  <tr v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot" :key="timeSlotIndex" v-if="timeSlot.isVisibleUnderFilter" v-bind:class="(timeSlot.gameStarted) ? 'success' : ''">
                                    <td >@{{timeSlot.timeSlot}}</td>
                                    <td width="80%">
                                      <ul class="members-add" @dragover.prevent @drop.prevent="dragDroppedPlayer($event,reservationByDateIndex, timeSlotIndex)">
                                          <reservation-player-tag  v-for="(reservationPlayer,reservationPlayerIndex) in timeSlot.reservations[0].players " :reservationPlayer="reservationPlayer" :reservation-indices="{dateIndexDraggedFrom:reservationByDateIndex,timeIndexDraggedFrom:timeSlotIndex,playerIndexDragged:reservationPlayerIndex}" draggable="true" @dragstart="dragStarted($event,{dateIndexDraggedFrom:reservationByDateIndex,timeIndexDraggedFrom:timeSlotIndex,playerIndexDragged:reservationPlayerIndex})" ></reservation-player-tag>
                                      </ul>
                                    </td>
                                    <td>
                                      <div class="ts-action-btn">
                                          {{--<a href="#." class="cancel-btn" @click="deleteReservationClicked(timeSlot.reservations[0].reservation_id)"><i class="fa fa-ban"></i></a>--}}
            <a class="btn btn-def" title="Start Game" @click="startGameClicked(timeSlot)"><i class="fa fa-check"></i></a>
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

            },
            startGameClicked(timeSlot){
                console.log("logged");
                if(timeSlot.gameStarted) {
                    timeSlot.gameStarted = false;
                    console.log(timeSlot.gameStarted);
                }
                else {
                    timeSlot.gameStarted = true;
                }
            },
            dragDroppedPlayer:function (event, dateIndexDroppedInto,timeIndexDroppedInto) {
                indicesObjectOfDraggedPlayer = JSON.parse(event.dataTransfer.getData("indicesObjectOfDraggedPlayer"));
                dragDropIndicesData = {};
                dragDropIndicesData.dateIndexDraggedFrom = indicesObjectOfDraggedPlayer.dateIndexDraggedFrom;
                dragDropIndicesData.timeIndexDraggedFrom = indicesObjectOfDraggedPlayer.timeIndexDraggedFrom;
                dragDropIndicesData.playerIndexDragged = indicesObjectOfDraggedPlayer.playerIndexDragged;
                dragDropIndicesData.dateIndexDroppedInto = dateIndexDroppedInto;
                dragDropIndicesData.timeIndexDroppedInto = timeIndexDroppedInto;

                this.$emit("drag-drop-operation",dragDropIndicesData);
            },
            dragStarted:function(event,reservationIndices){

                event.dataTransfer.setData("indicesObjectOfDraggedPlayer", JSON.stringify(reservationIndices));

            }
        },


    });
</script>
