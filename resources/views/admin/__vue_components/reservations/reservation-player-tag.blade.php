
<script>
var _onDeleteTagStyle = {
                           background: '#D30000'
                       };
Vue.component('reservation-player-tag', {
    template: `

                  <li :style ="[applyDeleteTagBg ? onDeleteTagStyle : null]" :draggable="draggable" @dragstart="dragStarted($event,reservationIndices)" >@{{reservationPlayer.member_name}}<a href="#." v-if="deletableData" @click.prevent="deletePlayerClicked"><i class="fa fa-times"></i></a></li>

            `,
    props: [
            "reservationPlayer",
            "deletable",
            "reservationIndices",
            "draggable"
            
            
    ],
    data: function () {
      
      return {
         
          applyDeleteTagBg:false,
          onDeleteTagStyle:_onDeleteTagStyle,
          deletableData:this.deletable == "true"? true : false,
          
      }
    },
    methods: {
        deletePlayerClicked: function(reservationPlayerId){
            
//            if(this.applyDeleteTagBg){
//                this.applyDeleteTagBg = false;
//            }else{
//                this.applyDeleteTagBg = true;
//            }
            this.$emit('deletePlayer');
            
        },
        dragStarted:function(event,reservationIndices){
            this.$emit("dragstart",event);


        }

    }
  
});
</script>
