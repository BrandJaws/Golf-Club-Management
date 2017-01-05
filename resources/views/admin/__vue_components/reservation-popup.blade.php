@include("admin.__vue_components.reservation-player-tag")
<script>

Vue.component('reservation-popup', {
    template: `
        <div>      		
               <div id="m-a-a" class="modal fade animate in" data-backdrop="true" style="display: block;">
                  <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                    <div class="modal-content">
                      <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closePopup"><span aria-hidden="true">×</span></button>
                        <h5 class="modal-title">Reservation</h5>
                      </div>
                      <div class="modal-body text-center p-lg">
                        <div class="row">
                                <div class="col-md-12">
                                <div class="tags-container">
                                    <ul class="members-add">
                                        <reservation-player-tag v-for="reservationPlayer in reservation.players" :reservationPlayer="reservationPlayer" @deletePlayer="deletePlayer"></reservation-player-tag>
                                    </ul>
                                </div><!-- tags container ends here -->
                                <div class="autocomplete-search">
                                        <div class="row">
                                        <div class="col-md-6">
                                                <div class="input-auto-complete text-left">
                                            <label>Add Members</label>
                                            <div class="easy-autocomplete eac-plate-dark" style="width: 100px;"><input id="plate" name="search-player" class="form-control" autocomplete="off" type="text"><div class="easy-autocomplete-container" id="eac-container-plate"><ul></ul></div></div></div>
                                        </div>
                                        <div class="col-md-6">
                                                <div class="guest-search text-left">
                                            <label>Add Number Of Guests</label>
                                            <input name="search-guest" class="form-control" type="nubmer"><br>
                                            <i>How many guests do you have!</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer text-center">
                        <button type="button" class="btn btn-outline b-primary text-primary" data-dismiss="modal">Cancel Booking</button> 
                        &nbsp;&nbsp;
                        <button type="button" class="btn btn-fw primary" data-dismiss="modal">Save</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div>
                  
                </div>
                <div class="modal-backdrop fade in"></div>   
        </div>
                     
          
            `,
    props: [
            "reservation"
            
            
            
    ],
    data: function () {
        
      return {
          reservationData:this.reservation
      }
    },
    methods:{
        closePopup:function(){
            this.$emit('close-popup');
        },
        deletePlayer:function(reservationPlayer){
            this.$emit('delete-player',reservationPlayer);
        }
    }
  
});
</script>
