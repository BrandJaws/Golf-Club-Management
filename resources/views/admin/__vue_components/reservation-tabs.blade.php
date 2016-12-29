@include("admin.__vue_components.reservation-tab-heads")
<script>

Vue.component('reservation-tabs', {
    template: `
              		<div class="tsheet-tabs padd-15">
                	  	<reservation-tab-heads :reservations-by-date="reservationsByDate"></reservation-tab-heads>
                                <slot></slot>
                         </div>
          
            `,
    props: [
            "reservationsParent"
            
            
    ],
    data: function () {
       
      return {
          reservationsByDate:this.reservationsParent.reservationsByDate
      }
    }
  
});
</script>
