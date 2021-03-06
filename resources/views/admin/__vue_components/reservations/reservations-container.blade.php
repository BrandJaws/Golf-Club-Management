@include("admin.__vue_components.reservations.reservation-tabs")
@include("admin.__vue_components.reservations.reservation-tab-tables")
@include("admin.__vue_components.reservations.reservation-tab-divs")
@include("admin.__vue_components.reservations.reservation-popup")
@include("admin.__vue_components.reservations.reservation-player-checkin-popup")
@include("admin.__vue_components.popups.confirmation-popup")

<script>

    Vue.component('reservations-container', {
        template: `
        <div>
                <confirmation-popup @close-popup="closeConfirmationPopup"  v-if="showConfirmationPopup" :popupMessage="dataHeldForConfirmation.confirmationMessage" :errorMessage="confirmationPopupErrorMessage" :confirm-callback="dataHeldForConfirmation.confirmCallback"></confirmation-popup>
                <reservation-player-checkin-popup v-if="showReservationPlayerCheckinPopup"
                        :reservation-player-id="reservationPlayerIdToCheckin"
                        @close-popup="closeReservationPlayerCheckinPopupTriggered"
                        @checkin-player="checkinPlayer"
                        :error-message="reservationPlayerCheckinPopupErrorMessage"></reservation-player-checkin-popup>
                <reservation-popup v-if="showReservationPopup"
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
                                @drag-drop-player="dragDropPlayerPerformed"
                                @drag-drop-timeslot="dragDropTimeSlotPerformed"
                                @checkin-player="checkinPlayerEventTriggered"
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
                forReservationsPageData: this.forReservationsPage != null && this.forReservationsPage.toLowerCase() == 'true' ? true : false,
                showReservationPopup: false,
                showReservationPlayerCheckinPopup: false,
                showConfirmationPopup: false,
                reservationToEdit: null,
                reservationType: null, //possible values new or edit
                popupMessage: "",
                confirmationPopupErrorMessage: "",
                reservationPlayerCheckinPopupErrorMessage:"",
                reservationPlayerIdToCheckin:null,
                dataHeldForConfirmation: {
                    confirmationMessage: null,
                    confirmCallback:null

                },
            }
        },
        methods: {

            editReservationEventTriggered: function (reservation) {

                reservationTemp = JSON.parse(JSON.stringify(reservation));
                this.reservationToEdit = reservationTemp;
                this.reservationType = "edit";
                this.showReservationPopup = true;
            },
            newReservationEventTriggered: function (reservation) {

                reservationTemp = reservation;
                reservationTemp.clubId = this.reservations.club_id;
                reservationTemp.courseId = this.reservations.course_id;
                this.reservationToEdit = reservationTemp;
                this.reservationType = "new";
                this.showReservationPopup = true;
            },
            closePopupTriggered: function () {

                this.showReservationPopup = false;
                this.reservationToEdit = null;
                this.reservationType = null;
                this.popupMessage = "";
            },
            updateReservations: function (newOrUpdatedReservation) {
                //console.log(newOrUpdatedReservation);

                this.$emit("update-reservations", newOrUpdatedReservation);


            },
            restoreDefaultDates: function () {

                this.$emit("restore-default-dates");
            },
            maxNumCalled: function () {
                this.popupMessage = 'There are already 4 members in this slot!';
            },
            reserveSlot: function (reservation) {

                guestsAndPlayers = this.returnGuestsAndPlayerIdsListFromPlayersList(reservation.players);
                _players = guestsAndPlayers.players;
                _guests = guestsAndPlayers.guests;

                var request = $.ajax({

                    url: "{{url('admin/reservations')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    data: {
                        club_id: reservation.clubId,
                        course_id: reservation.courseId,
                        reserved_at: reservation.reserved_at,
                        time: reservation.timeSlot,
                        player: _players,
                        guests: _guests,
                        _token: "{{ csrf_token() }}",

                    },
                    success: function (msg) {

                        this.updateReservations(msg.response);
                        this.closePopupTriggered();
                    }.bind(this),


                    error: function (jqXHR, textStatus) {
                        this.ajaxRequestInProcess = false;
                        console.log(jqXHR);
                        //Error code to follow
                        if (jqXHR.hasOwnProperty("responseText")) {
                            this.popupMessage = JSON.parse(jqXHR.responseText).response;
                        }


                    }.bind(this)
                });
            },
            updateReservation: function (reservation) {

                guestsAndPlayers = this.returnGuestsAndPlayerIdsListFromPlayersList(reservation.players);
                _players = guestsAndPlayers.players;
                _guests = guestsAndPlayers.guests;

                var request = $.ajax({

                    url: "{{url('admin/reservations')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    data: {
                        _method: "PUT",
                        reservation_id: reservation.reservation_id,
                        player: _players,
                        guests: _guests,
                        _token: "{{ csrf_token() }}",

                    },
                    success: function (msg) {
                        this.updateReservations(msg.response);
                        this.closePopupTriggered();

                    }.bind(this),

                    error: function (jqXHR, textStatus) {
                        this.ajaxRequestInProcess = false;

                        //Error code to follow
                        if (jqXHR.hasOwnProperty("responseText")) {
                            this.popupMessage = JSON.parse(jqXHR.responseText).response;
                        }

                    }.bind(this)
                });
            },
            deleteReservation: function (reservationId, confirmed) {





                    this.dataHeldForConfirmation.confirmCallback = function(){
                        var request = $.ajax({

                            url: "{{url('admin/reservations')}}" + "/" + reservationId,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{csrf_token()}}',
                            },
                            data: {
                                _method: "DELETE",
                                _token: "{{ csrf_token() }}",

                            },
                            success: function (msg) {

                                this.updateReservations(msg.response);
                                this.closePopupTriggered();
                                this.closeConfirmationPopup();
                            }.bind(this),

                            error: function (jqXHR, textStatus) {
                                this.ajaxRequestInProcess = false;

                                //Error code to follow
                                if (jqXHR.hasOwnProperty("responseText")) {
                                    this.popupMessage = JSON.parse(jqXHR.responseText).response;
                                }

                            }.bind(this)
                        });
                    }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to cancel this reservation?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }





            },
            returnGuestsAndPlayerIdsListFromPlayersList: function (players) {
                playersAndGuests = {};
                playersAndGuests.players = [];
                playersAndGuests.guests = 0;
                for (x = 0; x < players.length; x++) {
                    if (players[x].member_id == '') {
                        playersAndGuests.guests++;
                    } else {
                        playersAndGuests.players[x] = players[x].member_id;
                    }

                }
                return playersAndGuests;
            },
            displayConfirmationPopup: function () {
                //            console.log('emit received');
                this.showConfirmationPopup = true;
            },
            closeConfirmationPopup: function () {
                //            console.log('emit received');
                this.showConfirmationPopup = false;
                this.confirmationPopupErrorMessage = "";
                this.dataHeldForConfirmation.confirmationMessage = null;
                this.dataHeldForConfirmation.confirmCallback = null;

            },

            displayReservationPlayerCheckinPopupTriggered: function (reservationPlayerId) {

                this.reservationPlayerIdToCheckin = reservationPlayerId;
                this.showReservationPlayerCheckinPopup = true;
            },
            closeReservationPlayerCheckinPopupTriggered: function () {

                this.showReservationPlayerCheckinPopup = false;
                this.reservationPlayerIdToCheckin = null;
                this.reservationPlayerCheckinPopupErrorMessage = "";
            },
            dragDropPlayerPerformed: function (dragDropIndicesDataObject, confirmed) {




                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: "{{url('admin/reservations/move-players')}}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "POST",
                            _token: "{{ csrf_token() }}",
                            reservationPlayerIdsToBeMoved: [this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDraggedFrom].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDraggedFrom].reservations[0].players[dragDropIndicesDataObject.playerIndexDragged].reservation_player_id],
                            reservationIdToMoveTo: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDroppedInto].reservations[0].reservation_id,
                            club_id: this.reservations.club_id,
                            course_id: this.reservations.course_id,
                            reservationTimeSlotToMoveTo: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDroppedInto].timeSlot,
                            reservationDateToMoveTo: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reserved_at,

                        },
                        success: function (msg) {
                            console.log(msg);
                            this.updateReservations(msg.response);
                            //Perform actual move logic for server and if successful emit event and close popup;
                            // this.$emit("drag-drop-operation",dragDropIndicesDataObject);
                            this.closeConfirmationPopup();
                        }.bind(this),

                        error: function (jqXHR, textStatus) {
                            this.ajaxRequestInProcess = false;
                            console.log(jqXHR);
                            //Error code to follow
                            if (jqXHR.hasOwnProperty("responseText")) {
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }

                        }.bind(this)
                    });
                }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to move this player to another time slot?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }









            },
            dragDropTimeSlotPerformed: function (dragDropIndicesDataObject, confirmed) {

                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: "{{url('admin/reservations/swap-timeslots')}}",
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data: {
                            _method: "POST",
                            _token: "{{ csrf_token() }}",
                            reservationIdFirst: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDraggedFrom].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDraggedFrom].reservations[0].reservation_id,
                            reservationIdSecond: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDroppedInto].reservations[0].reservation_id,
                            club_id: this.reservations.club_id,
                            course_id: this.reservations.course_id,
                            reservationTimeSlotSecond: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reservationsByTimeSlot[dragDropIndicesDataObject.timeIndexDroppedInto].timeSlot,
                            reservationDateSecond: this.reservations.reservationsByDate[dragDropIndicesDataObject.dateIndexDroppedInto].reserved_at,

                        },
                        success: function (msg) {
                            console.log(msg);
                            this.updateReservations(msg.response);
                            //Perform actual move logic for server and if successful emit event and close popup;
                            // this.$emit("drag-drop-operation",dragDropIndicesDataObject);
                            this.closeConfirmationPopup();
                        }.bind(this),

                        error: function (jqXHR, textStatus) {
                            this.ajaxRequestInProcess = false;
                            console.log(jqXHR);
                            //Error code to follow
                            if (jqXHR.hasOwnProperty("responseText")) {
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }

                        }.bind(this)
                    });
                }.bind(this);

                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Are you sure you want to swap these time slots?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }





            },
            checkinPlayerEventTriggered: function (reservationPlayerId) {

                this.displayReservationPlayerCheckinPopupTriggered(reservationPlayerId);


            },
            checkinPlayer: function (reservationPlayerInfo) {

                var request = $.ajax({

                    url: "{{url('admin/reservations/checkin-player')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    data: {
                        _method: "POST",
                        _token: "{{ csrf_token() }}",
                        reservationPlayerId: reservationPlayerInfo.reservationPlayerId,
                        onTime: reservationPlayerInfo.onTime,

                    },
                    success: function (msg) {
                        console.log(msg);
                        this.updateReservations(msg.response);
                        //Perform actual move logic for server and if successful emit event and close popup;
                        // this.$emit("drag-drop-operation",dragDropIndicesDataObject);
                        this.closeReservationPlayerCheckinPopupTriggered();
                    }.bind(this),

                    error: function (jqXHR, textStatus) {
                        this.ajaxRequestInProcess = false;
                        console.log(jqXHR);
                        //Error code to follow
                        if (jqXHR.hasOwnProperty("responseText")) {
                            this.reservationPlayerCheckinPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                        }

                    }.bind(this)
                });

            },
        }

    });


</script>
