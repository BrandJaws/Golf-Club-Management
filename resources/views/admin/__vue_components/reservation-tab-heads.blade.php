<script>

Vue.component('reservation-tab-heads', {
    template: `
              		
                <div class="b-b nav-active-bg">
                  <ul class="nav nav-tabs">
                    <li class="nav-item" v-for="(reservation,reservationIndex) in reservationsByDateData">
                      <a :class="['nav-link', reservationIndex == 0 ? 'active' : '']" href data-toggle="tab" :data-target="'#tab'+(reservationIndex+1)">
                      <p>@{{reservation.date}}</p><p>@{{reservation.day}}</p>
                      </a>
                    </li>
                  </ul>
                </div>
                        
          
            `,
    props: [
            "reservationsByDate"
            
            
            
    ],
    data: function () {
        
      return {
          reservationsByDateData:this.reservationsByDate
      }
    }
  
});
</script>
