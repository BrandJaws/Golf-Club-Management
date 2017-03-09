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
                          :reservations-parent="reservations"
                          style-for-show-more-tab="true"> 
                        <reservation-tab-heads
                                  :reservations-by-date="reservations.reservationsByDate"
                                  show-more-tab="true"
                                  @restore-default-dates="restoreDefaultDates">
                        </reservation-tab-heads> 
                        <reservation-tab-divs
                                  :reservations-by-date="reservations.reservationsByDate"
                                  @edit-reservation="editReservationEventTriggered" 
                                  @new-reservation="newReservationEventTriggered">
			</reservation-tab-divs>\n\
                </reservation-tabs>
                <reservation-tabs v-else
                                  :reservations-parent="reservations"
                                  style-for-show-more-tab="false">
                        <reservation-tab-heads
                            
                            :reservations-by-date="reservations.reservationsByDate"
                            show-more-tab="false">
                        </reservation-tab-heads> 
                        
                        <reservation-tab-tables
                                :reservations-by-date="reservations.reservationsByDate"
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
                      
                    this.$emit("update-reservations",newOrUpdatedReservation);
                    
                    
        },
        restoreDefaultDates:function(){
        
            this.$emit("restore-default-dates");
        },
    }
  
});


</script>
