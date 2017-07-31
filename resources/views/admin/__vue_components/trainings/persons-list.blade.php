@include("admin.__vue_components.popups.confirmation-popup")
@include("admin.__vue_components.trainings.reserve-player-popup")
<script>

    Vue.component('person-list', {

        template: `
        <div>
         <confirmation-popup @close-popup="closeConfirmationPopup"  v-if="showConfirmationPopup" :popupMessage="dataHeldForConfirmation.confirmationMessage" :errorMessage="confirmationPopupErrorMessage" :confirm-callback="dataHeldForConfirmation.confirmCallback"></confirmation-popup>

         <reserve-player-popup @close-popup="closeReservePlayerPopup" @add-member="addMemberRequested" v-if="showReservePlayerPopup" :popupMessage="reservePlayerPopupMessage"></reserve-player-popup>
         <div class="row bg-white">
                    <div class="col-md-6">
                        <div class="main-page-heading">
                            <h3>
                                <span>Persons Taking this Training</span>
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-6 text-right p-10">
                        <button class="btn btn-def" type="button" @click="addPlayerClicked">Add Member</button>

                    </div>
                    <div class="col-md-12 padding-none">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Person Name</th>
                                    <th>Person Email</th>
                                    <th>Person Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(person,personIndex) in personsListData">
                                    <td>
                                        @{{ person.name }}
                                    </td>
                                    <td>
                                        @{{ person.email }}
                                    </td>
                                    <td>
                                        @{{ person.phone }}
                                    </td>
                                    <td>
                                        <a href="#." class="del-icon" @click="cancelReservationOfPlayer(person,personIndex,false)"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                </div>
                </div>
                </div>
                `,
        props: [
            "trainingId",
            "personsList",
            "urlForCrud",
        ],
        data: function(){
            return {
                personsListData:this.personsList,
                showCancelPopup:false,
                showReservePlayerPopup:false,
                showConfirmationPopup:false,
                reservePlayerPopupMessage:"",
                confirmationPopupErrorMessage: "",
                dataHeldForConfirmation: {
                    confirmationMessage: null,
                    confirmCallback:null

                },



            }
        },
        methods:{
            //method will be called twice first when the delete button is clicked and second when confirmed by the user
            cancelReservationOfPlayer:function(player,playerIndex,confirmed){
                this.dataHeldForConfirmation.confirmCallback = function(){
                    var request = $.ajax({

                        url: this.urlForCrud,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                        },
                        data:{

                            _method:"DELETE",
                            _token: "{{ csrf_token() }}",
                            reservation_player_id:player.id,

                        },
                        success:function(msg){

                            if(msg=="success"){
                                this.personsListData.splice(playerIndex,1);
                                this.closeConfirmationPopup();
                            }else{

                            }

                        }.bind(this),

                        error: function(jqXHR, textStatus ) {
                            this.ajaxRequestInProcess = false;
                            if(jqXHR.hasOwnProperty("responseText")){
                                this.confirmationPopupErrorMessage = JSON.parse(jqXHR.responseText).response;
                            }



                        }.bind(this)
                    });
                }.bind(this);





                if(!confirmed){
                    this.dataHeldForConfirmation.confirmationMessage = "Do you really wish to cancel this reservation?";
                    this.displayConfirmationPopup();

                }else{
                    this.dataHeldForConfirmation.confirmCallback();
                    this.dataHeldForConfirmation.confirmCallback = null;
                }
            },
            addMemberRequested:function(memberId){

                var request = $.ajax({

                    url: this.urlForCrud,
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}',
                    },
                    data:{

                        _token: "{{ csrf_token() }}",
                        training_id:this.trainingId,
                        member_id:memberId,


                    },
                    success:function(msg){
                        console.log(msg);
                        this.personsListData.push(msg.response);
                        this.closeReservePlayerPopup();

                    }.bind(this),

                    error: function(jqXHR, textStatus ) {

                        if(jqXHR.hasOwnProperty("responseText")){
                            this.reservePlayerPopupMessage = JSON.parse(jqXHR.responseText).response;
                        }


                    }.bind(this)
                });
            },

            displayConfirmationPopup:function(){

                this.showConfirmationPopup = true;
            },
            closeConfirmationPopup:function(){

                this.showConfirmationPopup = false;
                this.confirmationPopupErrorMessage = "";
                this.dataHeldForConfirmation.confirmationMessage = null;
                this.dataHeldForConfirmation.confirmCallback = null;
            },
            addPlayerClicked:function(){

                this.showReservePlayerPopup = true;
            },
            closeReservePlayerPopup:function(){

                this.showReservePlayerPopup = false;
            },

        }

    });

</script>