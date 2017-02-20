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
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="closePopup">×</span></button>
                            <h5 class="modal-title">Reservation</h5>
                            <small>Uit arcu tempor, dignissim erat in</small>
                      </div>
                      <div class="modal-body text-center p-lg borderBottom">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-auto-complete text-left">
                                    <label>Add Members</label>
                                </div>
                                <div class="tags-container">
                                    <ul class="members-add">
                                        <reservation-player-tag v-for="reservationPlayer in reservation.players" :reservationPlayer="reservationPlayer" @deletePlayer="deletePlayer"></reservation-player-tag>
                                    </ul>
                                    <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="id" property-for-name="name"
                                                    filtered-from-source="true" include-id-in-list="true"
                                                    v-model="selectedId" initial-text-value="" search-query-key="search" field-name="memberId"> </auto-complete-box>
                                </div><!-- tags container ends here -->
                            </div>
                            <div class="col-md-12 text-right">
                                <br />
                                <button type="button" class="btn btn-outline b-primary text-primary"><i class="fa fa-ban"></i> &nbsp; Cancel Booking</button>
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
                                            <input name="search-guest" class="form-control" type="nubmer"><br>
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
                        <button type="button" class="btn btn-fw primary" data-dismiss="modal"><i class="fa fa-floppy-o"></i> &nbsp;Save</button>
                        &nbsp;&nbsp;
                        <button type="button" class="closePopup btn btn-outline b-primary text-primary" data-dismiss="modal" ><i class="fa fa-times-circle"></i> &nbsp;Close</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div>
                  
                </div>
                <div class="modal-backdrop fade in closePopup"></div>
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
        },
        closeModal:function(event){
            console.log(event.target);
            console.log(event.target.className.search("closePopup"));
            if(event.target.className.search("closePopup") !== -1) {
                this.closePopup();
            }
        }
    }
  
});
</script>
