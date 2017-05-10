@include("admin.__vue_components.reservations.reservation-player-tag")
@include("admin.__vue_components.autocomplete.autocomplete")
<script>

Vue.component('reservation-player-checkin-popup', {
    template: `
        <div @click="closeModal($event)">
               <div id="m-a-a" class="modal fade animate in closePopup" data-backdrop="false" style="display: block;">
                  <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                    <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close closePopup" aria-label="Close"><span aria-hidden="true" class="closePopup">×</span></button>
                            <h5 class="modal-title">Checkin Player</h5>
                            <div class="alert alert-danger" v-if="errorMessage != '' ">
                                @{{ errorMessage }}
                            </div>
                      </div>
                      <div class="modal-body text-center p-lg borderBottom">
                            <div class="row">
                                <div class="col-md-2">
                                  On Time:
                                </div>
                                <div class="col-md-10 text-left">
                                    <input type="radio" name="onTime" value="1" v-model="onTimeRadioSelection"> Yes <br/>
                                    <input type="radio" name="onTime" value="0" v-model="onTimeRadioSelection" > No
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
            "reservationPlayerId",
            "errorMessage"



    ],
    data: function () {

      return {
            onTimeRadioSelection:"1",
      }
    },
    methods:{
        emitClosePopup:function(){
            this.$emit('close-popup');
        },
        closeModal:function(event){

            if(event.target.className.search("closePopup") !== -1) {
                this.emitClosePopup();
            }
        },
        saveReservationClicked:function(){
            

            this.$emit('checkin-player',{reservationPlayerId:this.reservationPlayerId,
                                         onTime:this.onTimeRadioSelection
            });
        }
    }
  
});
</script>
