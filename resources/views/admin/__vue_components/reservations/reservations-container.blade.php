@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-tables")
@include("admin.__vue_components.reservations.reservation-tab-divs")
@include("admin.__vue_components.reservations.reservation-popup")
@include("admin.__vue_components.popups.confirmation-popup")
<script>

Vue.component('reservations-container', {
    template: `<div>
                <confirmation-popup @close-popup="closeConfirmationPopup" @yes-selected="yesSelectedInConfirmation" v-if="showCancelPopup" popupMessage="Do you really wish to cancel this reservation?"></confirmation-popup>
                <reservation-popup v-if="showPopup"
                        :reservation="reservationToEdit" 
                        :reservation-type="reservationType" 
                        @close-popup="closePopupTriggered"
                        @update-reservations="updateReservations"
                        :popup-message="popupMessage"
                        @delete-reservation="deleteReservation"
                        @update-reservation="updateReservation"
                        @reserve-slot="reserveSlot" @max-num-reached="maxNumCalled"></reservation-popup>
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
                                @new-reservation="newReservationEventTriggered"
                                @delete-reservation="deleteReservation($event,false)"
                        >
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
          showCancelPopup: false,
          reservationToEdit: null,
          reservationType:null, //possible values new or edit
          popupMessage:"",
          tempReservationIdToBeDeleted:null,
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
            this.popupMessage = "";
        },
        updateReservations:function(newOrUpdatedReservation){
                    //console.log(newOrUpdatedReservation);
                      
                    this.$emit("update-reservations",newOrUpdatedReservation);
                    
                    
        },
        restoreDefaultDates:function(){
        
            this.$emit("restore-default-dates");
        },
        maxNumCalled:function(){
            this.popupMessage = 'There are already 4 members in this slot!';
        },
        reserveSlot:function(reservation){

            guestsAndPlayers = this.returnGuestsAndPlayerIdsListFromPlayersList(reservation.players);
            _players = guestsAndPlayers.players;
            _guests = guestsAndPlayers.guests;

            var request = $.ajax({

                url: "{{url('admin/reservations')}}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                data:{
                    club_id:reservation.clubId,
                    course_id:reservation.courseId,
                    reserved_at:reservation.reserved_at,
                    time:reservation.timeSlot,
                    player:_players,
                    guests:_guests,
                    _token: "{{ csrf_token() }}",

                },
                success:function(msg){

                    this.updateReservations(msg.response);
                    this.closePopupTriggered();
                }.bind(this),


                error: function(jqXHR, textStatus ) {
                    this.ajaxRequestInProcess = false;

                    //Error code to follow
                    if(jqXHR.hasOwnProperty("responseText")){
                        this.popupMessage = JSON.parse(jqXHR.responseText).response;
                    }


                }.bind(this)
            });
        },
        updateReservation:function(reservation){

            guestsAndPlayers = this.returnGuestsAndPlayerIdsListFromPlayersList(reservation.players);
            _players = guestsAndPlayers.players;
            _guests = guestsAndPlayers.guests;

            var request = $.ajax({

                url: "{{url('admin/reservations')}}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                data:{
                    _method:"PUT",
                    reservation_id:reservation.reservation_id,
                    player:_players,
                    guests:_guests,
                    _token: "{{ csrf_token() }}",

                },
                success:function(msg){
                    this.updateReservations(msg.response);
                    this.closePopupTriggered();

                }.bind(this),

                error: function(jqXHR, textStatus ) {
                    this.ajaxRequestInProcess = false;

                    //Error code to follow
                    if(jqXHR.hasOwnProperty("responseText")){
                        this.popupMessage = JSON.parse(jqXHR.responseText).response;
                    }

                }.bind(this)
            });
        },
        deleteReservation:function(reservationId, confirmed){

            if(!confirmed){
                this.displayConfirmationPopup();
                this.tempReservationIdToBeDeleted = reservationId;
                return;
            }

            var request = $.ajax({

                url: "{{url('admin/reservations')}}"+"/"+reservationId,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },
                data:{
                    _method:"DELETE",
                    _token: "{{ csrf_token() }}",

                },
                success:function(msg){

                    this.updateReservations(msg.response);
                    this.closePopupTriggered();
                    this.closeConfirmationPopup();
                }.bind(this),

                error: function(jqXHR, textStatus ) {
                    this.ajaxRequestInProcess = false;

                    //Error code to follow
                    if(jqXHR.hasOwnProperty("responseText")){
                        this.popupMessage = JSON.parse(jqXHR.responseText).response;
                    }

                }.bind(this)
            });
        },
        returnGuestsAndPlayerIdsListFromPlayersList:function(players){
            playersAndGuests = {};
            playersAndGuests.players = [];
            playersAndGuests.guests = 0;
            for(x=0;x<players.length; x++){
                if(players[x].member_id == ''){
                    playersAndGuests.guests++;
                }else{
                    playersAndGuests.players[x] = players[x].member_id;
                }

            }
            return playersAndGuests;
        },
        displayConfirmationPopup:function(){
            //            console.log('emit received');
            this.showCancelPopup = true;
        },
        closeConfirmationPopup:function(){
            //            console.log('emit received');
            this.showCancelPopup = false;
            this.tempReservationIdToBeDeleted = null;
        },
        yesSelectedInConfirmation:function(){

            this.deleteReservation(this.tempReservationIdToBeDeleted, true);

        },
    }
  
});


</script>
