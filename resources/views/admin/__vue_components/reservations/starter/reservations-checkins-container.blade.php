@include("admin.__vue_components.reservations.starter.reservations-checkins-tabs")
@include("admin.__vue_components.reservations.starter.reservations-checkins-tabs-tables")
<script>

    Vue.component('reservations-container', {
        template: `<div>
                <reservation-tabs v-if="forReservationsPageData"
                          :reservations-parent="reservations"
                          style-for-show-more-tab="true">
			</reservation-tab-divs>\n\
                </reservation-tabs>
                <reservation-tabs v-else
                                  :reservations-parent="reservations"
                                  style-for-show-more-tab="false">

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
            dragDropOperationPerformed:function (dragDropIndicesDataObject) {

                this.$emit("drag-drop-operation",dragDropIndicesDataObject);
            },
        }

    });


</script>
