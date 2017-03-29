<script>

    Vue.component('reservation-cancel-popup', {
        template: `
        <div @click="closeModal($event)">
               <div class="modal fade animate in closePopup" data-backdrop="false" style="display: block;">
                  <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                    <div class="modal-content">
                      <div class="modal-header">
                          <button @click="closePopupCancel" type="button" class="close closePopup" aria-label="Close"><span aria-hidden="true" class="closePopup">×</span></button>
                            <h5 class="modal-title">Cancel Reservation</h5>
                      </div>
                      <div class="modal-body text-center p-lg">
                        <p class="text-center">Do you really wish to cancel this reservation?</p>
                      </div>
                      <div class="modal-footer text-center">
                        <button type="button" class="btn btn-fw primary closePopup" @click="closePopupCancel">Yes</button>
                        &nbsp;&nbsp;
                        <button @click="closePopupCancel" type="button" class="closePopup btn btn-outline b-primary text-primary" >No</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-backdrop fade in closePopup"></div>
            `,
    props: [
            "reservation",
            "reservationType",
            'popupMessage'
    ],
    methods:{
        emitClosePopup:function(){
            this.$emit('close-popup');
        },
        closeModal:function(event){
           console.log(event.target);
            if(event.target.className.search("closePopup") !== -1) {
                this.emitClosePopup();
            }
        },
        deleteReservation:function(){

            this.$emit('close-cancel');

        },
        closePopupCancel:function(){
            this.$emit('close-cancel-popup');
        },
    }

});
</script>
