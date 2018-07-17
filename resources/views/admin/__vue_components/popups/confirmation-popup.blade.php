<template xmlns="http://www.w3.org/1999/html" id="closeModalTemplate">
    <div v-on:click="closeModal($event)" >
        <div class="modal fade animate in closePopup" data-backdrop="false" style="display: block;">
            <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closePopup" aria-label="Close"><span aria-hidden="true" class="closePopup">×</span></button>
                        <h5 class="modal-title">@{{ title }}</h5>
                    </div>
                    <div class="alert alert-danger" v-if="errorMessage != '' ">
                        @{{ errorMessage }}
                    </div>
                    <div class="modal-body text-center p-lg">
                        <p class="text-center">@{{ popupMessage }}</p>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" class="btn primary" v-on:click="yesSelected">Yes</button>
                        &nbsp;&nbsp;
                        <button  type="button" class="closePopup btn btn-outline b-primary text-primary" >No</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade in closePopup"></div>
    </div>
</template>
<script>

    Vue.component('confirmation-popup', {
        template: "#closeModalTemplate",
    props: [

            'popupMessage',
            'errorMessage',
            'confirmCallback',
            'title'
    ],
    methods:{
        emitClosePopup:function(){
            this.$emit('close-popup');
        },
        closeModal:function(event){

            if(event.target.className.search("closePopup") !== -1) {
                this.emitClosePopup();
            }
        },
        yesSelected:function(){
            //this.$emit('yes-selected');
            this.confirmCallback();

        },
    }

});
</script>
