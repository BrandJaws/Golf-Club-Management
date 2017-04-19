@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-tables")
@include("admin.__vue_components.reservations.reservation-tab-divs")
@include("admin.__vue_components.reservations.reservation-popup")
@include("admin.__vue_components.popups.confirmation-popup")
<script>

Vue.component('reservations-container', {
    template: `<div>
                <confirmation-popup @close-popup="closeConfirmationPopup" @yes-selected="yesSelectedInConfirmation" v-if="showConfirmationPopup" :popupMessage="dataHeldForConfirmation.confirmationMessage"></confirmation-popup>
                <reservation-popup v-if="showPopup"
                        :reservation="reservationToEdit" 
                        :reservation-type="reservationType" 
                        @close-popup="closePopupTriggered"
                        @update-reservations="updateReservations"
                        :popup-message="popupMessage"
                        @delete-reservation="deleteReservation($event,true)"
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
                                @drag-drop-operation="dragDropOperationPerformed"
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
          showPopup: false,
          showConfirmationPopup: false,
          reservationToEdit: null,
          reservationType:null, //possible values new or edit
          popupMessage:"",
          dataHeldForConfirmation:{
                                    confirmationMessage:null,
                                    methodToFollow:null,
                                    reservationIdToBeDeleted:null,
                                    dragDropIndicesDataObject:null,


          },
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
             reservationTemp.clubId = this.reservations.club_id;
             reservationTemp.courseId = this.reservations.course_id;
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
                this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to cancel this reservation?";
                this.dataHeldForConfirmation.methodToFollow = "deleteReservation";
                this.dataHeldForConfirmation.reservationIdToBeDeleted = reservationId;
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
            this.showConfirmationPopup = true;
        },
        closeConfirmationPopup:function(){
            //            console.log('emit received');
            this.showConfirmationPopup = false;
            this.dataHeldForConfirmation.confirmationMessage = null;
            this.dataHeldForConfirmation.methodToFollow = null;
            this.dataHeldForConfirmation.reservationIdToBeDeleted = null;
            this.dataHeldForConfirmation.dragDropIndicesDataObject = null;
        },
        yesSelectedInConfirmation:function(){

            switch(this.dataHeldForConfirmation.methodToFollow){
                case "deleteReservation":
                    this[this.dataHeldForConfirmation.methodToFollow](this.dataHeldForConfirmation.reservationIdToBeDeleted, true);
                    break;
                case "dragDropOperationPerformed":
                    this[this.dataHeldForConfirmation.methodToFollow](this.dataHeldForConfirmation.dragDropIndicesDataObject, true);
                    break;

            }


        },
        dragDropOperationPerformed:function (dragDropIndicesDataObject,confirmed) {

            if(!confirmed){
                this.displayConfirmationPopup();
                this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to move this player to another time slot?";
                this.dataHeldForConfirmation.methodToFollow = "dragDropOperationPerformed";
                this.dataHeldForConfirmation.dragDropIndicesDataObject = dragDropIndicesDataObject;

                return;
            }

            {{--var request = $.ajax({--}}

                {{--url: "{{url('admin/reservations/move-player')}}",--}}
                {{--method: "POST",--}}
                {{--headers: {--}}
                    {{--'X-CSRF-TOKEN': '{{csrf_token()}}',--}}
                {{--},--}}
                {{--data:{--}}
                    {{--_method:"POST",--}}
                    {{--_token: "{{ csrf_token() }}",--}}
                    {{--reservationIdToMoveTo--}}

                {{--},--}}
                {{--success:function(msg){--}}
                    {{--console.log(msg);--}}
                    {{--//this.updateReservations(msg.response);--}}
                    {{--this.closePopupTriggered();--}}
                    {{--this.closeConfirmationPopup();--}}
                {{--}.bind(this),--}}

                {{--error: function(jqXHR, textStatus ) {--}}
                    {{--this.ajaxRequestInProcess = false;--}}
                    {{--console.log(jqXHR);--}}
                    {{--//Error code to follow--}}
                    {{--if(jqXHR.hasOwnProperty("responseText")){--}}
                        {{--this.popupMessage = JSON.parse(jqXHR.responseText).response;--}}
                    {{--}--}}

                {{--}.bind(this)--}}
            {{--});--}}

            //Perform actual move logic for server and if successful emit event and close popup;
            this.$emit("drag-drop-operation",dragDropIndicesDataObject);
            this.closeConfirmationPopup();
        },
    }
  
});


</script>
