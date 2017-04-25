@include("admin.__vue_components.reservations.starter.reservations-checkins-tabs")
@include("admin.__vue_components.reservations.starter.reservations-checkins-tabs-tables")
<script>

    Vue.component('reservations-container', {
        template: `
                        <reservation-tab-tables
                                :reservations-by-date="reservations.reservationsByDate"
                                @start-game-clicked="startGameClicked">

                        </reservation-tab-tables>


            `,
        props: [

            "reservations"


        ],
        data: function () {

            return {

            }
        },
        methods: {

            startGameClicked:function (_reservation_id) {

                var request = $.ajax({

                    url: "{{url('admin/reservations/mark-as-started')}}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    data:{

                        reservation_id:_reservation_id,
                        _token: "{{ csrf_token() }}",

                    },
                    success:function(msg){
                        this.updateReservations(msg.response);
                        //this.closePopupTriggered();

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
            updateReservations:function(newOrUpdatedReservation){
                //console.log(newOrUpdatedReservation);

                this.$emit("update-reservations",newOrUpdatedReservation);


            },
        }

    });


</script>
