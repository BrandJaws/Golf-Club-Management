@include("admin.__vue_components.reservations.reservation-player-tag")
@include("admin.__vue_components.autocomplete.autocomplete")
<script>

Vue.component('reservation-popup', {
    template: `
        <div @click="closeModal($event)">
               <div id="m-a-a" class="modal fade animate in closePopup" data-backdrop="false" style="display: block;">
                  <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                    <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close closePopup" aria-label="Close"><span aria-hidden="true" class="closePopup">×</span></button>
                            <h5 class="modal-title">Reservation</h5>
                            <div class="alert alert-danger" v-if="popupMessage != '' ">
                                @{{ popupMessage }}
                            </div>
                      </div>
                      <div class="modal-body text-center p-lg borderBottom">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-auto-complete text-left">
                                    <label>Add Members</label>
                                </div>
                                <div class="tags-container">
                                    <ul class="members-add">
                                        <reservation-player-tag v-for="(reservationPlayer,playerIndex) in reservationData.players" :reservationPlayer="reservationPlayer" deletable="true" @deletePlayer="deletePlayer(playerIndex)" ></reservation-player-tag>
                                    </ul>
                                    <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="member_id" property-for-name="member_name"
                                                    filtered-from-source="true" include-id-in-list="true"
                                                    initial-text-value="" search-query-key="search" field-name="memberId" enable-explicit-selection="true" @explicit-selection="explicitSelectionMade"> </auto-complete-box>
                                </div><!-- tags container ends here -->
                            </div>
                            <div class="col-md-12 text-right">
                                <br />
                                <button type="button" class="btn btn-outline b-primary text-primary" @click="deleteReservation"><i class="fa fa-ban"></i> &nbsp; Cancel Booking</button>
                            </div>
                        </div>
                      </div>
                      <div class="modal-body text-center p-lg">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="autocomplete-search">
                                        <div class="row">
                                        <div class="col-md-6">
                                                <div class="guest-search text-left">
                                            <label>Add Number Of Guests</label>
                                            <input name="search-guest" class="form-control" type="nubmer" v-model="guestsCounter"><br>
                                            <button type="button" class="btn btn-outline b-primary text-primary" @click="addGuestsClicked" ><i class="fa fa-plus"></i> &nbsp;Add Guests</button>
                                            <i>How many guests do you have!</i></div>
                                        </div>
                                        <div class="col-md-6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer text-center">
                        <button type="button" class="btn btn-fw primary"  @click="saveReservationClicked"><i class="fa fa-floppy-o" ></i> &nbsp;Save</button>
                        &nbsp;&nbsp;
                        <button type="button" class="closePopup btn btn-outline b-primary text-primary" ><i class="fa fa-times-circle closePopup"></i> &nbsp;Close</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div>
                  
                </div>
                <div class="modal-backdrop fade in closePopup"></div>
        </div>

          
            `,
    props: [
            "reservation",
            "reservationType",
            'popupMessage'
            
            
            
            
    ],
    data: function () {

      return {
          reservationData:this.reservation,
          guestsCounter:0,
      }
    },
    methods:{
        emitClosePopup:function(){
            this.$emit('close-popup');
        },
        deletePlayer:function(playerIndex){
            this.reservationData.players.splice(playerIndex,1);
        },
        closeModal:function(event){

            if(event.target.className.search("closePopup") !== -1) {
                this.emitClosePopup();
            }
        },
        explicitSelectionMade:function(dataItemSelected){
            //Dont create tag if selected 4 or player already in the list
            playersInSelectionList = this.reservationData.players.length;
            if(playersInSelectionList >= 4){
                this.$emit('max-num-reached');
                return;
            }
            for(x=0; x<playersInSelectionList; x++){
                if(this.reservationData.players[x].member_id == dataItemSelected.member_id){
                    return;
                }
            }
            this.reservationData.players.push(dataItemSelected);

        },
        addGuestsClicked:function(){
            //Dont create tag if selected 4 or player already in the list
            playersInSelectionList = this.reservationData.players.length;
            
            if((playersInSelectionList + parseInt(this.guestsCounter)) > 4){
                return;
            }
            for(x=0; x<this.guestsCounter; x++){
                this.reservationData.players.push({member_id:'',member_name:'Guest'});
            }
            this.guestsCounter = 0;
        },
        saveReservationClicked:function(){
            
            if(this.reservationType == "new"){
                this.reserveSlot();
            }else if(this.reservationType == "edit"){
                this.updateReservation();
            }
        },
        reserveSlot:function(){
            
           this.$emit('reserve-slot',this.reservationData);
        },
        updateReservation:function(){
            
            this.$emit('update-reservation',this.reservationData);
        },
        deleteReservation:function(){

            this.$emit('delete-reservation',this.reservationData.reservation_id);

        }
    }
  
});
</script>
