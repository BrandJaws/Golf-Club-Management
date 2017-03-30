@include("admin.__vue_components.popups.confirmation-popup")
@include("admin.__vue_components.trainings.reserve-player-popup")
<script>

    Vue.component('person-list', {

        template: `
        <div>
         <confirmation-popup @close-popup="closeConfirmationPopup" @yes-selected="yesSelectedInConfirmation" v-if="showCancelPopup" popupMessage="Do you really wish to cancel this reservation?"></confirmation-popup>
         <reserve-player-popup @close-popup="closeReservePlayerPopup" @add-member="addMemberRequested" v-if="showReservePlayerPopup" popupMessage="Do you really wish to cancel this reservation?"></reserve-player-popup>
         <div class="row bg-white">
                    <div class="col-md-6">
                        <div class="main-page-heading">
                            <h3>
                                <span>Person Taking this Lesson</span>
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
                tempPlayerToBeCancelled:null,
                showReservePlayerPopup:false,


            }
        },
        methods:{
            //method will be called twice first when the delete button is clicked and second when confirmed by the user
            cancelReservationOfPlayer:function(player,playerIndex,confirmed){
            //if not confirmed open confirmation popup
                 if(!confirmed){
                    this.displayConfirmationPopup();
                    this.tempPlayerToBeCancelled = {};
                    this.tempPlayerToBeCancelled.playerIndex = playerIndex;
                    this.tempPlayerToBeCancelled.player = player;
                    return;
                 }

                 //if confirmed send delete request

                            var request = $.ajax({

                                        url: this.urlForCrud,
                                        method: "POST",
                                        headers: {
                                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                                        },
                                        data:{

                                            _method:"DELETE",
                                            _token: "{{ csrf_token() }}",
                                            reservation_player_id:this.tempPlayerToBeCancelled.player.id,

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

                                                    //Error code to follow


                                               }.bind(this)
                                    });
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
                            //this.popupMessage = JSON.parse(jqXHR.responseText).response;
                        }


                    }.bind(this)
                });
            },

            displayConfirmationPopup:function(){
    //            console.log('emit received');
                this.showCancelPopup = true;
            },
            closeConfirmationPopup:function(){
    //            console.log('emit received');
                this.showCancelPopup = false;
                this.tempPlayerToBeCancelled = null;
            },
            yesSelectedInConfirmation:function(){
                this.cancelReservationOfPlayer(this.tempPlayerToBeCancelled.player,this.tempPlayerToBeCancelled.playerIndex,true);
            },
            addPlayerClicked:function(){
                //            console.log('emit received');
                this.showReservePlayerPopup = true;
            },
            closeReservePlayerPopup:function(){
                //            console.log('emit received');
                this.showReservePlayerPopup = false;
            },

        }

    });

</script>