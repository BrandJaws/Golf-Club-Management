
<script>
var _onDeleteTagStyle = {
                           background: '#D30000'
                       };
Vue.component('reservation-player-tag', {
    template: `
                <div>		
                  <li :style ="[applyDeleteTagBg ? onDeleteTagStyle : null]">@{{reservationPlayer.playerName}}<a href="#." v-if="deletableData" @click.prevent="deletePlayerClicked"><i class="fa fa-times"></i></a></li>
                </div>                       
            `,
    props: [
            "reservationPlayer",
            "deletable"
            
            
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
            
        }
    }
  
});
</script>
