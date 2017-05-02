<style>

    li.gameEntry{
        background-color:#299a0b;
    }
    li.clubEntry{
        background-color:#b6e026;
    }
    li.notComingOnTime{
        background-color:#febf04;
    }
    li.defaultPlayerTag{
        background-color:#ccc;
    }

</style>
<script>

Vue.component('reservation-player-tag', {
    template: `

                  <li :class ="[computedStyleOfTag]" :draggable="draggable" @dragstart="dragStarted($event)" class="reservation-player-tag">@{{reservationPlayer.member_name}}<a href="#." v-if="deletableData" @click.prevent="deletePlayerClicked"><i class="fa fa-times"></i></a></li>

            `,
    props: [
            "reservationPlayer",
            "deletable",
            "draggable",
            "gameEntry",
            "clubEntry",
            "comingOnTime",
            
            
    ],
    data: function () {
      
      return {
         
          applyDeleteTagBg:false,
          deletableData:this.deletable == "true"? true : false,
          
      }
    },
    computed: {
        computedStyleOfTag: function(){


            if(this.gameEntry == 1){

                return 'gameEntry';

            }else if(this.clubEntry == 1){

                return 'clubEntry';

            }else if(this.comingOnTime == 'NO'){

                return 'notComingOnTime';

            }else{

                return null;

            }

            return overRideTagStyle;
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
        dragStarted:function(event){
            this.$emit("dragstart",event);


        }

    }
  
});
</script>
