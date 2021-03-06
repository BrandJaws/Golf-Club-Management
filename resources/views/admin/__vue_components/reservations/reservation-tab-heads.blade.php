<script>

Vue.component('reservation-tab-heads', {
    template: `
              		
                <div class="b-b nav-active-bg">
                  <ul class="nav nav-tabs">
                    <li class="nav-item" v-for="(reservation,reservationIndex) in reservationsByDate" v-if="reservation.dateIsVisible">
                      <a  :class="['nav-link', reservationIndex == 0 ? 'active' : '']" href data-toggle="tab" :data-target="'#tab'+(reservationIndex+1)">
                      <a class="dateCloseIcon" href="#." v-if="reservationIndex == 4" @click="restoreDefaultDatesClicked"><i class="fa fa-times"></i></a>
                      <p>@{{reservation.dayNumber}}</p><p>@{{reservation.dayName}}</p>
                      </a>
                    </li>
                    <li class="nav-item calender-more-li text-center" v-if="showMoreTabData">
                        <a id="date-reserv" href="#." class="nav-link">
                                <p><i class="fa fa-chevron-down" aria-hidden="true"></i><br>More</p>
                        </a>
                    </li>
            	
            
                  </ul>
                </div>
                        
          
            `,
    props: [
            "reservationsByDate",
            "showMoreTab"
            
            
            
    ],
    data: function () {
        
      return {
          showMoreTabData:this.showMoreTab != null && this.showMoreTab.toLowerCase() == 'true' ? true :false,
      }
    },
    methods:{
        restoreDefaultDatesClicked:function(){
            this.$emit("restore-default-dates");
        }
    },
  
});
</script>
