@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-tables")
@include("admin.__vue_components.reservations.reservation-tab-divs")
@include("admin.__vue_components.reservations.reservation-popup")
<script>

Vue.component('reservations-container', {
    template: `<div>
                <reservation-popup v-if="showPopup"
                        :reservation="reservationToEdit" 
                        :reservation-type="reservationType" 
                        @close-popup="closePopupTriggered"
                        @update-reservations="updateReservations"></reservation-popup>
                <reservation-tabs v-if="forReservationsPageData"
                          :reservations-parent="reservationsParent"
                          style-for-show-more-tab="true"> 
                        <reservation-tab-heads
                                  :reservations-by-date="reservationsParent.reservationsByDate"
                                  show-more-tab="true">
                        </reservation-tab-heads> 
                        <reservation-tab-divs
                                  :reservations-by-date="reservationsParent.reservationsByDate"
                                  @edit-reservation="editReservationEventTriggered" 
                                  @new-reservation="newReservationEventTriggered">
			</reservation-tab-divs>\n\
                </reservation-tabs>
                <reservation-tabs v-else
                                  :reservations-parent="reservationsParent"
                                  style-for-show-more-tab="false">
                        <reservation-tab-heads
                            
                            :reservations-by-date="reservationsParent.reservationsByDate"
                            show-more-tab="false">
                        </reservation-tab-heads> 
                        
                        <reservation-tab-tables
                                :reservations-by-date="reservationsParent.reservationsByDate"
                                @edit-reservation="editReservationEventTriggered" 
                                @new-reservation="newReservationEventTriggered">
                        </reservation-tab-tables>
                
                </reservation-tabs>
                
            </div>
          
            `,
    props: [
            "forReservationsPage",
            "reservations"
            
            
    ],
    data: function () {
       
      return {
          forReservationsPageData:this.forReservationsPage != null && this.forReservationsPage.toLowerCase()== 'true' ? true : false,
          reservationsParent: this.reservations,
          showPopup: false,
          reservationToEdit: null,
          reservationType:null, //possible values new or edit
      }
    },
    methods: {
        editReservationEventTriggered: function (reservation) {

            reservationTemp = JSON.parse(JSON.stringify(reservation));
            this.reservationToEdit = reservationTemp;
            this.reservationType = "edit";
            this.showPopup = true;
        },
        newReservationEventTriggered:function(reservation){

             reservationTemp = reservation;
             reservationTemp.clubId = this.reservationsParent.club_id;
             reservationTemp.courseId = this.reservationsParent.course_id;
             this.reservationToEdit = reservationTemp;
             this.reservationType = "new";
             this.showPopup = true;
        },
        closePopupTriggered: function () {

            this.showPopup = false;
            this.reservationToEdit = null;
            this.reservationType = null;
        },
        updateReservations:function(newOrUpdatedReservation){
                    //console.log(newOrUpdatedReservation);
                    if(newOrUpdatedReservation[0].course_id == this.reservationsParent.course_id){

                         for(dateCount=0;dateCount<this.reservationsParent.reservationsByDate.length;dateCount++){
                             if(this.reservations.reservationsByDate[dateCount].reserved_at == newOrUpdatedReservation[0].reserved_at){

                                 for(timeSlotOriginalReservationsCount=0;timeSlotOriginalReservationsCount<this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot.length;timeSlotOriginalReservationsCount++ ){

                                     for(timeSlotsReceivedCount=0;timeSlotsReceivedCount<newOrUpdatedReservation[0].reservationsByTimeSlot.length;timeSlotsReceivedCount++){
                                        
                                         if(newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].timeSlot == this.reservations.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].timeSlot 
                                            &&
                                            (this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type == "App\\Http\\Models\\RoutineReservation" || 
                                             this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type == ""
                                             )
                                          ){
                                            
                                             
                                              this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_id = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].reservation_id;
                                              this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].reservation_type = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].reservation_type;
                                              this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].players = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].players;
                                              this.reservationsParent.reservationsByDate[dateCount].reservationsByTimeSlot[timeSlotOriginalReservationsCount].reservations[0].status = newOrUpdatedReservation[0].reservationsByTimeSlot[timeSlotsReceivedCount].reservations[0].status;

                                              
                                         }
                                     }
                                 }
                                 break;
                             }
                         }
                    }
                    
                    
                    
        }
    }
  
});


</script>
