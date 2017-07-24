<script>

    Vue.component('events-reserve-member-popup', {
        template: `
        <div @click="closeModal($event)">
               <div @click="closeModal($event)">
                   <div class="modal fade animate in closePopup" data-backdrop="false" style="display: block;">
                      <div class="modal-dialog  modal-lg fade-down" id="animate" ui-class="fade-down">
                       <div class="modal-content">
                            <div class="modal-header text-left">
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close closePopup">
                                    <span aria-hidden="true" class="closePopup">×</span>
                                </button>
                                <h4 class="modal-title">Select Member to Add</h4>
                                <div class="alert alert-danger" v-if="popupMessage != '' ">
                                    @{{ popupMessage }}
                                </div>
                </div>
                <div id="membersPageAutoCom" class="modal-body">
                    <auto-complete-box url="{{url('admin/member/search-list')}}" property-for-id="member_id" property-for-name="member_name"
                                                                   filtered-from-source="true" include-id-in-list="true"
                                                                   v-model="selectedPlayerId" initial-text-value="" search-query-key="search" field-name="memberId"></auto-complete-box>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-def" @click="addMemberClicked">
                                <i class="fa fa-floppy-o"></i> Add Member
                                </button>
                                <button type="button" class="btn btn-outline b-primary text-primary closePopup">
                                    <i class="fa fa-ban closePopup" ></i> Cancel
                                </button>
                            </div>
                       </div>
                      </div>
                    </div>
               </div>
                <div class="modal-backdrop fade in closePopup"></div>
        </div>
            `,
    props: [

            'popupMessage'
    ],
    data:function(){
            return{
                selectedPlayerId:-1,
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
        addMemberClicked:function(){
            this.$emit('add-member', this.selectedPlayerId);
        },
    }

});
</script>
