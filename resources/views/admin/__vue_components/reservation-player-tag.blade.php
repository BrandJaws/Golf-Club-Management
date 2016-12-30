
<script>
var _onDeleteTagStyle = {
                           background: '#D30000'
                       };
Vue.component('reservation-player-tag', {
    template: `
                <div>		
                  <li :style ="[applyDeleteTagBg ? onDeleteTagStyle : null]">@{{reservationPlayerData.playerName}}<a href="#." @click.prevent="deletePlayerClicked(reservationPlayerData)"><i class="fa fa-times"></i></a></li>
                </div>                       
            `,
    props: [
            "reservationPlayer"
            
            
    ],
    data: function () {
      
      return {
          reservationPlayerData : this.reservationPlayer,
          applyDeleteTagBg:false,
          onDeleteTagStyle:_onDeleteTagStyle,
          
      }
    },
    methods: {
        deletePlayerClicked: function(reservationPlayerData){
            if(this.applyDeleteTagBg){
                this.applyDeleteTagBg = false;
            }else{
                this.applyDeleteTagBg = true;
            }
            this.$emit('deletePlayer',reservationPlayerData);
            
        }
    }
  
});
</script>
