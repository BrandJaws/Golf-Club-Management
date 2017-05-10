{{--<div id="cancelTest">--}}
    {{--<reservation-cancel-popup v-if="showCancelPopup"></reservation-cancel-popup>--}}
{{--</div>--}}
@include("admin.__vue_components.reservations.reservation-player-tag")
<script>
Vue.component('reservation-tab-tables', {
    template: `
              		
                <div class="tab-content p-a m-b-md">
                    <div v-for="(reservationByDate,reservationByDateIndex) in reservationsByDateData" :id="'tab'+(reservationByDateIndex+1)" :class="['tab-pane', 'animated', 'fadeIn', 'text-muted', reservationByDateIndex == 0 ? 'active' : '']"  >
                      <div class="tab-pane-content">

                        <div class="table-responsive">
                            <table class="table table-hover b-t">
                                <tbody>
                                  <tr v-for="(timeSlot,timeSlotIndex) in reservationByDate.reservationsByTimeSlot" :key="timeSlotIndex" v-if="timeSlot.isVisibleUnderFilter" @dragover="dragOver($event,timeSlot)" @drop.prevent="dragDropped($event,timeSlot,reservationByDateIndex, timeSlotIndex)" draggable="true" @dragstart="dragTimeSlotStarted($event,{objectType:'timeSlot',dateIndexDraggedFrom:reservationByDateIndex,timeIndexDraggedFrom:timeSlotIndex}) " :class="(timeSlot.reservations[0].game_status == 'STARTED') ? 'success' : ''"  >
                                    <td >@{{timeSlot.timeSlot}}</td>
                                    <td width="80%">
                                      <ul class="members-add" >
                                          <reservation-player-tag  v-for="(reservationPlayer,reservationPlayerIndex) in timeSlot.reservations[0].players " :reservationPlayer="reservationPlayer"  draggable="true" @dragstart="dragPlayerStarted($event,{objectType:'player',dateIndexDraggedFrom:reservationByDateIndex,timeIndexDraggedFrom:timeSlotIndex,playerIndexDragged:reservationPlayerIndex})" :coming-on-time="reservationPlayer.comingOnTime" :club-entry="reservationPlayer.club_entry" :game-entry="reservationPlayer.game_entry" allow-checkin="true" @checkin-player="checkinPlayer"></reservation-player-tag>
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

        },
        dragOver:function(event, timeSlot){
            //indicesObjectOfDraggedObject = JSON.parse(event.dataTransfer.getData("indicesObjectOfDraggedPlayer"));
//            console.log(event);
//            if(timeSlot.reservations[0].players.length >= 4 && indicesObjectOfDraggedObject.objectType === "player"){
//
//                return;
//            }
            event.preventDefault();
        },
        dragDropped:function (event, timeSlot,dateIndexDroppedInto,timeIndexDroppedInto) {
            indicesObjectOfDraggedObject = JSON.parse(event.dataTransfer.getData("indicesObjectOfDraggedPlayer"));
            if(timeSlot.reservations[0].players.length >= 4 && indicesObjectOfDraggedObject.objectType === "player"){
                //console.log("Time Slot Fully Booked");
                return false;
            }
            if( timeIndexDroppedInto == indicesObjectOfDraggedObject.timeIndexDraggedFrom){

                return;
            }

            dragDropIndicesData = {};
            dragDropIndicesData.dateIndexDraggedFrom = indicesObjectOfDraggedObject.dateIndexDraggedFrom;
            dragDropIndicesData.timeIndexDraggedFrom = indicesObjectOfDraggedObject.timeIndexDraggedFrom;
            dragDropIndicesData.playerIndexDragged = indicesObjectOfDraggedObject.playerIndexDragged;
            dragDropIndicesData.dateIndexDroppedInto = dateIndexDroppedInto;
            dragDropIndicesData.timeIndexDroppedInto = timeIndexDroppedInto;

            if(indicesObjectOfDraggedObject.objectType === "player"){
                this.$emit("drag-drop-player",dragDropIndicesData);
            }else if(indicesObjectOfDraggedObject.objectType === "timeSlot"){
                this.$emit("drag-drop-timeslot",dragDropIndicesData);
            }

        },
        dragPlayerStarted:function(event,reservationIndices){

            event.stopPropagation();
            event.dataTransfer.setData("indicesObjectOfDraggedPlayer", JSON.stringify(reservationIndices));



        },
        dragTimeSlotStarted:function(event,reservationIndices){


            event.dataTransfer.setData("indicesObjectOfDraggedPlayer", JSON.stringify(reservationIndices));



        },
        checkinPlayer:function(reservationPlayerId){
            this.$emit('checkin-player',reservationPlayerId);
        }
    },

  
});
</script>
